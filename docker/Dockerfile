FROM ubuntu:20.04

# Install php dependecies
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -yq --no-install-recommends \
    apt-utils \
    zip unzip \
    curl \
    # Install git
    git \
    # Install apache
    apache2 \
    # Install php 7.4
    libapache2-mod-php7.4 \
    php7.4-cli \
    php7.4-json \
    php7.4-curl \
    php7.4-mysql \
    php7.4-sqlite3 \
    php7.4-zip \
    php7.4-gd \
    php7.4-ldap \
    php7.4-mbstring \
    php7.4-xml \
    # Install tools
    nano \
    mysql-client \
    ca-certificates \
    php-dom \
    sqlite3 \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 80 8888

# Copy configuration files
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/ports.conf /etc/apache2/ports.conf

# Copy phpunit shortcuts
COPY docker/install-f-shortcut.sh /usr/local/bin/f
RUN chmod +x /usr/local/bin/f

# Copy apache to run in the foreground script
COPY docker/httpd-foreground.sh /usr/local/bin/httpd-foreground
RUN chmod +x /usr/local/bin/httpd-foreground

# Copy create databases script
COPY docker/create-databases.sh /usr/local/bin/create-databases
RUN chmod +x /usr/local/bin/create-databases

# Copy start script
COPY docker/start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start

# Install node and NPM
COPY docker/install-node.sh /usr/local/bin/install-node
RUN chmod +x /usr/local/bin/install-node
RUN /usr/local/bin/install-node

RUN rm /var/www/html/index.html

# Copy phpunit shortcuts
COPY docker/install-r-shortcut.sh /usr/local/bin/r
RUN chmod +x /usr/local/bin/r

WORKDIR /var/www/html/src/in-stock-series

CMD ["/usr/local/bin/start"]