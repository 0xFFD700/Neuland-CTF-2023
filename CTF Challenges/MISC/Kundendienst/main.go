package main

import (
	"bufio"
	"flag"
	"fmt"
	"log"
	"math/rand"
	"net"
	"os"
	"runtime"
	"strconv"
	"strings"

	llama "github.com/go-skynet/go-llama.cpp"
)

var (
	model      = "./model.gguf"
	promptFile = "./prompt.txt"
	template   = ""
	threads    = 4
	context    = 1024
	tokens     = 64
	gpulayers  = 0
	seed       = -1
)
var requestChannel = make(chan LLMRequest)

type LLMRequest struct {
	prompt   string
	response chan string
}

func handleConnection(conn net.Conn) {
	defer conn.Close()
	clientID := rand.Intn(1_000_000) // Generate a random client ID
	log.Printf("Client connected (ID: %d)\n", clientID)

	// Buffer to read and write data
	clientLog := strings.Clone(template)
	// r, _ := regexp.Compile("(.*)<\\|\\S+\\|>")

	s := bufio.NewScanner(conn)
	conn.Write([]byte("> "))
	for s.Scan() {
		read_line := s.Text()

		message := fmt.Sprintf("<|im_start|>user\n%s<|im_end|>\n<|im_start|>assistant", read_line)
		clientLog += message

		request := LLMRequest{prompt: clientLog, response: make(chan string)}

		requestChannel <- request
		for token := range request.response {
			clientLog += token
			switch token {
			case "<|im_end|>":
			default:
				_, err := conn.Write([]byte(token))
				if err != nil {
					log.Println("Error writing:", err)
					return
				}
			}
		}
		conn.Write([]byte("> "))
	}

	log.Printf("Client disconnected (ID: %d)\n", clientID)

	fileName := fmt.Sprintf("client_%d.txt", clientID)
	writeMessagesToFile(fileName, clientLog)
}

func writeMessagesToFile(fileName, message string) {
	file, err := os.OpenFile(fileName, os.O_CREATE|os.O_APPEND|os.O_WRONLY, 0660)
	if err != nil {
		log.Println("Error opening file:", err)
		return
	}
	defer file.Close()

	_, err = file.WriteString(message)
	if err != nil {
		log.Println("Error writing to file:", err)
	}
}

func llm_loop() {
	// Load Model
	l, err := llama.New(model, llama.EnableF16Memory, llama.SetContext(context), llama.EnableEmbeddings, llama.SetGPULayers(gpulayers))
	if err != nil {
		fmt.Println("Loading the model failed:", err.Error())
		os.Exit(1)
	}
	fmt.Printf("Model loaded successfully.\n")
	_ = l

	for {
		request := <-requestChannel
		_, err := l.Predict(request.prompt, llama.SetTokenCallback(func(token string) bool {
			request.response <- token
			return true
		}), llama.SetTokens(tokens), llama.SetThreads(threads), llama.SetTopK(90), llama.SetTopP(0.86), llama.SetStopWords("<|im_end|>"), llama.SetSeed(seed))
		if err != nil {
			panic(err)
		}
		request.response <- "\n"
		close(request.response)
	}
}

func main() {
	var port int

	flags := flag.NewFlagSet(os.Args[0], flag.ExitOnError)
	flags.StringVar(&model, "m", "./model.gguf", "path to gguf model file to load")
	flags.StringVar(&promptFile, "i", "./prompt.txt", "path to prompt.txt file")
	flags.IntVar(&gpulayers, "ngl", 0, "Number of GPU layers to use")
	flags.IntVar(&threads, "t", runtime.NumCPU(), "number of threads to use during computation")
	flags.IntVar(&tokens, "n", 512, "number of tokens to predict")
	flags.IntVar(&seed, "s", -1, "predict RNG seed, -1 for random seed")
	flags.IntVar(&port, "p", 1337, "server port number")

	err := flags.Parse(os.Args[1:])
	if err != nil {
		fmt.Printf("Parsing program arguments failed: %s", err)
		os.Exit(1)
	}

	// Load prompt
	b, err := os.ReadFile(promptFile) // just pass the file name
	if err != nil {
		fmt.Println("Error loading prompt:", err.Error())
		return
	}
	template = string(b)

	// Create a TCP listener on a specific port
	listener, err := net.Listen("tcp", net.JoinHostPort("", strconv.Itoa(port)))
	if err != nil {
		fmt.Println("Error listening:", err.Error())
		return
	}
	defer listener.Close()

	// Start the goroutine to process client messages
	go llm_loop()

	// Create a channel to control the number of concurrent clients
	maxClients := 8
	clientSemaphore := make(chan struct{}, maxClients)

	log.Println("Server is listening on port 1337")

	for {
		// Wait for a new connection
		conn, err := listener.Accept()
		if err != nil {
			fmt.Println("Error accepting connection:", err.Error())
			continue
		}

		// Use a goroutine to handle the connection
		go func() {
			// Acquire a semaphore slot for this client
			clientSemaphore <- struct{}{}
			// Handle the connection
			handleConnection(conn)
			// Release the semaphore slot when done
			<-clientSemaphore
		}()
	}
}
