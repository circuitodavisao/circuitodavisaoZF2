#!/bin/bash
docker start bdpg
docker rm cv
docker run -d --name cv --link bdpg:postgres -p 443:443 -v /home/circuitodavisaoZF2:/var/www/html cv:0.3
