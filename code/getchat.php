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

function dekrypti( $string, $key )
{
  $cipher = 'AES-128-CBC';
  $key = pack( 'H*', md5( $key ) );
  $iv_ascii = substr( $string, 0, 24 );
  $iv=base64_decode( $iv_ascii );
  $string=substr( $string, 24 );
  $dekrypt  = openssl_decrypt( base64_decode( $string ), $cipher, $key, OPENSSL_RAW_DATA, $iv);
  return $dekrypt;
}

$passwd =
    shell_exec('cat /usr/share/openchat-project/encryption_passwd.txt | tr -d " \t\n\r" ');


$sd=time();

echo "
<html>
<head>
<style type='text/css'>
@keyframes wis{
0%{
width:0px;
}
100%{
width:calc(100% - 40px);
}
}

@keyframes blend{
0%{
opacity:0;
}
100%{
opacity:1;
}
}
</style>
</head>
<body style='font-size:25px'>
<form method='GET' action=''><div id='table'>";

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

$accid=$_GET['accid'];
$chatid=$_GET['chatid'];
$roomkey=$_GET['roomkey'];



$sql="select fname, timezone, sel from user where id='$accid';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$name=$row[0];
$timezone=$row[1];
$sel=$row[2];
}

$sql="select id, enc from chat where rights LIKE '%|$name|%';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
    if($row[0] == $chatid and ($row[1] == 'NONE' or md5($passwd.$roomkey) == $row[1])){
        $verify=1;
        
        if(md5($passwd.$roomkey) == $row[1]){
            $passwd=$passwd.$roomkey;
        }

    }
}



if($verify == 1){

$sql="select sd from seen where user=$sel and type=$chatid;";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$sql="update seen set sd='$sd' where user=$sel and type=$chatid;";
$aska=mdq($bindung, $sql);
$sdstop=1;
}
if($sdstop != 1)
{
$sql="insert into seen set sd='$sd', user=$sel, type=$chatid;";
$ask=mdq($bindung, $sql);
}

