FROM ubuntu:14.04

RUN apt-get update && apt-get -y install nginx php5-fpm vim memcached php5-memcached php5-memcache git php5-pgsql php5-mysql php5-curl curl wget

COPY / /var/www

ADD default /etc/nginx/conf.d/default.conf
ADD circuitodavisaonovo.com.br.pem /etc/ssl/circuitodavisaonovo.com.br.pem
ADD circuitodavisaonovo.com.br.key /etc/ssl/circuitodavisaonovo.com.br.key
ADD nginx.conf /etc/nginx/nginx.conf
ADD www.conf /etc/php5/fpm/pool.d/www.conf
RUN chmod -R 777 /var/www

EXPOSE 443

RUN echo "cgi.fix_pathinfo=0" >> /etc/php5/fpm/php.ini
RUN sudo vim /etc/php5/fpm/php.ini +385 -c 'd' -c 'i' -c ':r! echo "max_execution_time = 120"' -c ':wq'
RUN sudo vim /etc/memcached.conf +23 -c 'd' -c 'i' -c ':r! echo "-m 4096"' -c ':wq'
CMD service php5-fpm start && nginx
