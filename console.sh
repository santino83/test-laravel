#!/usr/bin/env bash

CMD=$1
ARGS="${@:2}"
DOCKER_BIN=$(which docker)
WORK_DIR="$PWD"
DEFAULT_IMAGE_NAME="$(basename $WORK_DIR)"
DEFAULT_CONTAINER_NAME="$(basename $WORK_DIR)"
LOCAL_PORT=8000

function usage() {
  echo "Invalid command: $CMD\n"
  echo "Usage: $0 <command_name> [...<args>]"
  exit 1
}

function build() {

  IMAGE_NAME=$1
  [ -z "$IMAGE_NAME" ] && IMAGE_NAME="$DEFAULT_IMAGE_NAME"

  $DOCKER_BIN build -t "$IMAGE_NAME" "$WORK_DIR"

  exit 0
}

function run() {

  IMAGE_NAME=$1
  [ -z "$IMAGE_NAME" ] && IMAGE_NAME="$DEFAULT_IMAGE_NAME"

  CONTAINER_NAME=$2
  [ -z "$CONTAINER_NAME" ] && CONTAINER_NAME="$DEFAULT_CONTAINER_NAME"

  $DOCKER_BIN run -d -p "$LOCAL_PORT":80 --name "$CONTAINER_NAME" "$IMAGE_NAME"

}

if type "$CMD" > /dev/null 2>&1; then
  "$CMD" $ARGS
else
  usage
fi

