#!/bin/bash

# Add epel repo
if [ ! -e /etc/yum.repos.d/epel.repo ]; then
    echo "Installing EPEL repo..."
    sudo rpm -Uvh http://dl.fedoraproject.org/pub/epel/6/x86_64/epel-release-6-8.noarch.rpm
fi

# Install Apache and PHP (and any needed extensions).
echo "Installing Apache and PHP packages..."
sudo yum install -y git httpd php php-pdo

# Make sure the timezone is set in php.ini.
echo "Updating timezone in php.ini..."
sudo sed -i".bak" "s/^\;date\.timezone.*$/date\.timezone = \"America\\/New_York\" /g" /etc/php.ini

# Make sure Apache is configured to start automatically and is running.
echo "Ensuring httpd is running and enabled at startup..."
sudo chkconfig httpd on
sudo service httpd start

# Add hosts file entries
if grep -q "oauth.local" /etc/hosts; then
    echo "hosts file already updated"
else
    echo "Updating hosts file..."
    sudo echo "127.0.0.1 oauth.local" >> /etc/hosts
    sudo echo "192.168.55.10 saml.local" >> /etc/hosts
fi

# Retrieve the composer dependencies.
echo "Updating composer dependencies..."
cd /var/www/php-oauth/
if [ ! -e composer.phar ]; then
    sudo curl -sS https://getcomposer.org/installer | php
else
    sudo php composer.phar self-update
fi
sudo php composer.phar update --dev

# Run php-oauth config script if config doesnt exist yet
if [ ! -f /var/www/php-oauth/config/oauth.ini ]
then
    sudo /var/www/php-oauth/docs/configure.sh
fi

if [ ! -d /var/www/php-oauth/data ]
then
    sudo mkdir -p /var/www/php-oauth/data
    sudo mkdir -p /var/www/php-oauth/data/logs
    sudo chmod -R 777 /var/www/php-oauth/data
fi

# Initialize php-oauth database if doesnt exist yet
if [ ! -f /var/www/php-oauth/data/oauth2.sqlite ]
then
    sudo php /var/www/php-oauth/docs/initOAuthDatabase.php
    sudo php /var/www/php-oauth/docs/registerClients.php /var/www/php-oauth/docs/myregistration.json
fi

# Copy the conf file to where Apache will find it.
sudo cp /vagrant/vhost-oauth.local.conf /etc/httpd/conf.d/

# Adjust the mode settings on that conf file.
sudo chmod 644 /etc/httpd/conf.d/vhost-oauth.local.conf

# Restart Apache.
sudo service httpd restart