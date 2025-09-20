# Use the official PHP image with FrankenPHP
FROM dunglas/frankenphp:php8.2-bookworm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions including zip
RUN install-php-extensions \
    ctype \
    curl \
    dom \
    fileinfo \
    filter \
    hash \
    mbstring \
    openssl \
    pcre \
    pdo \
    pdo_mysql \
    session \
    tokenizer \
    xml \
    zip

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy package.json files
COPY package.json package-lock.json ./

# Install Node dependencies
RUN npm ci

# Copy application code
COPY . .

# Build assets
RUN npm run build

# Create required directories and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Optimize Laravel
RUN php artisan config:cache \
    && php artisan event:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose port
EXPOSE 8000

# Start the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
