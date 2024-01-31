FROM php:8.2-fpm-alpine3.18

RUN apk update \
 && apk add --no-cache $PHPIZE_DEPS \
    bash \
    git \
    zip \
    unzip \
    nodejs \
    python3 \
    gnupg \
    php82-pdo \
    unixodbc-dev \
    postgresql-dev \
    postgresql-client \
    openssh \
    openssl \
    openssl-dev \
    autoconf \
    g++ \
    make

#> PHP extensions ##
RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql
#< PHP extensions ##

#> Install composer ##
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
#< Install composer ##

#> Install composer security checker ##
RUN wget -O /usr/local/bin/local-php-security-checker "https://github.com/fabpot/local-php-security-checker/releases/download/v2.0.4/local-php-security-checker_2.0.4_linux_amd64" \
    && chmod +x /usr/local/bin/local-php-security-checker
#< Install composer security checker ##

#> Install npm ##
RUN apk add --update nodejs npm
#< Install npm ##

ENV PATH /usr/local/src/vendor/bin:$PATH

#######################
# Override the image  #
# ENTRYPOINT to add   #
# some logic          #
#######################
COPY entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

ENTRYPOINT ["entrypoint"]