#!/bin/bash

DOLLAR='$'

cat > Settings.php << EOF
<?php

/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines http://www.simplemachines.org
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0
 */

########## Maintenance ##########
# Note: If ${DOLLAR}maintenance is set to 2, the forum will be unusable!  Change it to 0 to fix it.
${DOLLAR}maintenance = $MAINTENANCE;
${DOLLAR}mtitle = 'Maintenance Mode';		# Title for the Maintenance Mode message.
${DOLLAR}mmessage = '$MAINTENANCE_MESSAGE';		# Description of why the forum is in maintenance mode.

########## Forum Info ##########
${DOLLAR}mbname = 'VATUSA Forums';		# The name of your forum.
${DOLLAR}language = 'english';		# The default language file set for the forum.
${DOLLAR}boardurl = '$URL';		# URL to your forum's folder. (without the trailing /!)
${DOLLAR}webmaster_email = 'vatusa12@vatusa.net';		# Email address to send emails from. (like noreply@yourdomain.com.)
${DOLLAR}cookiename = '$COOKIE';		# Name of the cookie to set for authentication.

########## Database Info ##########
${DOLLAR}db_type = 'mysql';
${DOLLAR}db_server = '$DB_HOST';
${DOLLAR}db_name = '$DB_DATABASE';
${DOLLAR}db_user = '$DB_USERNAME';
${DOLLAR}db_passwd = '$DB_PASSWORD';
${DOLLAR}db_port = '$DB_PORT';
${DOLLAR}ssi_db_user = '';
${DOLLAR}ssi_db_passwd = '';
${DOLLAR}db_prefix = 'smf_';
${DOLLAR}db_persist = 0;
${DOLLAR}db_error_send = 0;

########## Directories/Files ##########
# Note: These directories do not have to be changed unless you move things.
${DOLLAR}boarddir = '/www';		# The absolute path to the forum's folder. (not just '.'!)
${DOLLAR}sourcedir = '/www/Sources';		# Path to the Sources directory.
${DOLLAR}cachedir = '/www/cache';		# Path to the cache directory.

########## Error-Catching ##########
# Note: You shouldn't touch these settings.
${DOLLAR}db_last_error = 0;

# Make sure the paths are correct... at least try to fix them.
if (!file_exists(${DOLLAR}boarddir) && file_exists(dirname(__FILE__) . '/agreement.txt'))
	${DOLLAR}boarddir = dirname(__FILE__);
if (!file_exists(${DOLLAR}sourcedir) && file_exists(${DOLLAR}boarddir . '/Sources'))
	${DOLLAR}sourcedir = ${DOLLAR}boarddir . '/Sources';
if (!file_exists(${DOLLAR}cachedir) && file_exists(${DOLLAR}boarddir . '/cache'))
	${DOLLAR}cachedir = ${DOLLAR}boarddir . '/cache';

${DOLLAR}image_proxy_secret = '';
${DOLLAR}image_proxy_maxsize = '';
${DOLLAR}image_proxy_enabled = 0;
${DOLLAR}auth_secret = '${FORUM_AUTH_SECRET}';

EOF

echo $FORUM_KEY > forum.key
