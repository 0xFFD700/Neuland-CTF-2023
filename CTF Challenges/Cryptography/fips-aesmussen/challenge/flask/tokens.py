import json
from base64 import b64encode, b64decode
from functools import wraps
from flask import request, abort, Response
from flask import current_app
from Crypto.Cipher import AES
from Crypto.Util.Padding import pad, unpad


def opportunistic_token(f):
    @wraps(f)
    def decorated(*args, **kwargs):
        claims = None
        try:
            if "Authorization" in request.headers:
                token = request.headers["Authorization"].split(" ")[1]
                header, _, iv, ct, _ = [b64decode(x) for x in token.split(".")]

                header = json.loads(header.decode("utf8"))
                assert (header.get("enc", None) == "A128CBC")
                assert (header.get("alg", None) == "dir")
                assert (header.get("kid", None) == "flask.secret_key")

                cipher = AES.new(current_app.secret_key, AES.MODE_CBC, iv=iv)
                pt_bytes = cipher.decrypt(ct)
                pt = unpad(pt_bytes, AES.block_size).decode("utf8")
                claims = json.loads(pt)
        except ValueError:
            abort(400, "Malformed JWE")
        except:
            abort(500, "Unknown error")

        return f(*args, claims=claims, **kwargs)

    return decorated

def issue_token(claims):
    plaintext = json.dumps(claims).encode("utf8")

    header = json.dumps({
        "enc": "A128CBC",
        "alg": "dir",
        "kid": "flask.secret_key",
    }).encode("utf8")
    cek = b""
    mac = b""

    cipher = AES.new(current_app.secret_key, AES.MODE_CBC)
    ct = cipher.encrypt(pad(plaintext, AES.block_size))
    iv = cipher.iv

    return ".".join(b64encode(x).decode("utf8") for x in [header, cek, iv, ct, mac])
