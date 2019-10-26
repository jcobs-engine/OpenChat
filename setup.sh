#!/bin/bash
function wait() {
    read -sn1 dummy
}

clear;
if test $( whoami ) == 'root'; then
echo -e "\033[1;34m1.\033[0m Please install a \033[1;37mwebserver\033[0m (e. g. Apache / XAMPP)"
wait
echo -e "\033[1;34m2.\033[0m Please install \033[1;37mMySQL\033[0m (MariaDB)"
wait
echo -e "\033[1;34m3.\033[0m Please setup your OpenChat-MySQL (You can find the dump in the GitHub-repository!)."
echo ""
echo -e "\033[1;34m4.\033[0m What is your username for the sql-database?"
read username
mkdir /usr/share/openchat-project/
 echo $username > /usr/share/openchat-project/mysql_username.txt
echo ""
echo -e "\033[1;34m4.\033[0m What is your password to the sql-database?"
read password
 echo $password > /usr/share/openchat-project/mysql_password.txt
echo ""
echo -e "\033[1;34m4.\033[0m What is the name of your sql-database?"
read database
 echo $database > /usr/share/openchat-project/mysql_database.txt
echo ""
echo -e "\033[1;34m4.\033[0m Please enter any developer-passphrase (It is only used for coding the OpenChat-Project on this Device): "
read passwd
 echo $passwd > /usr/share/openchat-project/encryption_passwd.txt
echo ""
echo -e '\033[1;34m5.\033[0m Finally, set up an internal address for your web server (e. g. localhost). It must lead to the absolute OpenChat path, where the folders "code" and "program_files" etc. are inside.'
wait
echo ""
echo -e "\033[1;34m6.\033[0m Please enter this (possibly ip) Adress:"
read adress
echo $adress > /usr/share/openchat-project/path.txt
echo ""
echo -e "\033[1;34m6.\033[0m Please enter the username of the service Apache [wwwrun]"
read apachestring
if test "$apachestring" == ""; then apache="wwwrun"; else apache=$apachestring; fi
chmod 700 /usr/share/openchat-project
chmod 600 /usr/share/openchat-project/*
chown $apache.root /usr/share/openchat-project
chown $apache.root /usr/share/openchat-project/*
echo ""
echo ""
echo -e "\033[1;31mNow you can code OpenChat!\nHave a lot of fun!\033[0m"
echo ""
else
    echo "Please login as root!"
fi
