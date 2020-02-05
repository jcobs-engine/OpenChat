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

$abziehen=strtotime("-24 hours");
$abziehenyear=strtotime("-1 months");

$time=gmdate( 'U', $abziehen );
$timeyear=gmdate( 'U', $abziehenyear );

$timenow=gmdate( 'U' );

echo "allowed time: $timeyear - $time - $timenow<p><fieldset><legend>Deleted Tells</legend>";

$sql="select tell.id from tell, chat where ( tell.aktu<'$time' and ( tell.chatid=chat.id and chat.enc='NONE' ) or tell.file=1 ) or tell.aktu<'$timeyear';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
    if( $row[0] != $old ){
        echo '# '.$row[0].'<br>';
    }
    
    $old=$row[0];
    $sqla="delete from tell where id=$row[0];";
    $aska=mdq($bindung, $sqla);
}

echo "</fieldset>";

echo "<fieldset><legend>Deleted Files</legend>";

$sql="select id from file where aktu<'$time' and type=0;";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
    $id=md5( "$row[0]" );
    $ergebnis=shell_exec( 'rm ../user_files/'.$id.'.*' );
    echo '# '.$id.'<br>';
}

echo '</fieldset><p>';


$sql="delete from file where aktu<'$time' and type=0;";
$ask=mdq($bindung, $sql);

$du=0;
$sql="select id from user where fname='anonymous';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
    $du++;
}
echo "Deleted $du inactive User.";


$sql="delete from user where fname='anonymous';";
$ask=mdq($bindung, $sql);





?>
