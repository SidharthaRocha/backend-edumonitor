# Use uma imagem oficial do PHP com Apache
FROM php:8.2-apache

# Copia os arquivos do backend para o diretório padrão do Apache
COPY . /var/www/html

# Define o config.php como arquivo de entrada principal
RUN echo "DirectoryIndex config.php" >> /etc/apache2/apache2.conf

# Ativa o mod_rewrite para redirecionamentos, se necessário
RUN a2enmod rewrite

# Exponha a porta 80
EXPOSE 80
