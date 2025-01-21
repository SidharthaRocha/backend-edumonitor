# Use uma imagem oficial do PHP com Apache
FROM php:8.2-apache

# Copia os arquivos do backend para o diretório padrão do Apache
COPY . /var/www/html

# Exponha a porta 80
EXPOSE 80
