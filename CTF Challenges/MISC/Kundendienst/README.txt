# Compiling
Clone https://github.com/go-skynet/go-llama.cpp.git to ./go-llama.cpp, recursing all submodules:
`git clone --recurse-submodules https://github.com/go-skynet/go-llama.cpp.git`
This used to be a git submodule, but this is broken for some reasons.
Afterwards, do a normal docker build.

# Installation
Mount a LLM model to `/model.gguf`. For this challenge, a German model works best. 
Recommendation: TheBloke/em_german_leo_mistral-GGUF, quantization Q4_K_M.
-> https://huggingface.co/TheBloke/em_german_leo_mistral-GGUF/blob/main/em_german_leo_mistral.Q4_K_M.gguf

Mount the prompt to `/prompt.txt`.

The container exposes a tcp service on port 1337.
