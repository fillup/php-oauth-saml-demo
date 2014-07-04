#!/bin/bash

# Add epel repo
if [ ! -e /etc/yum.repos.d/epel.repo ]; then
    echo "Installing EPEL repo..."
    sudo rpm -Uvh http://dl.fedoraproject.org/pub/epel/6/x86_64/epel-release-6-8.noarch.rpm
fi

# Install Apache and PHP (and any needed extensions).
pkgs=( git httpd php php-pdo php-xml )
for i in "${pkgs[@]}"
do
    if rpm -qa | grep -q $i; then
        echo "$i already installed"
    else
        echo "Installing $i and dependencies..."
        sudo yum install -y $i
    fi
done

# Make sure the timezone is set in php.ini.
echo "Updating timezone in php.ini..."
sudo sed -i".bak" "s/^\;date\.timezone.*$/date\.timezone = \"America\\/New_York\" /g" /etc/php.ini

# Set ServerName for apache
if [ ! -e "/etc/httpd/conf.d/servername.conf" ]; then
    echo "Setting servername in for apache"
    sudo echo "ServerName oauth.local" >> /etc/httpd/conf.d/servername.conf
fi

# Make sure Apache is configured to start automatically and is running.
echo "Ensuring httpd is running and enabled at startup..."
sudo chkconfig httpd on

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
    echo "Installing composer.phar"
    sudo curl -sS https://getcomposer.org/installer | php
else
    if test `find "composer.phar" -mmin +1440`; then
        echo "Composer.phar is older than a day, performing self-update"
        sudo php composer.phar self-update
    else
        echo "Composer.phar is less than a day old, update not needed"
    fi
fi
if test `find "composer.lock" -mmin +1440`; then
    echo "Composer dependencies are older than a day, performing update"
    sudo php composer.phar update --dev
else
    echo "Composer dependencies are less than a day old, update not needed"
fi

# Run php-oauth config script if config doesnt exist yet
if [ ! -f /var/www/php-oauth/config/oauth.ini ]
then
    echo "Running php-oauth configure script"
    sudo /var/www/php-oauth/docs/configure.sh
fi

if [ ! -d /var/www/php-oauth/data ]
then
    echo "Creating php-oauth/data directories"
    sudo mkdir -p /var/www/php-oauth/data
    sudo mkdir -p /var/www/php-oauth/data/logs
    sudo chmod -R 777 /var/www/php-oauth/data
fi

# Initialize php-oauth database if doesnt exist yet
if [ ! -f /var/www/php-oauth/data/oauth2.sqlite ]
then
    echo "Initializing php-oauth database"
    sudo php /var/www/php-oauth/docs/initOAuthDatabase.php
    sudo php /var/www/php-oauth/docs/registerClients.php /var/www/php-oauth/docs/myregistration.json
fi

# Create symlink for simplesaml
if [ ! -L /var/www/php-oauth/www/simplesaml ]
then
    echo "Creating symlink for simplesaml"
    sudo ln -s /var/www/simplesamlphp/www/ /var/www/php-oauth/www/simplesaml
fi

# Copy the conf file to where Apache will find it.
echo "Copying vhost configuration"
sudo cp /vagrant/vhost-oauth.local.conf /etc/httpd/conf.d/

# Adjust the mode settings on that conf file.
sudo chmod 644 /etc/httpd/conf.d/vhost-oauth.local.conf

# Restart Apache.
echo "Restarting apache"
sudo service httpd restart