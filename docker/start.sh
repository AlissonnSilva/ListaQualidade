#!/usr/bin/env sh
set -eu

docker-compose up -d --build
echo "Application available at http://localhost:8000"
