#!/bin/sh
DEPLOY_DIR="$(dirname "$0")"
if [ ! -f "$DEPLOY_DIR/variables" ];then
  DEPLOY_DIR="/SOEN390_ATeam/docs/deployment"
fi

# Include configuration variables
. "$DEPLOY_DIR/variables"
. "$DEPLOY_DIR/customize"

echo "*** Disabling SELinux"
setenforce 0
sed -e 's/SELINUX=enforcing/SELINUX=disabled/g' /etc/sysconfig/selinux

echo "*** Installing core package set"
yum install -y nano wget telnet zip unzip etckeeper git mysql-server httpd php php-gd php-mysql php-pdo php-xml php-cli
service mysqld start
service httpd start

echo "*** Configuring firewall"
OUTPUT_FILE=/etc/sysconfig/iptables
. "$DEPLOY_DIR/iptables.sample"
service iptables restart

echo "*** Configuring PHP"
OUTPUT_FILE=/etc/php.ini
. "$DEPLOY_DIR/php.ini.sample"

echo "*** Configuring Apache"
OUTPUT_FILE=/etc/httpd/conf/httpd.conf
. "$DEPLOY_DIR/httpd.conf.sample"

echo "*** Deploying source code"
rm -rf /var/www/html
cp -a /SOEN390_ATeam /var/www/html
chown root:root /var/www/html
chmod 755 /var/www/html

echo "*** Configuring application"
OUTPUT_FILE=/var/www/html/application/config/database.php
. "$DEPLOY_DIR/database.php.sample"

mkdir /var/www/html/application/config/test
OUTPUT_FILE=/var/www/html/application/config/test/database.php
. "$DEPLOY_DIR/database_test.php.sample"

mkdir /var/www/html/uploads
chown apache:apache /var/www/html/uploads

echo "*** Unpacking ffmpeg"
mkdir /var/www/storage
pushd /var/www/storage
ARCH="32bit"
if [ "$(uname -m)" == "x86_64" ];then
  ARCH="64bit"
fi
tar xfz "/SOEN390_ATeam/docs/deployment/ffmpeg-2.2-gnulinux-${ARCH}.tar.gz"
popd

echo "*** Configuring database"
echo -e "CREATE DATABASE ${DB_NAME}" | mysql -u root
echo -e "CREATE DATABASE ${DB_NAME}_test" | mysql -u root
echo -e "GRANT ALL ON ${DB_NAME}.* TO '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}'" | mysql -u root
echo -e "GRANT ALL ON ${DB_NAME}_test.* TO '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}'" | mysql -u root
cat "$DEPLOY_DIR/install.sql" | mysql -u root "${DB_NAME}"
cat "$DEPLOY_DIR/install.sql" | mysql -u root "${DB_NAME}_test"
echo -e "INSERT INTO ${DB_NAME}.admins (login, password) VALUES ('${ADMIN_EMAIL}', '${ADMIN_PASSWORD}')" | mysql -u root
mysqladmin -u root password "${DB_ROOT_PASSWORD}"

echo "*** Restarting services..."
service mysqld restart
service httpd restart

echo "*** Done!"
