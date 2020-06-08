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
sudo apt-get install docker-ce=18.06.1~ce~3-0~ubuntu

# instalando sistema
# PRODUCAO
# sudo docker run --name bdpg -e POSTGRES_PASSWORD=qwaszx159753 -e POSTGRES_DB=postgres -e POSTGRES_USER=postgres -d -v /home/circuitodavisaoZF2/my-postgres.conf:/etc/postgresql/postgresql.conf postgres -c 'config_file=/etc/postgresql/postgresql.conf'
# HOMOLOGACAO
sudo docker run --name bdpg -e POSTGRES_PASSWORD=qwaszx159753 -e POSTGRES_DB=postgres -e POSTGRES_USER=postgres -d postgres

sudo cd /home/circuitodavisaoZF2
sudo chmod -R 777 data/

sudo docker build -t cv:0.1 .
sudo docker run -d --name cv --link bdpg:postgres -p 443:443 -v /home/circuitodavisaoZF2:/var/www/html cv:0.1

# instalando o banco de dados
# sudo scp root@51.89.96.128:/home/backup/dump /home/dump
sudo tar -xzvf dump
sudo docker exec -i bdpg psql -U postgres < /home/dump.sql
