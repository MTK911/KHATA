FROM alpine:latest
LABEL Maintainer="MTK <root@mtk911.cf>"

# Add basics first
RUN apk update && apk upgrade && apk add \
	apache2 php7-apache2 php7 php7-phar php7-json php7-iconv php7-openssl

# Setup apache and php
RUN apk add \
	php7-xdebug \
	php7-mcrypt \
	php7-mbstring \
	php7-soap \
	php7-gmp \
	php7-pdo_odbc \
	php7-dom \
	php7-pdo \
	php7-zip \
	php7-bcmath \
	php7-gd \
	php7-odbc \
	php7-gettext \
	php7-xml \
	php7-xmlreader \
	php7-xmlwriter \
	php7-tokenizer \
	php7-xmlrpc \
	php7-bz2 \
	php7-pdo_dblib \
	php7-curl \
	php7-ctype \
	php7-session \
	php7-exif \
	php7-intl \
	php7-fileinfo \
	php7-apcu

# Problems installing in above stack
RUN apk add php7-simplexml

RUN cp /usr/bin/php7 /usr/bin/php \
    && rm -f /var/cache/apk/*

# Add apache to run and configure
RUN sed -i "s#^DocumentRoot \".*#DocumentRoot \"/app/KHATA\"#g" /etc/apache2/httpd.conf \
    && sed -i "s#/var/www/localhost/htdocs#/app/KHATA#" /etc/apache2/httpd.conf \
    && printf "\n<Directory \"/app/KHATA\">\n\tAllowOverride All\n</Directory>\n" >> /etc/apache2/httpd.conf

RUN mkdir /app && mkdir /app/KHATA && chown -R apache:apache /app && chmod -R 755 /app

ADD --chown=apache https://raw.githubusercontent.com/MTK911/KHATA/master/catch.php /app/KHATA/
ADD --chown=apache https://raw.githubusercontent.com/MTK911/KHATA/master/configuration.php /app/KHATA/
ADD --chown=apache https://raw.githubusercontent.com/MTK911/KHATA/master/index.php /app/KHATA/

# Dropping .htaccess to restrict access to sensitive file
RUN printf "<files KHATA_LOG>\norder deny,allow\ndeny from all\nallow from 127.0.0.1\n</files>\n\n<files RESPONDER_FILE>\norder deny,allow\ndeny from all\nallow from 127.0.0.1\n</files>\n" >> /app/KHATA/.htaccess

# Configuring KHATA with randomness

# Setting IV
RUN  abc=$(shuf -i 10000-99999 -n 1 && date) \
	&& xyz=$(echo $abc | sha256sum | head -c 64) \ 
	&& sed -i "s/1234567890123456/$xyz/" /app/KHATA/configuration.php

# Setting Key
RUN  abc=$(shuf -i 10000-99999 -n 1 && date) \
	&& xyz=$(echo $abc | sha256sum | head -c 64) \ 
	&& sed -i "s/abcdefghijklmnopabcdefghijklmnop/$xyz/" /app/KHATA/configuration.php

# Setting log file name
RUN  abc=$(shuf -i 10000-99999 -n 1 && date) \
	&& xyz=$(echo $abc | sha256sum | head -c 64) \ 
	&& sed -i "s/a744d9a1f4f287e51d681fdea6c10c0aec21773969db86f29dae0b31b0357763/$xyz/" /app/KHATA/configuration.php \
	&& sed -i "s/KHATA_LOG/$xyz/" /app/KHATA/.htaccess

# Setting responder file name
RUN  abc=$(shuf -i 10000-99999 -n 1 && date) \
	&& xyz=$(echo $abc | sha256sum | head -c 64) \ 
	&& sed -i "s/6DCDE155F1F900E157D25BB5C24B1A54D71EA35B78661ED9B1A8BE3532C3D4F5/$xyz/" /app/KHATA/configuration.php \
	&& sed -i "s/RESPONDER_FILE/$xyz/" /app/KHATA/.htaccess

# Setting randomness for session
RUN  abc=$(shuf -i 10000-99999 -n 1 && date) \
	&& xyz=$(echo $abc | sha256sum | head -c 10) \ 
	&& sed -i "s/pgRETaOKZO/$xyz/" /app/KHATA/configuration.php
	
RUN  abc=$(shuf -i 10000-99999 -n 1 && date) \
	&& xyz=$(echo $abc | sha256sum | head -c 10) \ 
	&& sed -i "s/79qlMPqv1H/$xyz/" /app/KHATA/configuration.php
	
# Setting log size
#RUN sed -i "s/1000000000/<ADD SIZE HERE>/" /app/KHATA/configuration.php

# Setting Username for KHATA
#RUN sed -i "s/admin/<ADD USERNAME HERE>/" /app/KHATA/configuration.php

# Setting Password for KHATA
#RUN sed -i "s/8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918/<ADD SHA256 HASHED PASSWORD HERE>/" /app/KHATA/configuration.php

# Setting timezone
#RUN sed -i "s/Asia\/Karachi/<ADD TIMEZONE HERE>/" /app/KHATA/configuration.php

# Setting refresh time
RUN sed -i "s/refresh=60/refresh=120/" /app/KHATA/configuration.php

EXPOSE 80
CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]