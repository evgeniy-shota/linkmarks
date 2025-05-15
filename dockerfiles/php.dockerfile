FROM php:8.4-fpm-alpine3.21

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN apk add libjpeg libpng libwebp
# RUN apk add imagemagick git autoconf

# WORKDIR /var/lib

# RUN git clone https://github.com/Imagick/imagick \
# && cd imagick \
# && phpize && ./configure \
# && make \
# && make install \
# && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/ext-imagick.ini

# RUN apk add --no-cache --virtual .imagick-build-dependencies \
# autoconf \
# # curl \
# g++ \
# gcc \
# git \
# # imagemagick-dev \
# libtool \
# make \
# tar \
# && apk add --virtual .imagick-runtime-dependencies \
# imagemagick 

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# MacOS staff group's gid is 20, so is the dialout group in alpine linux. We're not using it, let's just remove it.
RUN delgroup dialout

RUN addgroup -g ${GID} --system laravel
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel

RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --no-cache --virtual .imagick-build-dependencies \
  autoconf \
  curl \
  g++ \
  gcc \
  git \
  imagemagick-dev \
  libtool \
  make \
  tar \
&& apk add --virtual .imagick-runtime-dependencies \
  imagemagick \

&& IMAGICK_TAG="3.7.0" \
&& git clone -o ${IMAGICK_TAG} --depth 1 https://github.com/mkoppanen/imagick.git /tmp/imagick \
&& cd /tmp/imagick \

&& phpize \
&& ./configure \
&& make \
&& make install \

# && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/ext-imagick.ini \
&& touch /usr/local/etc/php/conf.d/php.ini \
&& echo "extension=imagick.so" >> /usr/local/etc/php/conf.d/php.ini \

&& apk del .imagick-build-dependencies

#RUN mkdir -p /usr/src/php/ext/redis \
#    && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
#    && echo 'redis' >> /usr/src/php-available-exts \
#    && docker-php-ext-install redis
    
USER laravel

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
