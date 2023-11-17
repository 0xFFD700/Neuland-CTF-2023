package main

import (
    "fmt"
    "bufio"
    "os"
    "strings"
    "bytes"
)

func main() {
    s1 := "nland{"
    s2 := "th1s_"
    s3 := "1s"
    s4 := "_d3f1n3tly_"
    s5 := "n0t_th3" 
    s6 := "_g4lf"
    s7 := "w0w_"
    s8 := "y0u_f0und_"
    s9 := "th3_"
    s10 := "g4lf!}"
    s11 := "fxfs"
    s12 := "s0_m3ny_"
    s13 := "4w3s0m3_g4lfs"
    s14 := "1n_th1s_b1n4ry}"
    s15 := "nland{1t_1s_"
    s16 := "th3_g4lf_"
    s17 := "1_w4s_"
    s18 := "l00k1ng_for!}"
    s19 := "th3_"

    a1 := []string{"x33", "g00", "h3r3", s16, s19}
    a2 := []string{"x33", "g00", "h3r3", s19}
    a3 := []string{"xf33", s11, s17}
    a4 := []string{s11, "xf33"}

    b1 := xor(a1, a2)
    b2 := xor(a3, a4)

    var b3 bytes.Buffer
    b3.WriteString(s1)
    b3.WriteString(s4)
    b3.WriteString(s5) 
    b3.WriteString(s6)
    b3.WriteString(s7)

    var b4 strings.Builder
    b4.WriteString(s2)
    b4.WriteString(s8)
    b4.WriteString(s9)
    b4.WriteString(s10)
    b4.WriteString(s11)

    var b5 bytes.Buffer 
    b5.WriteString(s3)
    b5.WriteString(s12)
    b5.WriteString(s13)
    b5.WriteString(s14)
    b5.WriteString(s15)

    var b6 strings.Builder 
    b6.WriteString(s16)
    b6.WriteString(s17)
    b6.WriteString(s18)


    welcome := `Yo, my dudes! 🤘 Just wanted to drop in and let you know, I'm absolutely stacked right now - gains on gains, you feel me? 💪 But here's the deal, I'm on a mission, and I need your help to find this elusive "galf".
I'm talking about a digital treasure hunt, bros! 🚩 Imagine it's like the Olympics of the internet, and I'm out here flexing my mental muscles, but the galf is playing hard to get. It's like the final boss level, and I need my squad to assemble and help me conquer it!
So, if any of you have the 411 on where this galf is hiding, hit me up ASAP. Slide into my DMs, drop a comment, whatever it takes. We're in this together, and together we're unstoppable! Let's get that W, my internet comrades! 🏆 #GalfHunters #SquadGoals #InternetChampions
    `

    fail := `Oh, snap! 🙈 It looks like you might've taken a wrong turn in the cyber-jungle and snagged the wrong galf. Your internet compass got a little wonky, you know how it is. 🧭😅
But hey, no worries, we're all about that trial and error life. We learn from our mishaps and come back even stronger. So, if anyone's got the deets on the actual galf location, hit me up, and let's get back on the grind. We'll laugh about this one day when we're sitting on the digital throne of victory. 💻👑
Keep the faith, my internet warriors! You got this! 💪🌐 #GalfMisadventures #NextTimeForSure #StillStacked
    `

    success := `Boom! You did it, my internet legends! 🚩💥 You've got the right galf, and you're officially champions of the cyber realm. Cue victory dance. 💃💻
Massive shoutout to the squad for coming through and dropping those knowledge bombs. You guys are the real MVPs! 🏆 You conquered the digital wilderness and claimed our prize. This is like our digital trophy, a symbol of our collective awesomeness.
Celebrate with me, fam! Pop virtual confetti, do a little victory dab, or whatever feels right in the cyber-party. 🎉🕺 #InternetChamps #SquadGoalsUnlocked
    `

    fmt.Println(welcome)

    fmt.Println("Hit me with the flag bro:")
    inp, _, err := bufio.NewReader(os.Stdin).ReadLine()
    if err != nil {
            fmt.Println("Uhm, something went wrong!", err)
            fmt.Println("nland{th1s_1s_d3f1n3tly_n0t_th3_g4lf")
    }

    v1 := s1
    _ = v1
    v2 := fmt.Sprintf("%s%s", s2, s3)
    _ = v2
    v3 := b1[0]
    _ = v3
    v4 := b2[0]
    _ = v4

    if string(inp) == s6 + s9 + s11 + b1[0] + a1[1] {
        fmt.Println(success)
        fmt.Println(s1 + s7 + s8 + b1[0] + b2[0] + s18)
    } else {
        fmt.Println(fail)
    }
}

func xor(list1, list2 []string) []string {
    set1 := make(map[string]bool)
    for _, s := range list1 {
        set1[s] = true
    }
    set2 := make(map[string]bool)
    for _, s := range list2 {
        set2[s] = true
    }

    var c []string
    for _, s := range list1 {
        if !set2[s] {
          c = append(c, s)
        }
    }
    for _, s := range list2 {
        if !set1[s] {
          c = append(c, s)
        }
    }
    return c
}
