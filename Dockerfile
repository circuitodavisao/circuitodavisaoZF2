FROM ubuntu:14.04

COPY / /var/www

ADD default /etc/nginx/conf.d/default.conf
ADD circuitodavisaonovo.com.br.pem /etc/ssl/circuitodavisaonovo.com.br.pem
ADD circuitodavisaonovo.com.br.key /etc/ssl/circuitodavisaonovo.com.br.key
RUN chmod -R 777 /var/www

EXPOSE 443

# Update cache and install Nginx
RUN apt-get update && apt-get -y install nginx php5-fpm vim memcached php5-memcached php5-memcache git php5-pgsql php5-mysql php5-curl curl wget

# Turn off daemon mode
# Reference: http://stackoverflow.com/questions/18861300/how-to-run-nginx-within-docker-container-without-halting
RUN echo "\ndaemon off;" >> /etc/nginx/nginx.conf
RUN echo "cgi.fix_pathinfo=0" >> /etc/php5/fpm/php.ini
# troca para tcp
RUN sudo vim /etc/php5/fpm/pool.d/www.conf +33 -c 'd' -c 'i' -c ':r! echo "listen = 127.0.0.1:9000"' -c ':wq'
RUN sudo vim /etc/memcached.conf +23 -c 'd' -c 'i' -c ':r! echo "-m 128"' -c ':wq'

CMD service php5-fpm start && nginx
