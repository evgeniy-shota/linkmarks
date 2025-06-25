FROM php:8.4-fpm-alpine3.21

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN apk add --update linux-headers

RUN apk add libjpeg \
  libpng \
  libpng-dev \
  libwebp \
  gd \
  #php84-pecl-xdebug \
  libjpeg-turbo-dev \
  freetype-dev \  
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd \
  && docker-php-ext-install pdo pdo_mysql 

#RUN apk add --no-cache --virtual .xdebug-build-dependencies \
#  g++ \
#  gcc \
#  git \
#  autoconf \
#  automake \
#  tar \
#  make \
#  php84-dev \
#
#  && XDEBUG_TAG=3.4.4 \
#  && git clone -o ${XDEBUG_TAG} --depth 1 https://github.com/xdebug/xdebug.git /tmp/xdebug \
#  && cd /tmp/xdebug \
#  && phpize \
#  && ./configure --enable-xdebug \
#  && make \
#  && make install \
#  && cp modules/xdebug.so \
#  && touch /usr/local/etc/php/conf.d/99-xdebug.ini \
#  && echo "zend_extension=xdebug" >> /usr/local/etc/php/conf.d/99-xdebug.ini \
#  
#  && apk del .xdebug-build-dependencies 
  
#RUN curl -sSLf \
#        -o /usr/local/bin/install-php-extensions \
#        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
#    chmod +x /usr/local/bin/install-php-extensions && \
#    install-php-extensions gd 
# RUN apk add imagemagick git autoconf

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
