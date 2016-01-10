#!/bin/sh

echo "adding chown perm to sudoers"

echo "www-data ALL=NOPASSWD: /bin/chown" >> /etc/sudoers.d/telecube-sudo

echo "Done"
