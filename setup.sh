#!/bin/bash
function wait() {
    read -sn1 dummy
}

clear;
if test $( whoami ) == 'root'; then
echo -e "\033[1;34m1.\033[0m Please install a \033[1;37mWebserver\033[0m (eg. Apache / XAMPP)"
wait
echo -e "\033[1;34m2.\033[0m Please install \033[1;37mMySQL\033[0m (MariaDB)"
wait
echo -e "\033[1;34m3.\033[0m Please setup your OpenChat-MySQL (Further information on the table structure can be found in README.md):"
echo ""
echo -e "   Enter in a terminal:\n\n\033[1;41;37mmysql -u root\033[0m";
wait
echo ""
echo -e "\033[1;41;37mCREATE USER 'YourUsername'@'localhost' IDENTIFIED BY 'YourPassword';\033[0m";
wait
echo ""
echo -e "\033[1;41;37mCREATE DATABASE YourDatabaseName;\033[0m"
wait
echo ""
echo -e "\033[1;41;37mGRANT ALL PRIVILEGES ON YourDatabaseName . * TO 'MyUsername'@'localhost';\033[0m"
wait
echo ""
echo -e "\033[1;34m4.\033[0m How did you called YourUsername?"
read username
mkdir /usr/share/openchat-project/
 echo $username > /usr/share/openchat-project/mysql_username.txt
echo ""
echo -e "\033[1;34m4.\033[0m How did you called YourPassword?"
read password
 echo $password > /usr/share/openchat-project/mysql_password.txt
echo ""
echo -e "\033[1;34m4.\033[0m How did you called YourDatabaseName?"
read databse
 echo $database > /usr/share/openchat-project/mysql_database.txt
echo ""
echo -e "\033[1;34m4.\033[0m Please enter any Developer-Passphrase (It is only used for coding the OpenChat-Project on this Device): "
read passwd
 echo $passwd > /usr/share/openchat-project/encryption_passwd.txt
echo ""
echo -e '\033[1;34m5.\033[0m Finally, set up an internal address for your web server (eg. localhost). It must lead to the absolute OpenChat path, where the folders "code" and "program_files" etc. are inside.'
wait
echo ""
echo -e "\033[1;34m6.\033[0m Please enter this (possibly ip) Adress:"
read adress
 echo $adress > /usr/share/openchat-project/path.txt
 chmod 777 /usr/share/openchat-project/*

echo ""
echo ""
echo -e "\033[1;31mNow you can code OpenChat!\nHave a lot of fun!\033[0m"
echo ""
else
    echo "Please login as root!"
fi
