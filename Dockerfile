# Use uma imagem oficial do PHP com Apache
FROM php:8.2-apache

# Copia todos os arquivos da pasta atual para o diretório padrão do Apache
COPY . /var/www/html/

# Ativa o mod_rewrite para redirecionamentos, se necessário
RUN a2enmod rewrite

# Exponha a porta 80
EXPOSE 80
