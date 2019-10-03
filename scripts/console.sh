#!/usr/bin/env bash

if [ "$1" != "" ]; then
    echo "Positional parameter 1 contains something"

else
    echo "Positional parameter 1 is empty"
    exit
fi

docker container exec -it tell-itus_php_1 $1
