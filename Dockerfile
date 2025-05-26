FROM php:8.3-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    zip \
    git \
    curl \
    unzip \
    && docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    ftp \
    intl \
    zip \
    && docker-php-ext-enable mysqli

# Install Node via NVM
ENV NODE_VERSION=14.21.3
ENV NVM_DIR=/root/.nvm

RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash && \
    . "$NVM_DIR/nvm.sh" && \
    nvm install ${NODE_VERSION} && \
    nvm use ${NODE_VERSION} && \
    nvm alias default ${NODE_VERSION} && \
    ln -s "$NVM_DIR/versions/node/v${NODE_VERSION}/bin/node" /usr/bin/node && \
    ln -s "$NVM_DIR/versions/node/v${NODE_VERSION}/bin/npm" /usr/bin/npm

ENV PATH="$NVM_DIR/versions/node/v${NODE_VERSION}/bin/:${PATH}"

# Verify installations
RUN node --version && npm --version

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
