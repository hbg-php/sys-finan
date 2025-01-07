# Use uma imagem base do PHP com Apache (mod_php)
FROM php:8.2-apache

# Certificar-se de que o repositório APT está atualizado e instalar pacotes necessários
#RUN apt-get update && apt-get upgrade -y && apt-get dist-upgrade -y

# Instalar dependências do sistema para extensões PHP
RUN apt-get update && apt-get install -y \
    ca-certificates \
    curl \
    git \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libzip-dev \
    libbz2-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    default-libmysqlclient-dev \
    && apt-get clean

# Instalar as extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql intl zip bcmath

# Instalar o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Habilitar mod_rewrite do Apache (necessário para URLs amigáveis no Laravel)
RUN a2enmod rewrite

# Copiar arquivos de configuração do Apache
COPY ./apache/000-default.conf /etc/apache2/sites-available/000-default.conf

#RUN a2ensite 000-default.conf

# Definir o diretório de trabalho
WORKDIR /var/www/html/

COPY ./src /var/www/html/

COPY ./src/composer.json /var/www/html/

# Configurar permissões de pasta para o Laravel (storage e bootstrap/cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN composer install --no-dev --optimize-autoloader

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Expor as portas configuradas no docker-compose.yml (8085 para HTTP e 8443 para HTTPS)
EXPOSE 80
#EXPOSE 9000

# Iniciar o Apache em primeiro plano
CMD ["apache2-foreground"]
