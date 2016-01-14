#!/bin/sh

echo "denying web access to some script only directories"

sed -i 's/location ~ \/(auto|classes|includes) {/location ~ \/(auto|classes|daemons|includes) {\n/g' /etc/nginx/sites-available/default

service nginx restart

echo "Done."
