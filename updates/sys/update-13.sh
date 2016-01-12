#!/bin/sh

echo "adding pkill to sudoers"

echo "www-data ALL=NOPASSWD: /usr/bin/pkill -f ami-scripts/event-handler.php" >> /etc/sudoers.d/telecube-sudo

echo "killing ast-manager-event-handler"

/usr/bin/pkill -f ast-manager-event-handler.php
/usr/bin/pkill -f ami-scripts/event-handler.php

echo "Done."

