FROM ubuntu:14.04
RUN apt-get update && apt-get install -y git apache2 php5 php5-intl libapache2-mod-php5 php5-pgsql php5-mysql php5-curl curl apache2-utils

COPY / /var/www/html/
ADD 000-default.conf /etc/apache2/sites-enabled/000-default.conf
RUN mkdir /etc/apache2/ssl
ADD circuitodavisaonovo.com.br.pem /etc/apache2/ssl/circuitodavisaonovo.com.br.pem
ADD circuitodavisaonovo.com.br.key /etc/apache2/ssl/circuitodavisaonovo.com.br.key

# Add crontab file in the cron directory
#ADD crontab /etc/cron.d/cron
# Give execution rights on the cron job
#RUN chmod 0644 /etc/cron.d/cron

RUN chmod -R 777 /var/www
RUN a2enmod rewrite
RUN a2enmod php5
RUN a2enmod ssl
RUN a2enmod cache
RUN a2enmod cache_disk
RUN a2enmod expires
RUN a2enmod headers
RUN echo "<IfModule mod_cache_disk.c>" >> /etc/apache2/mods-enabled/cache_disk.conf
RUN echo "    CacheRoot /var/cache/apache2/mod_cache_disk" >> /etc/apache2/mods-enabled/cache_disk.conf
RUN echo "    CacheDirLevels 2" >> /etc/apache2/mods-enabled/cache_disk.conf
RUN echo "    CacheDirLength 1" >> /etc/apache2/mods-enabled/cache_disk.conf
RUN echo "</IfModule>" >> /etc/apache2/mods-enabled/cache_disk.conf
RUN a2enmod authn_socache
RUN a2enmod socache_shmcb
RUN echo "AuthnCacheSOCache shmcb" >> /etc/apache2/apache2.conf
RUN echo "opcache.enable=1" >> /etc/php5/apache2/php.ini
RUN echo "opcache.memory_consumption=128" >> /etc/php5/apache2/php.ini
RUN echo "opcache.interned_strings_buffer=8" >> /etc/php5/apache2/php.ini
RUN echo "opcache.max_accelerated_files=4000" >> /etc/php5/apache2/php.ini
RUN echo "opcache.revalidate_freq=60" >> /etc/php5/apache2/php.ini
RUN echo "opcache.fast_shutdown=1" >> /etc/php5/apache2/php.ini
RUN echo "opcache.enable_cli=1" >> /etc/php5/apache2/php.ini
RUN service apache2 restart

# Manually set up the apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

#EXPOSE 80
EXPOSE 443

CMD /usr/sbin/apache2ctl -D FOREGROUND
