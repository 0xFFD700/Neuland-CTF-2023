# Broken File - Easy

Upload the file `broken`. The file `IMG_5259-org.MOV` is the original video.

## Description

This file is broken. Can you find out what was used to "create" it?

The flag is in the format `nland{<your-input-without-spaces>}`.

## Flag

`nland{iPhone13mini}`

## Solution

Run `strings` on it to find various metadata. This is a `.mov` file where the header is corrupted (Broken File - Medium). However, the strings indicate that it was recorded with an iPhone 13 mini.
