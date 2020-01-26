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

$id=$_GET['id'];
$file=md5($passwd.$id);



$sql="select size from file where id=$id;";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$meta=explode(',', $row[0]);
$final_size=$meta[0];
$filetype=$meta[1];
}

$gpg_size=shell_exec( 'stat --printf="%s" ../user_files/'.$file.'.'.$filetype.'.gpg');

if($gpg_size == ''){
$size=shell_exec( 'stat --printf="%s" ../user_files/'.$file.'.'.$filetype);
$title='Uploading:';
}
else
{
$size=$gpg_size;
$title='Encryption:';
}
$prozent=round(($size/$final_size)*100);

if( $size == '' ){
$prozent=0;
}

if( $prozent >= 47 ){
$prozent+=7;
}

if( $prozent > 100 ){
$prozent=100;
}

if( $prozent < 50 ){
$col='black';
}
else{
$col='white';
}

if( $prozent == 0 or $prozent == 100){
$title='';
$prozentp='Prepairing...';
}
else
{
$prozentp=$prozent.'%';
}

echo "<html><body style='overflow:hidden; background-color:grey;'><div style='box-shadow:0px 0px 5px 5px black;text-align:left;position:absolute;height:100%; width:$prozent%; top:0px; left:0px;background-color:black; color:white;'></div><div style='position:absolute; width:100%; text-shadow:0px 0px 5px black;color:$col; font-size:33px; font-weight:bold;text-align:center'>$prozentp</div><div style='position:absolute; width:calc(100% - 20px); left:20px; color:white; text-shadow:0px 0px 5px black; font-size:30px; font-weight:bold;text-align:left'>$title</div></body></html>";

?>
