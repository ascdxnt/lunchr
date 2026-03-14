FROM php:8.5-apache

# Install mysqli extension and msmtp for mail() support
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli \
    && apt-get update && apt-get install -y msmtp && rm -rf /var/lib/apt/lists/*

# Configure msmtp as sendmail replacement
RUN echo "account default\nhost mailpit\nport 1025\nfrom comedor@scms.local\nauto_from off" > /etc/msmtprc \
    && chmod 644 /etc/msmtprc

# Point PHP's sendmail_path to msmtp
RUN echo 'sendmail_path = "/usr/bin/msmtp -t"' > /usr/local/etc/php/conf.d/mail.ini

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set document root to project directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

WORKDIR /var/www/html

EXPOSE 80
