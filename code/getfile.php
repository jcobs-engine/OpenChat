<?php

foreach($_POST AS $post_table => $content)
{
$post_table=str_replace('"', '#00100010#', $post_table);
$post_table=str_replace("'", '#00100111#', $post_table);

$content=str_replace('"', '#00100010#', $content);
$content=str_replace("'", '#00100111#', $content);

$_POST["$post_table"]=$content;
}

foreach($_GET AS $get_table => $content)
{
$get_table=str_replace('"', '#00100010#', $get_table);
$get_table=str_replace("'", '#00100111#', $get_table);

$content=str_replace('"', '#00100010#', $content);
$content=str_replace("'", '#00100111#', $content);

$_GET["$get_table"]=$content;
}

$passwd=shell_exec('cat /usr/share/openchat-project/encryption_passwd.txt | tr -d " \t\n\r" ');

echo "
<html>
<head>
<title>Download</title>
<style type='text/css'>
@keyframes blend{
0%{
box-shadow:0px 0px 0px white, 0px 0px 5px white inset;
}
100%{
box-shadow:0px 0px 2px 4px white, 0px 0px 5px white inset;
}
}

@keyframes count{
0%{
width:100%;
background-color:white;
}
2%{
background-color:#26ff00;
}
30%{
background-color:#fff200;
}
70%{
background-color:#ff7300;
}
95%{
background-color:#ff0000;
}
100%{
background-color:#ff0000;
width:0px;
}
}

@keyframes inp{
0%{
opacity:0;
}
100%{
opacity:1;
}
}

@keyframes blendd{
0%{
opacity:0;
left:calc(50% - 150px);
}

100%{
left:calc(50% - 100px);
opacity:1;
}
}

@keyframes goleft{
0%{
width:255px;
}
100%{
width:0px;
}
}

@keyframes goright{
0%{
width:250px;
left:calc( 50% - 0px);
}
100%{
width:0px;
left:calc( 50% + 250px );
}
}

@keyframes zoom{
0%{
left:calc( 50% - 250px );
width:500px;
height:20px;
top: calc( 50% - 10px );
}
100%{
top:-5px;
height:calc( 100% + 10px );
left:-5px;
width:calc( 100% + 10px );
}
}

</style>
</head>
<body style='font-size:25px; background-color:black' onload=\"setTimeout( function(){ window.close(); }, 60000); setTimeout(function(){ dbutton.style.zIndex='1'; }, 2100);\">";

$host = "localhost";
$benutzer =  shell_exec('cat /usr/share/openchat-project/mysql_username.txt | tr -d " \t\n\r" ');
$passwort = shell_exec('cat /usr/share/openchat-project/mysql_password.txt | tr -d " \t\n\r" ');
$bindung=mysqli_connect($host, $benutzer, $passwort ) or die ("Verbindungsaufbau zur Daten-Zentrale nicht m&ouml;glich!");
$db=shell_exec('cat /usr/share/openchat-project/mysql_database.txt | tr -d " \t\n\r" ');

function mdq( $bindung, $query )
{
  mysqli_select_db( $bindung, shell_exec('cat /usr/share/openchat-project/mysql_database.txt | tr -d " \t\n\r" ') );
  return( mysqli_query( $bindung, $query ) );
}

function dekrypti($string, $key)
{
    $cipher = 'AES-128-CBC';
    $key = pack('H*', md5($key));
    $iv_ascii = substr($string, 0, 24);
    $iv = base64_decode($iv_ascii);
    $string = substr($string, 24);
    $dekrypt = openssl_decrypt(
        base64_decode($string),
        $cipher,
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );
    return $dekrypt;
}


$accid=$_GET['accid'];
$chatid=$_GET['chatid'];
$fileid=$_GET['fileid'];
$personal_key=$_GET['personal_key'];
$etepass='';

if( $accid != md5($passwd.$personal_key)){
    $accid='';
    $personal_key='';
}

$chatenc=md5($passwd.dekrypti($_COOKIE[$chatid], $passwd));

if($chatid == 0){

$sql="select ownname from file where md5( concat( '$passwd', id ) )='$fileid';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$filevon=$row[0];
}

$sql="select id from user where id='$accid' and fname='$filevon';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$verify=1;
$personal_key_capture=$personal_key;
$pass=$row[0];
}

}
else
{

  $sql="select fname from user where id='$accid';";
  $ask=mdq($bindung, $sql);
  while( $row=mysqli_fetch_row( $ask ) ){
    $name=$row[0];
  }

$sql="select ownname from file where md5( concat( '$passwd', id ) )='$fileid';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$filevon=$row[0];
}


$sql="select id from user where fname='$filevon';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$filevonid=$row[0];
}

if( $name != "" ){
$sql="select enc from chat where id=$chatid and rights LIKE '%|$name|%' and (enc='NONE' or enc='$chatenc');";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$verify=1;
$pass=$filevonid;
if($row[0] == $chatenc){
  $etepass=dekrypti($_COOKIE[$chatid], $passwd);
}
}
}
}

if($verify == 1){
$fileid=shell_exec("ls ../user_files/$fileid.*.gpg");
$filewith=$fileid;

$fileid=substr($fileid, 0, -5);

$os=time();


$fileid_c=str_replace("../user_files/", $os.'_', $fileid);
$fileid_c="../user_files/".$fileid_c;

shell_exec("gpg --batch --passphrase $pass$personal_key_capture$etepass --decrypt --output $fileid_c $fileid.gpg &");
shell_exec('( sleep 60 && rm -f '.$fileid_c.' ) > /dev/null 2> /dev/null &');


echo "<div id='proccess' style='animation:blend 0.4s, zoom 1s;animation-fill-mode:forwards;animation-delay:0s, 1s;position:fixed; top:calc( 50% - 20px ); left:calc( 50% - 250px ); height:20px;box-shadow:0px 0px 0px white, 0px 0px 5px white inset; width:500px; border-radius:3px'></div>";
echo "<div id='proccess1' style='animation:goleft 0.6s;animation-fill-mode:forwards; animation-delay:0.4s;position:fixed; top:calc( 50% - 20px ); left:calc( 50% - 250px ); height:20px; background-color:white;width:255px; border-radius:3px'></div>";
echo "<div id='proccess2' style='animation:goright 0.6s;animation-fill-mode:forwards; animation-delay:0.4s;position:fixed; top:calc( 50% - 20px ); left:calc( 50% - 0px ); height:20px; background-color:white;width:250px; border-radius:3px'></div>

<div id='countdown' style='animation:inp 1s;animation-fill-mode:forwards;animation-delay:2s;opacity:0;position:fixed; bottom:10%; left:10px; height:20px;box-shadow:0px 0px 1px white, 0px 0px 5px white inset; width:calc(100% - 20px); border-radius:5px'>
<div id='count' style='animation:inp 1s, count 56s linear;animation-fill-mode:forwards;animation-delay:2s, 3.5s;opacity:0; height:20px;background-color:white; width:100%; border-radius:5px'>
</div>

<a href='$fileid_c' id='dbutton' download style='z-index:-1;animation:blendd 1s; animation-delay:2s;animation-fill-mode:forwards;opacity:0; text-decoration:none; color:white; font-weight:bold; position:fixed; left:calc(50% - 100px); width:200px; text-align:center; top:calc(50% - 35px);font-size:40px;text-shadow:0px 0px 2px white'>Download</a>

<div style='position:fixed; top:0px; left:0px; width:100%; height:100%; '></div>
";

}


echo "</form></body></html>";

?>
