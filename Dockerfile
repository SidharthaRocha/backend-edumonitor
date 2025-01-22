# Use uma imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instala o Composer no contêiner
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia os arquivos do backend para o diretório padrão do Apache
COPY . /var/www/html

# Instala as dependências do Composer dentro do contêiner
RUN composer install --working-dir=/var/www/html

# Exponha a porta 80
EXPOSE 80
