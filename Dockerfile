# Use uma imagem oficial do PHP com Apache
FROM php:8.2-apache

# Copie os arquivos do backend para o diretório padrão do Apache
COPY ./backend /var/www/html/

# Defina o arquivo de entrada como 'config.php', se for o principal
RUN echo "DirectoryIndex config.php" >> /etc/apache2/apache2.conf

# Ativa o mod_rewrite, se necessário para redirecionamentos
RUN a2enmod rewrite

# Certifique-se de que os arquivos possuem as permissões corretas
RUN chown -R www-data:www-data /var/www/html

# Exponha a porta 80
EXPOSE 80

# Comando para rodar o Apache em primeiro plano
CMD ["apache2-foreground"]
