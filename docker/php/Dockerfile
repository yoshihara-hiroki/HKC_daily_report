# 8.4 -> 8.3 に変更（安定版を使用）
FROM php:8.3-fpm

# パッケージのインストール
# libicu-dev を追加しました
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring zip bcmath intl opcache

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.jsのインストール (Vite用)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# 作業ディレクトリ
WORKDIR /var/www/html

# 権限設定
RUN chown -R www-data:www-data /var/www/html