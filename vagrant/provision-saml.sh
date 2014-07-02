#!/bin/bash

# Add epel repo
if [ ! -e /etc/yum.repos.d/epel.repo ]; then
    echo "Installing EPEL repo..."
    sudo rpm -Uvh http://dl.fedoraproject.org/pub/epel/6/x86_64/epel-release-6-8.noarch.rpm
fi

# Install Apache and PHP (and any needed extensions).
echo "Installing Apache and PHP packages..."
sudo yum install -y git svn httpd php php-mcrypt php-xml php-mbstring php-ldap

# Install ldap servers and clients
echo "Installing openldap packages..."
sudo yum install -y openldap-servers openldap-clients

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
    sudo echo "192.168.55.11 oauth.local" >> /etc/hosts
    sudo echo "127.0.0.1 saml.local" >> /etc/hosts
fi

# Update ldap.conf
echo "Updating ldap.conf..."
sudo sed -i "s/^#BASE.*$//" /etc/openldap/ldap.conf
sudo sed -i "s/^#URI.*$/URI ldap:\/\/localhost:389/" /etc/openldap/ldap.conf
sudo sed -i "s/TLS_REQCERT never//g" /etc/openldap/ldap.conf
sudo echo "TLS_REQCERT never" >> /etc/openldap/ldap.conf

# Retrieve the composer dependencies.
echo "Updating composer dependencies..."
cd /var/www/simplesamlphp/
if [ ! -e composer.phar ]; then
    sudo curl -sS https://getcomposer.org/installer | php
else
    sudo php composer.phar self-update
fi
sudo php composer.phar update --dev

# Copy the conf file to where Apache will find it.
sudo cp /vagrant/vhost-saml.local.conf /etc/httpd/conf.d/

# Adjust the mode settings on that conf file.
sudo chmod 644 /etc/httpd/conf.d/vhost-saml.local.conf

# Restart Apache.
sudo service httpd restart