FROM php:8.3-fpm

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable PHP extensions
RUN docker-php-ext-enable mysqli

# Install FTP extension
RUN docker-php-ext-install ftp

# Install Intl extension dependencies
RUN apt-get update && apt-get install -y libicu-dev

# Install PHP intl extension
RUN docker-php-ext-install intl

# GIT and ZIP required for composer
RUN apt-get update && apt-get install -y zip git 

# For Node
ENV NODE_VERSION=14.21.3
RUN apt install -y curl
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"
RUN node --version
RUN npm --version

# For Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
