FROM php:8.3-fpm

# Instal dependensi sistem dan ekstensi PHP yang dibutuhkan Laravel & MySQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    mariadb-client

# Bersihkan cache sistem untuk menghemat ukuran image
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instal ekstensi PHP (pdo_mysql wajib untuk MySQL, zip untuk composer)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instal Composer dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instal Node.js (versi LTS/20) & NPM untuk memproses Vite/Tailwind
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Tentukan direktori kerja di dalam kontainer
WORKDIR /var/www

# Salin seluruh kode proyek ke dalam kontainer
COPY . .

# Berikan izin akses (permission) folder storage dan bootstrap cache ke user www-data
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Port yang akan digunakan Laravel
EXPOSE 8000

# Jalankan server bawaan Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000