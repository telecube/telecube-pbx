#!/bin/sh

echo "adding extra realtime options to sip.conf"

# check the config
STR="rtupdate=yes"
if grep -Fxq "$STR" /etc/asterisk/sip.conf
then
    # code if found
    echo "rtupdate=yes already configured"
else
    # code if not found
    sed -i 's/rtcachefriends=yes/rtcachefriends=yes\nrtupdate=yes\nrtautoclear=yes/g' /etc/asterisk/sip.conf
fi

echo "reloading sip"
/usr/sbin/asterisk -rx "sip reload"

echo "Done"

