#!/bin/bash
# Cron do ruby
clear

ruby /home/narutogame/www/cron.rb

echo 3 > /proc/sys/vm/drop_caches

#Fim
exit
