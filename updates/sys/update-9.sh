#!/bin/sh

echo "create the asterisk manager config file"

ami_pass=$(openssl rand -base64 16)

echo "[telecube]" > /etc/asterisk/manager.d/telecube-pbx.conf
echo "secret = $ami_pass" >> /etc/asterisk/manager.d/telecube-pbx.conf
echo "deny=0.0.0.0/0.0.0.0" >> /etc/asterisk/manager.d/telecube-pbx.conf
echo "permit=127.0.0.1/255.255.255.0" >> /etc/asterisk/manager.d/telecube-pbx.conf
echo "read = system,call,log,verbose,command,agent,user,originate" >> /etc/asterisk/manager.d/telecube-pbx.conf
echo "write = system,call,log,verbose,command,agent,user,originate" >> /etc/asterisk/manager.d/telecube-pbx.conf

echo "set the ami_pass file in /opt"

echo "$ami_pass" > /opt/ami_pass

echo "restart astrisk"

service asterisk restart

echo "Done"
