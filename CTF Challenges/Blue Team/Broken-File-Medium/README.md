# Broken File - Medium

## Description

Get the file from the challenge "Broken File - Easy" and reconstruct it.

## Flag

`nland{1_l1k3_f1l3h34d3Rs}`

## Solution

The challenge "Broken File - Easy" already indicated that this is a video format. However, the header was modified. This is a `.mov` file (can be idientified through the strings). 

A `.mov` file has it's magic bytes at byte 4. Those are `66 74 79 70 71 74 20 20`. Those were removed from the file. However, this is not the core part that is missing. Various blog posts and the `.mov` standard references the `MOOV` atom. This atom is missing too. The exact position of the atom is not standardized. Nevertheless, a hexdump indicates the bytes `DE AD BE EF`. This is the position where the `MOOV` atom is missing. Therefore, the bytes `6D 6F 6F 76` (`MOOV` in ASCII) must be added.

Interesting detail: most video players don't care about the magic bytes. They just need the `MOOV` atom.
