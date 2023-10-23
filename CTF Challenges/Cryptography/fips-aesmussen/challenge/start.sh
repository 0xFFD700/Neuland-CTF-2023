#!/usr/bin/bash

/tmp/cleanup.sh &
while true; do
    gunicorn -b 0.0.0.0:1337 --pythonpath flask "app:app"
done
