RUN apt-get update && apt-get install -y git
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite
RUN apt-get install -y wget
RUN wget https://dl.google.com/cloudsql/cloud_sql_proxy.linux.amd64 -O cloud_sql_proxy
RUN chmod +x cloud_sql_proxy
COPY . . /var/www/html/
RUN apt-get install -y yarn
RUN yarn
CMD ["sh", "-c", "./cloud_sql_proxy -instances=bss-sandbox-env-1:asia-southeast2:bss-dev-mysql-serve$
EXPOSE 80/tcp
EXPOSE 443/tcp





RUN apt-get update && apt-get install -y git
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite
COPY .  /var/www/html/
EXPOSE 80/tcp
EXPOSE 443/tcp