#!/bin/sh

echo "resetting the sip.conf file, adding nat and register attempts in a new include"

echo "" > /etc/asterisk/sip-conf.conf
echo "; extra config options" >> /etc/asterisk/sip-conf.conf
echo "nat=force_rport,comedia" >> /etc/asterisk/sip-conf.conf
echo "registerattempts=0" >> /etc/asterisk/sip-conf.conf

echo "adding the sip-conf.conf include to sip.conf"

# check the config
STR="#include \"sip-conf.conf\""
if grep -Fxq "$STR" /etc/asterisk/sip.conf
then
    # code if found
    echo "sip-conf.conf include already configured"
else
    # code if not found
    sed -i 's/#include "sip-register.conf"/#include "sip-conf.conf"\n\n#include "sip-register.conf"/g' /etc/asterisk/sip.conf
fi

echo "reloading sip"
/usr/sbin/asterisk -rx "sip reload"

echo "Done."
