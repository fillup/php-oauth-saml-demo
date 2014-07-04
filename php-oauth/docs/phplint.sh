#!/bin/sh
for i in `find . | grep \.php$`
do
        php -l $i > /dev/null
done
