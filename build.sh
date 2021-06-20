#!/bin/sh
mkdir attachments
chown -R application:application attachments

mkdir cache
chown -R application:application cache

cp /var/run/secrets/Settings.php /www/Settings.php
cp /var/run/secrets/smfapi_settings.txt /www/smfapi_settings.txt

chown application:application Settings.php
chown application:application smfapi_settings.txt

/usr/bin/supervisord --nodaemon --configuration /etc/supervisord.conf