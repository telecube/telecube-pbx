#!/bin/sh

echo "adding the sip trunking includes files"

echo "" >> /etc/asterisk/sip.conf
echo "" >> /etc/asterisk/sip.conf
echo '#include "sip-register.conf"' >> /etc/asterisk/sip.conf
echo "" >> /etc/asterisk/sip.conf
echo '#include "sip-trunks.conf"' >> /etc/asterisk/sip.conf

echo "; SIP Registered Trunks" > /etc/asterisk/sip-register.conf
echo "; SIP IP Authenticated Trunks" > /etc/asterisk/sip-trunks.conf

echo "Done."

