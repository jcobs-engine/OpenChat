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

function krypti( $str, $key )
{
  $cipher = 'AES-128-CBC';
  $key = pack( 'H*', md5( $key ) );
  $iv = openssl_random_pseudo_bytes( 16 );
  $krypt = base64_encode( openssl_encrypt( $str, $cipher , $key, OPENSSL_RAW_DATA, $iv) );
  $dekrypt  = openssl_decrypt( base64_decode( $krypt ), $cipher, $key, OPENSSL_RAW_DATA, $iv);
  $ascii_iv=base64_encode( $iv );
  return $ascii_iv.$krypt;
}

$passwd =
    shell_exec('cat ../passwords/passwd.txt');


echo "
<html>
<head>
</head>
<body style='font-size:25px'>
<form method='GET' action=''>";

$host = "localhost";
$benutzer =  "levi";
$passwort = substr( shell_exec('cat ../passwords/sql.txt'), 0, 13);
$bindung=mysqli_connect($host, $benutzer, $passwort ) or die ("Verbindungsaufbau zur Daten-Zentrale nicht m&ouml;glich!");
$db="openchat";

function mdq( $bindung, $query )
{
  mysqli_select_db( $bindung, 'openchat' );
  return( mysqli_query( $bindung, $query ) );
}

$accid=$_GET['accid'];
$chatid=$_GET['chatid'];
$text=$_GET['text'];
$roomkey=$_GET['roomkey'];

$sql="select fname from user where id='$accid' or id='7cbff9f534bf023c49c773f3fdd33ba7';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$name=$row[0];
}
$sql="select id, enc from chat where ( rights LIKE '%|$name|%' or '$accid'='7cbff9f534bf023c49c773f3fdd33ba7') and id=$chatid;";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
if($row[0] == $chatid and ($row[1] == 'NONE' or md5($passwd.$roomkey) == $row[1])){
$verify=1;
    if(md5($passwd.$roomkey) == $row[1]){
        $passwd=$passwd.$roomkey;   
        
    }
}
$cid=md5($row[0]);
}

if($verify == 1 and $text != ""){


$h=gmdate( 'h' );
$h=$h.':00 '.gmdate( 'A' );

$time=gmdate( 'h' ).':'.gmdate( 'i' ).' '.gmdate( 'a' );
$aktu=gmdate( 'U' );

$text=krypti( "$text", md5($passwd.$chatid) );

$sd=time();

$sql="insert into tell set chatid=$chatid, von='$name', text='$text', hour='$h', time='$time', aktu='$aktu', sd=$sd;";
$ask=mdq($bindung, $sql);
}

echo "$name $chatid $text</form>
</body>
</html>
";

?>
