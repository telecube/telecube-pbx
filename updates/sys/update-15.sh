#!/bin/sh

# check the extra config files exist
if [ -f "/etc/asterisk/sip-conf.conf" ]
then
	echo "Found /etc/asterisk/sip-conf.conf"
else
	echo "; extra config options" > /etc/asterisk/sip-conf.conf
	echo "nat=force_rport,comedia" >> /etc/asterisk/sip-conf.conf
	echo "registerattempts=0" >> /etc/asterisk/sip-conf.conf
	/bin/chmod 0666 /etc/asterisk/sip-conf.conf
	/bin/chown asterisk:asterisk /etc/asterisk/sip-conf.conf
fi

if [ -f "/etc/asterisk/sip-register.conf" ]
then
	echo "Found /etc/asterisk/sip-register.conf"
else
	echo "; SIP Registered Trunks" > /etc/asterisk/sip-register.conf
	/bin/chmod 0666 /etc/asterisk/sip-register.conf
	/bin/chown asterisk:asterisk /etc/asterisk/sip-register.conf
fi

if [ -f "/etc/asterisk/sip-trunks.conf" ]
then
	echo "Found /etc/asterisk/sip-trunks.conf"
else
	echo "; SIP IP Authenticated Trunks" > /etc/asterisk/sip-trunks.conf
	/bin/chmod 0666 /etc/asterisk/sip-trunks.conf
	/bin/chown asterisk:asterisk /etc/asterisk/sip-trunks.conf
fi


HOST_IP=$(ifconfig | awk -F':' '/inet addr/&&!/127.0.0.1/{split($2,_," ");print _[1]}')
arr=$(echo $HOST_IP | tr " " "\n")
for x in $arr
do
   ASTERISK_IP=$x
   break
done

echo "[general]" > /etc/asterisk/sip.conf
echo "context=public" >> /etc/asterisk/sip.conf
echo "allowoverlap=no" >> /etc/asterisk/sip.conf
echo "udpbindaddr=$ASTERISK_IP" >> /etc/asterisk/sip.conf
echo "tcpenable=no" >> /etc/asterisk/sip.conf
echo "tcpbindaddr=0.0.0.0" >> /etc/asterisk/sip.conf
echo "transport=udp" >> /etc/asterisk/sip.conf
echo "realm=telecube.com.au" >> /etc/asterisk/sip.conf
echo "srvlookup=yes" >> /etc/asterisk/sip.conf
echo "maxexpiry=240" >> /etc/asterisk/sip.conf
echo "minexpiry=120" >> /etc/asterisk/sip.conf
echo "defaultexpiry=180" >> /etc/asterisk/sip.conf
echo "language=au" >> /etc/asterisk/sip.conf
echo "sendrpid = pai" >> /etc/asterisk/sip.conf
echo "useragent=Asterisk (Telecube) PBX" >> /etc/asterisk/sip.conf
echo "callcounter = yes" >> /etc/asterisk/sip.conf
echo "directmedia=no" >> /etc/asterisk/sip.conf
echo "sdpsession=Asterisk (Telecube) PBX" >> /etc/asterisk/sip.conf
echo "rtcachefriends=yes" >> /etc/asterisk/sip.conf
echo "rtsavesysname=yes" >> /etc/asterisk/sip.conf
echo "alwaysauthreject=yes" >> /etc/asterisk/sip.conf
echo "progressinband=yes" >> /etc/asterisk/sip.conf
echo "" >> /etc/asterisk/sip.conf
echo "" >> /etc/asterisk/sip.conf
echo '#include "sip-conf.conf"' >> /etc/asterisk/sip.conf
echo "" >> /etc/asterisk/sip.conf
echo '#include "sip-register.conf"' >> /etc/asterisk/sip.conf
echo "" >> /etc/asterisk/sip.conf
echo '#include "sip-trunks.conf"' >> /etc/asterisk/sip.conf
echo "" >> /etc/asterisk/sip.conf

/bin/chmod 0666 /etc/asterisk/sip.conf
/bin/chown asterisk:asterisk /etc/asterisk/sip.conf

# reload sip.conf
/usr/sbin/asterisk -rx "sip reload"

