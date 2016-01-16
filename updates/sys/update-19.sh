#!/bin/sh

echo "loading app exec module"

/usr/sbin/asterisk -rx "module load app_exec.so"

echo "adding app exec module to load on start"

/bin/echo "load => app_exec.so" >> /etc/asterisk/modules.conf

echo "Done."
