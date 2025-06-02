FROM php:8.3-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    zip \
    git \
    libzip-dev \
    libicu-dev \
    unzip \
    curl \
    && docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    ftp \
    intl \
    zip \
    && docker-php-ext-enable mysqli

# Install Node.js via NVM
ENV NODE_VERSION=20.13.1
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION} \
    && nvm use v${NODE_VERSION} \
    && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"
RUN node --version
RUN npm --version

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
