#!/bin/sh
mkdir attachments
chown -R application:application attachments

mkdir cache
chown -R application:application cache

sh create_settings.sh

chown application:application Settings.php
chown application:application smfapi_settings.txt

/usr/bin/supervisord --nodaemon --configuration /etc/supervisord.conf