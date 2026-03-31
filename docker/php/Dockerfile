FROM php:8.4-fpm

# Argumentos que le pasaremos desde el archivo docker-compose
ARG user
ARG uid

# 1. Instalar dependencias del sistema y librerías gráficas (GD para firmas/imágenes)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev

# 2. Limpiar cache para que el contenedor pese menos
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Instalar extensiones de PHP (GD configurado, BCMath, PDO MySQL, etc)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath zip

# 4. Traernos Composer (El motor para instalar tu carpeta vendor)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Instalar Node.js y NPM (Necesario para compilar tu diseño con Tailwind/Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 6. Crear un usuario de sistema para evitar problemas de permisos de administrador
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# 7. Establecer directorio de trabajo
WORKDIR /var/www

# 8. Decirle a Docker que de ahora en adelante todo se ejecute con este usuario
USER $user