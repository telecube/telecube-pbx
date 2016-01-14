#!/bin/sh

HOST_IP=$(ifconfig | awk -F':' '/inet addr/&&!/127.0.0.1/{split($2,_," ");print _[1]}')
arr=$(echo $HOST_IP | tr " " "\n")
for x in $arr
do
   ASTERISK_IP=$x
   break
done

# check the extra config files exist
if [ -f "/etc/asterisk/sip-network.conf" ]
then
	echo "Found /etc/asterisk/sip-network.conf"
else
	echo "; variable network stuff" > /etc/asterisk/sip-network.conf
	echo "udpbindaddr=$ASTERISK_IP" >> /etc/asterisk/sip-network.conf
	echo "tcpenable=no" >> /etc/asterisk/sip-network.conf
	echo "tcpbindaddr=0.0.0.0" >> /etc/asterisk/sip-network.conf
	echo "transport=udp" >> /etc/asterisk/sip-network.conf
	/bin/chmod 0666 /etc/asterisk/sip-network.conf
	/bin/chown asterisk:asterisk /etc/asterisk/sip-network.conf
fi


echo "[general]" > /etc/asterisk/sip.conf
echo "context=public" >> /etc/asterisk/sip.conf
echo "allowoverlap=no" >> /etc/asterisk/sip.conf

echo "" >> /etc/asterisk/sip.conf
echo "#include \"sip-network.conf\"" >> /etc/asterisk/sip.conf
echo "" >> /etc/asterisk/sip.conf

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

