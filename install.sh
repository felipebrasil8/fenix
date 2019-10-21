#!/bin/bash
echo 'git...'
#git clone git@github.com:felipebrasil8/fenix.git
#mv fenix/* .
#rm -rf fenix
docker-compose build && docker-compose up -d && echo "Please wait while service is up..."
