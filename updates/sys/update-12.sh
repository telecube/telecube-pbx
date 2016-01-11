#!/bin/sh

echo "loading codec modules"

/bin/echo "load => codec_a_mu.so" >> /etc/asterisk/modules.conf
/bin/echo "load => codec_adpcm.so" >> /etc/asterisk/modules.conf
/bin/echo "load => codec_alaw.so" >> /etc/asterisk/modules.conf
/bin/echo "load => codec_dahdi.so" >> /etc/asterisk/modules.conf
/bin/echo "load => codec_g722.so" >> /etc/asterisk/modules.conf
/bin/echo "load => codec_g726.so" >> /etc/asterisk/modules.conf
/bin/echo "load => codec_gsm.so" >> /etc/asterisk/modules.conf
/bin/echo "load => codec_lpc10.so" >> /etc/asterisk/modules.conf
/bin/echo "load => codec_resample.so" >> /etc/asterisk/modules.conf
/bin/echo "load => codec_speex.so" >> /etc/asterisk/modules.conf
/bin/echo "load => codec_ulaw.so" >> /etc/asterisk/modules.conf

service asterisk restart

echo "Done."
