FROM php:7.2-apache
MAINTAINER atsu666

# apache user
RUN usermod -u 1000 www-data \
    && groupmod -g 1000 www-data

RUN rm /etc/apache2/sites-available/*
RUN rm /etc/apache2/sites-enabled/*

ADD config/apache2.conf /etc/apache2/
ADD config/task.pysr.com.conf /etc/apache2/sites-enabled/

# extension
RUN apt-get update \
    && apt-get install -y \
        libfreetype6-dev \
        libmcrypt-dev

# apache user
RUN usermod -u 1000 www-data \
    && groupmod -g 1000 www-data

# apache
RUN a2enmod rewrite
RUN a2enmod ssl

ADD entrypoint.sh /usr/local/bin/
RUN ["chmod", "+x", "/usr/local/bin/entrypoint.sh"]

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
