#!/bin/sh

echo "loading minimal modules"

/bin/echo "[modules]" > /etc/asterisk/modules.conf
/bin/echo "autoload=no" >> /etc/asterisk/modules.conf
echo "" >> /etc/asterisk/modules.conf
/bin/echo "load => pbx_realtime.so" >> /etc/asterisk/modules.conf
/bin/echo "load => res_musiconhold.so" >> /etc/asterisk/modules.conf
/bin/echo "load => res_timing_dahdi.so" >> /etc/asterisk/modules.conf
/bin/echo "load => res_rtp_asterisk.so" >> /etc/asterisk/modules.conf
/bin/echo "load => res_config_mysql.so" >> /etc/asterisk/modules.conf
/bin/echo "load => res_agi.so" >> /etc/asterisk/modules.conf
/bin/echo "load => res_speech.so" >> /etc/asterisk/modules.conf
/bin/echo "load => res_http_websocket.so" >> /etc/asterisk/modules.conf
/bin/echo "load => chan_sip.so" >> /etc/asterisk/modules.conf
/bin/echo "load => app_authenticate.so" >> /etc/asterisk/modules.conf
/bin/echo "load => app_db.so" >> /etc/asterisk/modules.conf
/bin/echo "load => func_channel.so" >> /etc/asterisk/modules.conf
/bin/echo "load => func_callerid.so" >> /etc/asterisk/modules.conf
/bin/echo "load => pbx_config.so" >> /etc/asterisk/modules.conf
/bin/echo "load => app_dial.so" >> /etc/asterisk/modules.conf
/bin/echo "load => func_timeout.so" >> /etc/asterisk/modules.conf
/bin/echo "load => func_extstate.so" >> /etc/asterisk/modules.conf
/bin/echo "load => func_realtime.so" >> /etc/asterisk/modules.conf

echo "commenting out rtautoclear and rtupdate"

sed -i 's/rtupdate=yes/;rtupdate=yes/g' /etc/asterisk/sip.conf
sed -i 's/rtautoclear=yes/;rtautoclear=yes/g' /etc/asterisk/sip.conf

service asterisk restart

echo "Done."
