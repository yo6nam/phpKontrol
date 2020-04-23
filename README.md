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

