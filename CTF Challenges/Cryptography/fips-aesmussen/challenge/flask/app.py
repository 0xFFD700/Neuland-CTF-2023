import os, tempfile, time
from flask import Flask, Response, abort, request, send_file, send_from_directory
from werkzeug.utils import secure_filename
from Crypto.Random import get_random_bytes
from tokens import opportunistic_token, issue_token


app = Flask(__name__)
app.config["SECRET_KEY"] = get_random_bytes(16)
app.config["UPLOAD_FOLDER"] = "./uploads"
app.config['MAX_CONTENT_LENGTH'] = 1 * 1000 * 1000 # 1 MiB

@app.route("/")
def source():
    return send_file(__file__)

@app.route("/snippet/<user>", methods=["GET", "POST"])
@opportunistic_token
def snippet(user = None, claims = None):
    print(user, claims)
    if user is None:
        abort(400, "No user")
    filename = secure_filename(user)
    filepath = os.path.join(app.config["UPLOAD_FOLDER"], filename)

    match request.method:
        case "GET":
            if os.path.exists(filepath):
                if claims and (claims.get("sub", None) == filename or claims.get("is_admin", 0) == 1):
                    return send_from_directory(app.config["UPLOAD_FOLDER"], user)
                else:
                    abort(401)
            else:
                abort(404)
        case "POST":
            if os.path.exists(filepath):
                abort(409, "File already exists")
            if "file" not in request.files:
                abort(400, "No file part")

            file = request.files["file"]
            file.save(filepath)
            claims = { "sub": filename, "iat": int(time.time()), "is_admin": 0 }
            token = issue_token(claims)
            return { "token": token }
        case _:
            return Response(status=405)

if __name__ == "__main__":
	app.run()
