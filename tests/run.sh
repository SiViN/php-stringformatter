#!/bin/bash

set -o nounset

docker run -it -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:5.3-cli vendor/bin/phpunit "$@"
docker run -it -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:5.4-cli vendor/bin/phpunit "$@"
docker run -it -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:5.5-cli vendor/bin/phpunit "$@"
docker run -it -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:5.6-cli vendor/bin/phpunit "$@"
docker run -it -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:7.0-cli vendor/bin/phpunit "$@"
