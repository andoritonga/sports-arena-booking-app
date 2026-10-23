FROM php:8.4-apache

# Install MySQL/MariaDB database extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache mod_rewrite and mod_headers for security
RUN a2enmod rewrite headers

# Harden PHP configuration against RCE & php2shell
RUN { \
    echo 'expose_php = Off'; \
    echo 'display_errors = Off'; \
    echo 'log_errors = On'; \
    echo 'allow_url_fopen = Off'; \
    echo 'allow_url_include = Off'; \
    echo 'session.cookie_httponly = 1'; \
    echo 'session.use_strict_mode = 1'; \
    echo 'session.cookie_samesite = Lax'; \
    echo 'upload_max_filesize = 5M'; \
    echo 'post_max_size = 8M'; \
    echo 'disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source,pcntl_exec,eval,assert'; \
} > /usr/local/etc/php/conf.d/security-hardening.ini

# Prevent PHP execution inside upload directories at the Apache level
RUN { \
    echo '<Directory "/var/www/html/gambar">'; \
    echo '    Options -ExecCGI -Includes -Indexes'; \
    echo '    AllowOverride None'; \
    echo '    RemoveHandler .php .phtml .php3 .php4 .php5 .php7 .php8 .phar .inc'; \
    echo '    RemoveType .php .phtml .php3 .php4 .php5 .php7 .php8 .phar .inc'; \
    echo '    <FilesMatch "(?i)\.(php|phtml|php[0-9]|phar|inc|pl|py|cgi|sh|bash|exe)$">'; \
    echo '        Require all denied'; \
    echo '    </FilesMatch>'; \
    echo '    Header set X-Content-Type-Options "nosniff"'; \
    echo '    Header set Content-Security-Policy "default-src '\''none'\''; img-src '\''self'\''; style-src '\''unsafe-inline'\''"'; \
    echo '</Directory>'; \
} > /etc/apache2/conf-available/no-php-in-uploads.conf \
    && a2enconf no-php-in-uploads

WORKDIR /var/www/html
COPY . /var/www/html/

RUN mkdir -p /var/www/html/gambar/bukti /var/www/html/gambar/bukti_pembayaran /var/www/html/gambar/lapangan /var/www/html/gambar/user /var/www/html/gambar/sistem \
    && chown -R www-data:www-data /var/www/html/gambar \
    && chmod -R 755 /var/www/html/gambar
