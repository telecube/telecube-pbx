#!/bin/sh


echo "updating the extensions.conf config"

echo ";" > /etc/asterisk/extensions.conf
echo "; Please don't modify this file directly, it is managed by the PBX and your changes may be overwritten" >> /etc/asterisk/extensions.conf
echo ";" >> /etc/asterisk/extensions.conf
echo "" >> /etc/asterisk/extensions.conf

echo "[globals]" >> /etc/asterisk/extensions.conf
echo "" >> /etc/asterisk/extensions.conf
echo "" >> /etc/asterisk/extensions.conf

echo "[public]" >> /etc/asterisk/extensions.conf
echo "exten => _X.,1,NoOp(Unauthorised Call)" >> /etc/asterisk/extensions.conf
echo "exten => _X.,n,Agi(/var/lib/asterisk/agi-bin/unauthorised-call.php)" >> /etc/asterisk/extensions.conf
echo "exten => _X.,n,Hangup()" >> /etc/asterisk/extensions.conf
echo "" >> /etc/asterisk/extensions.conf

echo "[voip-extensions]" >> /etc/asterisk/extensions.conf
echo "exten => _X.,1,NoOp(Call out)" >> /etc/asterisk/extensions.conf
echo "exten => _X.,n,Agi(/var/lib/asterisk/agi-bin/voip-out.php)" >> /etc/asterisk/extensions.conf
echo "exten => _X.,n,Hangup()" >> /etc/asterisk/extensions.conf
echo "" >> /etc/asterisk/extensions.conf

echo "exten => _+X.,1,NoOp(Call out)" >> /etc/asterisk/extensions.conf
echo "exten => _+X.,n,Agi(/var/lib/asterisk/agi-bin/voip-out.php)" >> /etc/asterisk/extensions.conf
echo "exten => _+X.,n,Hangup()" >> /etc/asterisk/extensions.conf
echo "" >> /etc/asterisk/extensions.conf

echo "[voip-trunks]" >> /etc/asterisk/extensions.conf
echo "exten => _X.,1,NoOp(Inbound call)" >> /etc/asterisk/extensions.conf
echo "exten => _X.,n,Agi(/var/lib/asterisk/agi-bin/voip-in.php)" >> /etc/asterisk/extensions.conf
echo "exten => _X.,n,Hangup()" >> /etc/asterisk/extensions.conf
echo "" >> /etc/asterisk/extensions.conf
echo "" >> /etc/asterisk/extensions.conf

echo ";;/* Include the BLF Listings */;;" >> /etc/asterisk/extensions.conf
echo "#include \"blf.conf\"" >> /etc/asterisk/extensions.conf
echo "" >> /etc/asterisk/extensions.conf


echo "reloading the dialplan"
/usr/sbin/asterisk -rx "dialplan reload"

echo "Done"

