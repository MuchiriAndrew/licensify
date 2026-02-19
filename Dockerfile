# Use webdevops/php-nginx as base image
FROM webdevops/php-nginx:8.3

##change the shell
SHELL ["/bin/bash", "-c"]

ENV PHP_MAX_EXECUTION_TIME 110
# Copy the project files into the container
COPY . /production/licensify

# Set the laravel web folder
ARG WEB_PATH=/production/licensify/public
ENV WEB_DOCUMENT_ROOT=$WEB_PATH

# set the correct laravel app foler
ARG LARAVEL_PATH=/production/licensify
WORKDIR $LARAVEL_PATH

# # Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs



# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    nano \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip


# Install JavaScript dependencies
RUN npm install && npm run build


# configure packages
# RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install Composer and PHP dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install

# Copy the project files into the container
COPY . .

EXPOSE 80

# Laravel specific commands
RUN php artisan storage:link && \
    mkdir -p bootstrap/cache && \
    touch storage/logs/laravel.log && \
    chmod -R 777 storage && \
    chmod -R 777 bootstrap

ARG DB_CONNECTION
ARG DB_HOST
ARG DB_PORT
ARG DB_DATABASE
ARG DB_USERNAME
ARG DB_PASSWORD
