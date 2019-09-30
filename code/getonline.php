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

$passwd=shell_exec('cat /passwords/passwd.txt');


$echo="
<html>
<head>
</head>
<body style='font-size:25px'>
<form method='GET' action=''>";

$jetztchat=0;

$host = "localhost";
$benutzer =  "levi";
$passwort = substr( shell_exec('cat /passwords/sql.txt'), 0, 13);
$bindung=mysqli_connect($host, $benutzer, $passwort ) or die ("Verbindungsaufbau zur Daten-Zentrale nicht m&ouml;glich!");
$db="openchat";

function mdq( $bindung, $query )
{
  mysqli_select_db( $bindung, 'openchat' );
  return( mysqli_query( $bindung, $query ) );
}

$accid=$_GET['accid'];
$time=time();

$sql="select fname from user where id='$accid';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$name=$row[0];
$verify=1;
}

if($verify == 1){

$sql="select room from user where setoff-$time>0 and id='$accid';";
$aska=mdq($bindung, $sql);
while( $rowa=mysqli_fetch_row( $aska ) ){
$onpoeple=array();
$chatid=$rowa[0];

$sql="select title from chat where id='$chatid';";
$askas=mdq($bindung, $sql);
while( $rowas=mysqli_fetch_row( $askas ) ){
$chattitle=$rowas[0];
}

$jetztchat=$chatid;

$online=0;

$sql="select id, setoff, fname from user where (room='$chatid' and setoff-$time>0) and id!='$accid' ORDER by fname;";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$online++;

$onpoeple[]=$row[2];


}

if($online <= 0)
$col="#cf2a11";
else
$col="#05a124";

$onpoepleend='<b>'.$onpoeple[0].'</b>';

$i=0;
foreach($onpoeple AS $leut){

if($i <= 5){
if($i != 0)
$onpoepleend.=', <b>'.$leut.'</b>';
}

$i++;
}

if($i > 6)
$onpoepleend.=', <b>...</b>';

$echo.="<span style='padding-left:6px;text-shadow:0px 0px 1px white; cursor:default;'>$chattitle</span> <b style='color:$col; float:right; padding-right:10px;'>".$online."</b><div style='margin-top:10px;font-size:20px;padding-left:10px; padding-right:10px;'>$onpoepleend</div><hr style='border::?: solid #d1d1d1; border-radius:2px;'>";
$seta=1;
}

$sql="select id, title, enc from chat where rights LIKE '%|$name|%' and id!=$jetztchat ORDER by id desc;";
$aska=mdq($bindung, $sql);
while( $rowa=mysqli_fetch_row( $aska ) ){
$chatid=$rowa[0];
$chattitle=$rowa[1];
$enc=$rowa[2];
    
    if($enc != md5($passwd.$_COOKIE[$chatid]) and $enc != "NONE"){
        $n='n';    
    }
    else
        $n='';
    
$online=0;
$sql="select id, setoff, fname from user where (room='$chatid' and setoff-$time>0) and id!='$accid' ORDER by fname;";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$online++;
}

if($online != 0){

if($online <= 0)
$col="#cf2a11";
else
$col="#05a124";

$echo.="<span style='padding-left:6px;cursor:pointer;' onclick=\"cz$n$chatid.value='1'; site.value=2;document.save.submit();\">$chattitle</span> <b style='color:$col; cursor:default;float:right;padding-right:10px;'>".$online."</b><hr style='border:1px solid #d1d1d1'>
<input type='hidden' name='cz$n$chatid' id='cz$n$chatid' value='none'>
";
$setb=1;
}
}

$echo.="

</body>
</html>
";

if($seta == 1 or $setb == 1)
$echo="<hr style='border:1px solid #d1d1d1; margin-bottom:6px;'>$echo";

if($seta == 1 and $setb != 1)
$echo=str_replace(':?:', '1px', $echo);
else
$echo=str_replace(':?:', '2px', $echo);

echo $echo;

}

    
?>
