#!/bin/bash

sudo apt-get update

# Atualizando repositotio para instalar o docker

sudo apt-get install \
    apt-transport-https \
    ca-certificates \
    curl \
    software-properties-common

curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -

sudo apt-key fingerprint 0EBFCD88

sudo add-apt-repository \
   "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
   $(lsb_release -cs) \
   stable"

# instalando o docker

sudo apt-get update

sudo apt-get install docker-ce

# instalando sistema

sudo docker run --name bdpg -e POSTGRES_PASSWORD=qwaszx159753 -e POSTGRES_DB=postgres -e POSTGRES_USER=postgres -d postgres

sudo cd /home/developer/Documents/circuitodavisaoZF2

sudo docker build -t cv:0.5 .
sudo docker run -d --name cv --link bdpg:postgres -p 443:443 -v /home/developer/Documents/circuitodavisaoZF2:/var/www/html cv:0.5

# instalando o banco de dados

sudo docker exec -i bdpg psql -U postgres < /home/developer/Documents/circuitodavisaoZF2/dump/dump.sql
