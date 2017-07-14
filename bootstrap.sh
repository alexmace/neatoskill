#!/usr/bin/env bash

apt-get update
apt-get install -y php-pear php-dev php-xdebug php-cgi apache2 libapache2-mod-fcgid language-pack-en php-curl

# #yum install -y httpd php-pear php-devel mysql-server php-mysql pygpgme wget
phpenmod xdebug

# Configure fcgid correctly.
echo "AddHandler fcgid-script .php
FCGIWrapper '/usr/bin/php-cgi' .php

FcgidMaxRequestsPerProcess 200
FcgidMaxRequestLen 10240000
FcgidIOTimeout 1800
SetEnv ENVIRONMENT "development"

DirectoryIndex index.html index.php

<VirtualHost *:80>
        DocumentRoot /var/www/alexaskills/public
        <Directory /var/www/alexaskills>
                Options FollowSymLinks ExecCGI
                AllowOverride All
                Order allow,deny
                Allow from all
                # Apache 2.4
                <IfModule mod_authz_core.c>
                        Require all granted
                </IfModule>
        </Directory>
        RewriteEngine On
</VirtualHost>" > /etc/apache2/conf-available/slim.conf
a2enconf slim
a2enmod rewrite
service apache2 restart
chown -R www-data /var/www/alexaskills/log
chown -R www-data /var/www/alexaskills/cache
if ! [ -L /var/www/alexaskills ]; then
  ln -fs /vagrant /var/www/alexaskills
fi
