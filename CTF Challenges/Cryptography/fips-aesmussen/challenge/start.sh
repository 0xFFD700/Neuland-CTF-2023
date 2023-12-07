#!/bin/sh

DIR="$(dirname "$(realpath "$0")")"
"$DIR/cleanup.sh" &
while true; do
    gunicorn -b 0.0.0.0:1337 --pythonpath "$DIR/flask" "app:app"
done
