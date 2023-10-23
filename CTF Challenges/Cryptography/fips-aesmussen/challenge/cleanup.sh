#!/usr/bin/bash

while true; do
  find ./flask/uploads/ -type f -not -name 'admin' -delete
  sleep 60m
done