$showsys=0;
while($showsys <= 1){

if($showsys == 0)
$sysstyle="id='tableone' style='display:block'";
else
$sysstyle="id='tabletwo' style='display:none'";

echo "<div $sysstyle>";

echo "<table style='font-size:20px;margin-bottom:20px;'>";
$sql="select text, von, hour, time from tell where chatid=$chatid;";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){

$text=$row[0];
$von=$row[1];
$hour=$row[2];
$time=$row[3];

$text=dekrypti( $text, md5($passwd.$chatid) );


$hour=explode(' ', $hour);

$hourt=explode(':', $hour[0]);

$hourt[0]=$hourt[0]+$timezone;



if($hourt[0] > 11){
if($hour[1] == 'AM' and $hour[1] != 12)
$hour[1]='PM';
else
$hour[1]='AM';

$hourt[0]=$hourt[0]-12;
}

if($hourt[0] < 1){
if($hour[1] == 'AM' and $hour[1] != 0)
$hour[1]='PM';
else
$hour[1]='AM';

$hourt[0]=$hourt[0]+12;
}

$hour[0]=$hourt[0].':'.$hourt[1];

$hour="$hour[0] $hour[1]";

$text=str_replace("%20", " ", $text);
$text=str_replace("%3F", "?", $text);
$text=str_replace("%21", "!", $text);
$text=str_replace("%E4", "ä", $text);
$text=str_replace("%C4", "Ä", $text);
$text=str_replace("%FC", "ü", $text);
$text=str_replace("%DC", "Ü", $text);
$text=str_replace("%F6", "ö", $text);
$text=str_replace("%D6", "Ö", $text);
$text=str_replace("%DF", "ß", $text);
$text=str_replace("%26", "&", $text);
$text=str_replace("%24", "$", $text);
$text=str_replace("%22", '"', $text);
$text=str_replace("%27", "'", $text);
$text=str_replace("%23", "#", $text);
$text=str_replace("%28", "(", $text);
$text=str_replace("%29", ")", $text);
$text=str_replace("%3A", ":", $text);
$text=str_replace("%3B", ";", $text);
$text=str_replace("%3D", "=", $text);
$text=str_replace("%25", "%", $text);
$text=str_replace("%2C", ",", $text);
$text=str_replace("%5E", "^", $text);

$text=str_replace("#00100111#", "'", $text);
$text=str_replace("#00100010#", '"', $text);


$text=str_replace("#255", "<span style='background-color:#c40303; color:white; border-radius:3px;padding:3px;padding-left:8px; padding-right:8px;'>", $text);
$text=str_replace("#256", "</span>", $text);

$text=str_replace("#245", "<span style='background-color:#0f8409; color:white; border-radius:3px;padding:3px;padding-left:8px; padding-right:8px;'>", $text);
$text=str_replace("#246", "</span>", $text);

$text=str_replace("#235", "<span style='background-color:#004dc9; color:white; border-radius:3px;padding:3px;padding-left:8px; padding-right:8px;'>", $text);
$text=str_replace("#236", "</span>", $text);

$text=str_replace("#225", "<span style='background-color:#002868; color:white; border-radius:3px;padding:3px;padding-left:8px; padding-right:8px;'>", $text);
$text=str_replace("#226", "</span>", $text);

$text=str_replace("#215", "<span style='background-color:#8700a5; color:white; border-radius:3px;padding:3px;padding-left:8px; padding-right:8px;'>", $text);
$text=str_replace("#216", "</span>", $text);

if(strpos($text,'<a href=')!==false)
{
$mes=explode("#456", $text);

$text=str_replace("I have a present for you!", $mes[0], $mes[1]);
}

if($von == $name){
$color="#7bf469";
$textcolor="#c7ffbf";
}
else{
$color="#d1d1d1";
$textcolor="#d1d1d1";
}

if($von == "SYSTEM"){
$color="#7d85e0";
$textcolor="#8794ff; font-weight:bold";
}



if($hour != $vh){	
echo "</table><fieldset style='border:0px; border-top:2px solid rgba(100%, 100%, 100%, 0.7);font-size:18px'><legend align='center' style='color:white;padding-bottom:1px;cursor:default;width:100px;text-align:center'>$hour</legend></fieldset>";
echo "<table style='font-size:20px;margin-bottom:20px;'>";
echo "<tr class='lines' style='display:block;opacity:0;padding-bottom:5px;padding-top:3px'><td colspan='4' style='animation:wis 1s;animation-fill-mode:forwards;width:0px;position:absolute;'><div style='width:100%; border-bottom:1px solid rgba(100%, 100%, 100%, 0.2)'></div></td></tr>";
}
$time=explode(' ', $time);

$th=explode(':', $time[0]);

$th[0]=$th[0]+$timezone;



if($th[0] > 11){
if($time[1] == 'am' and $th[0]!=12)
$time[1]='pm';
else
$time[1]='am';

$th[0]=$th[0]-12;
}

if($th[0] < 1){
if($time[1] == 'am' and $th[0]!=0)
$time[1]='pm';
else
$time[1]='am';

$th[0]=$th[0]+12;
}

$time[0]=$th[0].':'.$th[1];

echo "<tr><td style='color:$color; text-align:right; '><b>$von</b></td><td style='color:$color; padding-right:30px; padding-bottom:5px;'><b>:</b></td><td style='color:$textcolor'> $text</td><td class='time' style='text-align:right;right:20px;position:absolute; display:none;font-size:20px;padding-top:3px; background-color:#070707; box-shadow:0px 0px 3px 3px #070707; opacity:0;animation-fill-mode:forwards;animation:blend 1s;animation-delay:0.7s;animation-fill-mode:forwards;'><b>$time[0]</b> $time[1]</td></tr>";
echo "<tr class='lines' style='display:block; opacity:0;padding-bottom:5px;padding-top:3px'><td colspan='4' style='animation:wis 1s;animation-fill-mode:forwards;width:0px;position:absolute;'><div style='width:100%; border-bottom:1px solid rgba(100%, 100%, 100%, 0.2)'></div></td></tr>";


$vh=$hour;

}

echo "</table></div>";

$showsys++;
}

$setoff=time()+8;

$sql="update user set setoff='$setoff', room='$chatid' where id='$accid';";
$ask=mdq($bindung, $sql);

}
else
echo "<img src='../programm_files/lock.png' style='position:relative; width:100px; left:calc(50% - 50px );padding-top:calc(11%)'>";

echo "</div></form>
</body>
</html>
";

?>
