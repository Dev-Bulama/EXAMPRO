#!/usr/bin/env bash
set -euo pipefail

usage(){
  cat <<USAGE
Usage: $0 [-d database] [-u user] [-p password] [-h host] [-P port]

Bootstraps the ExamPro platform by copying environment files, running the MySQL schema,
and seeding the database with default data. Requires the `mysql` CLI when database
initialisation flags are provided.
USAGE
}

DB_NAME=""
DB_USER=""
DB_PASS=""
DB_HOST="127.0.0.1"
DB_PORT="3306"

while getopts ":d:u:p:h:P:?" opt; do
  case "$opt" in
    d) DB_NAME="$OPTARG" ;;
    u) DB_USER="$OPTARG" ;;
    p) DB_PASS="$OPTARG" ;;
    h) DB_HOST="$OPTARG" ;;
    P) DB_PORT="$OPTARG" ;;
    ?) usage; exit 0 ;;
    :) echo "Option -$OPTARG requires an argument" >&2; exit 1 ;;
    *) usage; exit 1 ;;
  esac
done

log(){
  printf '[exampro] %s\n' "$1"
}

if [[ ! -f .env ]]; then
  log "Creating .env from .env.example"
  cp .env.example .env
fi

if [[ -n "$DB_NAME" && -n "$DB_USER" ]]; then
  if ! command -v mysql >/dev/null 2>&1; then
    echo "mysql client not available; skip database bootstrap" >&2
    exit 1
  fi

  log "Running database schema against $DB_USER@$DB_HOST:$DB_PORT/$DB_NAME"
  mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < database/migrations/001_create_tables.sql

  log "Seeding database via bin/seed.php"
  php bin/seed.php
else
  log "Database credentials not provided; skipped schema + seed."
fi

log "Setup complete. Start the API with: php -S 0.0.0.0:8000 public/index.php"
