#!/bin/bash

# This script is not meant to be manually run, but is executed by
# the minecraft_deploy.php script once the server has been deployed.

if ! [ -n "$BASH_VERSION" ];then
    echo "this is not bash, calling self with bash....";
    SCRIPT=$(readlink -f "$0")
    /bin/bash $SCRIPT
    exit;
fi

apt-get update
apt-get dist-upgrade -y
sudo apt-get install openjdk-7-jdk screen -y

# lets be neat and stick everything in a minecraft directory
mkdir minecraft
cd minecraft

wget -O minecraft_server.1.7.4.jar https://s3.amazonaws.com/Minecraft.Download/versions/1.7.4/minecraft_server.1.7.4.jar

# Find out how much RAM I have and use all but 256 of it for minecraft
ram=$(free -m | awk '/^Mem:/{print $2}')
ram=`expr $ram - 256`

# Start minecraft in a screen session so the user can log in later and run commands
cmd="/usr/bin/java -Xmx`echo $ram`M -Xms`echo $ram`M -jar /root/minecraft/minecraft_server.1.7.4.jar nogui"
screen -d -m -S mc
screen -S mc -p 0 -X exec $cmd