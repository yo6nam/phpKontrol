# phpKontrol
Control your devices using a simple web based interface and lightweight MQTT implementation\
Based on Python, PHP, MySQL

## Server side

You'll need a MQTT Broker, public one will do too.\
Check [this link](https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-the-mosquitto-mqtt-messaging-broker-on-debian-8) for a tutorial.

## Client side

Copy the files from /client/ to your device (OrangePi / RaspberryPi) at /opt/phpKontrol
- Install the required [paho-mqtt](https://pypi.python.org/pypi/paho-mqtt/1.3.1) using ```$pip install paho-mqtt```
- Create a new file 'launcher.conf' using 'launcher.conf.example' as the template
- Move the 'phpKontrol.service' file to /lib/systemd/system and enable/start the service using:\
```$systemctl enable phpKontrol.service && systemctl daemon-reload && systemctl start phpKontrol.service```

## Web interface

The same server where MQTT Broker has been installed can be used or any other webserver.\
Requirements : PHP 5.x - 7.x, MySQL  
- Copy the files from /server/ to your webserver's root or subdirectory
- Create a new database, import phpKontrol.sql and update the 'dbcon.php' with the details
- Edit the 'control.php' file to match your needs
- Log in using u: demo and p: demo
- Read the info.txt for further details.\
\
Note : The payload will be sent to the broker as 'clientid_command' so make sure to edit the 'launcher.conf' on each client, accordingly.

## Credits

* [mqtt-launcher](https://github.com/jpmens/mqtt-launcher)
* [phpMQTTClient](https://github.com/karpy47/PhpMqttClient)
