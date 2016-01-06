#!/bin/sh

echo "adding write permissions to the sip trunking includes files"

chmod 0666 /etc/asterisk/sip-register.conf
chmod 0666 /etc/asterisk/sip-trunks.conf

chown asterisk:asterisk /etc/asterisk/sip-register.conf
chown asterisk:asterisk /etc/asterisk/sip-trunks.conf

echo "Done."

