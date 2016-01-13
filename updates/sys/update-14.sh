#!/bin/sh

echo "denying web access to some script only directories"

sed -i 's/server_name _;/server_name _;\n\n\tlocation ~ \/(auto|classes|includes) {\n\t\tdeny all;\n\t}\n\n\tlocation ~ \/init.php {\n\t\tdeny all;\n\t}\n/g' /etc/nginx/sites-available/default

service nginx restart

echo "Done."
