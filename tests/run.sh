#!/bin/bash

set -o nounset

echo 5.3
docker run -it -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:5.3-cli vendor/bin/phpunit "$@"
echo 5.4
docker run -it -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:5.4-cli vendor/bin/phpunit "$@"
echo 5.5
docker run -it -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:5.5-cli vendor/bin/phpunit "$@"
echo 5.6
docker run -it -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:5.6-cli vendor/bin/phpunit "$@"
echo 7.0
docker run -it -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:7.0-cli vendor/bin/phpunit "$@"
echo 7.1
docker run -it -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:7.1-cli vendor/bin/phpunit "$@"
