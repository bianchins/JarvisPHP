# JarvisPHP
### Your personal butler written in Php

JarvisPhp is a REST API system written in Php that permit a direct interact with user through commands.
The commands may be spoken by voice and recognized by a Speech-to-text system, for example an Android application (via Android STT)
and sent to JarvisPHP API.
The scenario is simple: the owner (user) speak through a bluetooth headset connected with the smartphone, pressing the button
on the headset itself. The result of the voice recognization is sent to JarvisPHP, that try to understand the command and do something.
JarvisPHP was tested on a Raspberry PI, but it can be used over every *nix system.

##Documentations
Please refer to wiki: https://github.com/bianchins/JarvisPHP/wiki/

*Remember to execute `composer install --no-dev`: this will install composer dependences*

##System's architecture
How can JarvisPhp do something? How can it interact with the enviroment?
JarvisPhp uses plugins for execute the understood command. For example, if you ask "Who are you?" it activate a "Info plugin"
that answer "My name is...".
A plugin (you can write your own!) can do anything: for example, interact with `GPIO` of a Raspberry Pi (http://www.raspberrypi.org),
or play some music, query public weather api, read mails, connect to facebook and read notifications, and so on.
![JarvisPHP's architecture](https://cloud.githubusercontent.com/assets/4076011/7567407/248adedc-f7fd-11e4-9152-ce285c909697.png)

##System's requirements
*For core system*
- Php 5.3 or newer
- a web server (lighttpd recommended)
- Composer (https://getcomposer.org/)

*For Text To Speech*
It depends on which TTS you choose in configuration files. For example:
- for Espeak tts plugin, `espeak` and `aplay` required
- for Say tts plugin, it works only on OSX
- for Google TTS plugin, `aplay` required
Of course you can edit a TTS plugins and replace aplay with your audio player. 
Or you can write your own TTS, just place it in the `Speakers` folder
