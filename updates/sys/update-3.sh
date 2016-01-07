#!/bin/sh

echo "adding write permissions to the sip trunking includes files"

chmod 0666 /etc/asterisk/sip-conf.conf

chown asterisk:asterisk /etc/asterisk/sip-conf.conf

echo "Done."

