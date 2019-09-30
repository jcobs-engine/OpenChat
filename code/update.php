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

$host = "localhost";
$benutzer =  shell_exec('cat /usr/share/openchat-project/mysql_username.txt | tr -d " \t\n\r" ');
$passwort = substr( shell_exec('cat /usr/share/openchat-project/mysql_password.txt | tr -d " \t\n\r" '), 0, 13);
$bindung=mysqli_connect($host, $benutzer, $passwort ) or die ("Verbindungsaufbau zur Daten-Zentrale nicht m&ouml;glich!");
$db=shell_exec('cat /usr/share/openchat-project/mysql_database.txt | tr -d " \t\n\r" ');

function mdq( $bindung, $query )
{
  mysqli_select_db( $bindung, shell_exec('cat /usr/share/openchat-project/mysql_database.txt | tr -d " \t\n\r" ') );
  return( mysqli_query( $bindung, $query ) );
}
if($_GET['id'] == "hauptsache10weg"){

$abziehen=strtotime("-24 hours");

$time=gmdate( 'U', $abziehen );
$timenow=gmdate( 'U' );

echo "allowed time: $time - $timenow<p><fieldset><legend>Deleted Tells</legend>";

$sql="select id from tell where aktu<'$time';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
echo '# '.$row[0].'<br>';
}

echo "</fieldset>";

$sql="delete from tell where aktu<'$time';";
$ask=mdq($bindung, $sql);

echo "<fieldset><legend>Deleted Files</legend>";

$sql="select id from file where aktu<'$time' and type=0;";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$id=md5( "$row[0]" );
$ergebnis=shell_exec( 'rm ../user_files/'.$id.'.*' );
echo '# '.$id.'<br>';
}

$sql="delete from file where aktu<'$time' and type=0;";
$ask=mdq($bindung, $sql);

echo '</fieldset>';

}


?>
