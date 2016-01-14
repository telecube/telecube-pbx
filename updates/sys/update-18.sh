#!/bin/sh

echo "loading blf config"

echo "; BLF Config" > /etc/asterisk/blf.conf

/bin/chmod 0666 /etc/asterisk/blf.conf
/bin/chown asterisk:asterisk /etc/asterisk/blf.conf

# reload the dialplan
/usr/sbin/asterisk -rx "dialplan reload"
