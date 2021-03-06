# phpKontrol
Control your devices using a simple and access controlled web based interface and lightweight MQTT implementation.\
Based on Python, PHP, MySQL and a few bugs.

## Server side (Broker)

You'll need a MQTT Broker, public one will do too.\
Check [this link](https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-the-mosquitto-mqtt-messaging-broker-on-debian-8) for a tutorial.\
Keep it simple! A single user-password combination can be used for everything (clients/web interface).

## Client side (OrangePi / RaspberryPi / etc.)

The clients will run the script (mqtt-launcher) as a service with restart-on-fail.\
Requirements : Python 2.x (3.x not tested), paho-mqtt  

- copy the files from /client/ folder to your device at /opt/phpKontrol
- Install the required [paho-mqtt](https://pypi.python.org/pypi/paho-mqtt/1.3.1) using ```$pip install paho-mqtt```
- Create a new file 'launcher.conf' using 'launcher.conf.example' as the template or read [here](https://github.com/jpmens/mqtt-launcher) for more info on commands
- Move the 'phpKontrol.service' file to /lib/systemd/system and enable/start the service using:\
```$systemctl enable phpKontrol.service && systemctl daemon-reload && systemctl start phpKontrol.service```  
  
Note : To avoid executing the same command sent by the web interface on multiple clients use an unique id (#) on the payload\
```'client_#_ON'	:   [ 'bash', '/path/to/script.sh' ],```

## Web interface
![screenshot](https://github.com/yo6nam/phpKontrol/blob/master/snap.png?raw=true)\
The same server where MQTT Broker has been installed can be used or any other webserver.\
Requirements : PHP 5.x - 7.x, MySQL (or equivalent e.g. MariaDB)  
- Copy the files from /server/ folder to your webserver's root or subdirectory
- Create a new database, import phpKontrol.sql and update the 'dbcon.php' with the details
- Edit the 'control.php' file to match your needs
- Log in using u: demo and p: demo
- Read the info.txt for further details.
  
Note : The payload will be sent to the broker as 'clientid_command' so make sure to edit the 'launcher.conf' on each client, accordingly.

## Credits

* [mqtt-launcher](https://github.com/jpmens/mqtt-launcher)
* [phpMQTTClient](https://github.com/karpy47/PhpMqttClient)
* [chosen](https://github.com/harvesthq/chosen/)
