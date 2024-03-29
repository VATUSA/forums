FROM php:8.1-fpm-alpine

ENV TIMEZONE            America/Chicago
ENV PHP_MEMORY_LIMIT    128M
ENV MAX_UPLOAD          20M
ENV PHP_MAX_FILE_UPLOAD 200
ENV PHP_MAX_POST       100M

COPY docker /

RUN	addgroup -S application && adduser -SG application application && \
	apk update && \
	apk upgrade && \
	apk add --update tzdata && \
	cp /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && \
	echo "${TIMEZONE}" > /etc/timezone && \
	apk add --update \
	nginx \
	supervisor \
	openssh-client && \
	cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini && \
	sed -i "s|;*daemonize\s*=\s*yes|daemonize = no|g" /usr/local/etc/php-fpm.conf && \
	sed -i "s|;*listen\s*=\s*127.0.0.1:9000|listen = 9000|g" /usr/local/etc/php-fpm.d/www.conf && \
	sed -i "s|;*listen\s*=\s*/||g" /usr/local/etc/php-fpm.d/www.conf && \
	sed -i "s|;*date.timezone =.*|date.timezone = ${TIMEZONE}|i" /usr/local/etc/php/php.ini && \
	sed -i "s|;*memory_limit =.*|memory_limit = ${PHP_MEMORY_LIMIT}|i" /usr/local/etc/php/php.ini && \
	sed -i "s|;*upload_max_filesize =.*|upload_max_filesize = ${MAX_UPLOAD}|i" /usr/local/etc/php/php.ini && \
	sed -i "s|;*max_file_uploads =.*|max_file_uploads = ${PHP_MAX_FILE_UPLOAD}|i" /usr/local/etc/php/php.ini && \
	sed -i "s|;*post_max_size =.*|post_max_size = ${PHP_MAX_POST}|i" /usr/local/etc/php/php.ini && \
	sed -i "s|;*cgi.fix_pathinfo=.*|cgi.fix_pathinfo= 0|i" /usr/local/etc/php/php.ini && \
	mkdir /etc/supervisor.d && \
	mkdir /www && \
	chown application:application /www && \
	apk del tzdata && \
	rm -rf /var/cache/apk/*

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli

WORKDIR /www
COPY . /www

ENTRYPOINT ["/bin/sh","/www/build.sh"]

EXPOSE 80