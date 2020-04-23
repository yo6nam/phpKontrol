# phpKontrol
Control your devices using a simple web based interface and lightweight MQTT implementation

# Server side

You'll need a MQTT Broker. Check this link :
https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-the-mosquitto-mqtt-messaging-broker-on-debian-8

# Client side

Copy the files from /client/ to your device (OrangePi / RaspberryPi) at /opt/phpKontrol
- Install the required pho-mqtt using $pip install paho-mqtt
- Create a new file 'launcher.conf' using 'launcher.conf.example' as the template
- Move the 'phpKontrol.service' file to /lib/systemd/system and enable/start the service using:
$systemctl enable phpKontrol && systemctl daemon-reload && systemctl start phpKontrol

# Web interface

You can use the same server where the MQTT Broker is installed.
You will need PHP 5.x - 7.x, MySQL
- Copy the files on your webserver's root or subdirectory
- Create a new database, import phpKontrol.sql and update the 'dbcon.php' with the details
- Edit the 'control.php' file to match your needs