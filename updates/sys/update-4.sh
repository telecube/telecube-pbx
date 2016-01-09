#!/bin/sh

echo "adding ntpdate to cron"

MIN=$(shuf -i 1-59 -n 1)
crontab -l | { cat; echo "$MIN	*	*	*	*	/usr/sbin/ntpdate -b -s ntp0.cs.mu.OZ.AU >/dev/null 2>&1"; } | crontab -

echo "done"