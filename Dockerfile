FROM ubuntu:14.04
RUN apt-get update && apt-get install -y apache2 php5 php5-intl libapache2-mod-php5 php5-pgsql php5-mysql


# COPY index.php /var/www/html/
COPY / /var/www/html/
ADD 000-default.conf /etc/apache2/sites-enabled/000-default.conf
# COPY opcache.ini $PHP_INI_DIR/conf.d/

RUN chmod -R 777 /var/www
RUN a2enmod rewrite
RUN a2enmod php5
# RUN php5enmod opcache
RUN service apache2 restart

# Manually set up the apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

EXPOSE 80

CMD /usr/sbin/apache2ctl -D FOREGROUND
