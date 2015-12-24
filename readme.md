# Subiz_sms - Open source Web based transfer sms to mail

Subiz_sms is open source web-based SMS (Short Message Service) transfer service, it use gammu-smsd (part of gammu family - http://wammu.eu/gammu/ ) as SMS gateway engine to deliver and retrieve messages from your phone/modem and user Mailgun(Tranditional Email Api Service - https://www.mailgun.com/ ) to transfer sms.

My idea is base on Kalkun ( https://github.com/back2arie/Kalkun ) solution.

### Requirement

I run my application on Raspberry Pi (ram 434MB, OS Rapbian)

You need to install and configure this first:
```
apache2
php5.x.x
PHP-CLI
MySQL 5.x.x
php-curl
```
(You can follow thread https://www.raspberrypi.org/learning/lamp-web-server-with-wordpress/worksheet/ to install LAMP)
### !Important
gammu-smsd, make sure it is already running and configured.
### Installation

Clone my project or Extract this to web root folder(in my case /var/www/html). 

Create database named 'db_sms'. (using mysql console 'CREATE DATABSE db_sms' ).

Edit database config (application/config/database.php) Change database value username and password is depend on your mysql configuration.

Edit base_url default config (application/config/config.php) change $config['base_url'] = 'http://localhost/subiz_sms/'; instead of your application ip-add .

Import database scheme (it is included on subiz_sms source '/subiz_sms/database/db_sms.sql'): using mysql console:( this example is run on my case) 

```ex: mysql db_sms - u username -p < /var/www/html/subiz_sms/database/db_sms.sql```

(replace 'username' with your user access to mysql)

Config deamon(gammu-smsd): Edit you gammu-smsd config file to set path on gammu-smsd configuration at runonreceive directive, config gammu-smsd service store sms in mysql database(db_sms) e.g:
```
[smsd]
runonreceive = /var/www/html/subiz_sms/scripts/daemon.sh
service = SQL
driver = native_mysql
database = db_sms
user = username #Your mysql username 
password = password # Your mysql password
pc = localhost #Server mysql ip
```
in finally the file content maybe like this
```
[gammu]
device = /dev/ttyUSB1
connection = at
[smsd]
PIN=9999
runonreceive = /home/hoangdv/www/Kalkun/scripts/daemon.sh
logfile = /var/log/gammu.log
commtimeout = 10
sendtimeout = 20
deliveryreport = log
phoneid = mdsms
transmitformat = auto
driver = native_mysql
database = db_sms
user = subiz
password = subiz
pc = localhost
```
Set correct path on daemon.sh (make sure that the this file is executable )
```
#!/bin/sh
#Configure this (use absolute path)
PHP=/usr/bin/php5 # php cli path  -  check if  is correct if gammu-smsd return log 'Error status 126' or executable permission of .sh file.
SUBIZ=/var/www/html/subiz_sms/scripts/subiz.php # change this your case.
#Execute
$PHP $SUBIZ
```

Change URI path in subiz.php, default is (http://localhost/subiz_sms)

Open up your browser and go to http://you-ip/subiz_sms Default account : username = subiz, password = 147258 (you can change it);

Bonus: I run application on Raspi, have about 100sms/day the db is extended everyday to strim it I have created a crontab on Raspi to remove old sms
```
30 2 15 * * /usr/bin/wget -q -O ~/Documents/bksms$(date +%F_%T).txt http://localhost/subiz_sms/index.php/Home/deleteoldsms/15
```

### User manual

* Information Devices: (gammu-smsd can run with multi config(devices))

![alt text](https://files.slack.com/files-pri/T02870PT5-F0H6102AD/sr1.png "Screen 1")



- Log (status: Red - can not transfer, Green - transfer ok, White - no transfer
![alt text](https://files.slack.com/files-pri/T02870PT5-F0H5XGUUD/sr2.png "Screen 2")



- Config rule to filter sms

![alt text](https://files.slack.com/files-pri/T02870PT5-F0H5WAK52/sr3.png "Screen 3")
