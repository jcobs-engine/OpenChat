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

$return.="
<html>
<head>
<title>OpenChat | Upload</title>";

$return.='<script>
function gibihm(){
var text = document.getElementById("filus").value;
var text = text.split( String.fromCharCode(92) );
text.forEach(setdataname);
document.getElementById("bysend").value = document.getElementById("dataname").value;
}

function setdataname(cw){
document.getElementById("dataname").value = cw;
}

</script>';
$return.="
</head>
<body style='font-size:25px; cursor:pointer;' onload='setTimeout(function(){ filus.click(); }, 1000 ); closa.click();'>
<form method='POST' name='save' enctype='multipart/form-data'>";

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

$online=1;
$sql="select id from user where id='0';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$online=0;
}


if($online == 1)
$pfad='www.openchat.xyz/';
else
$pfad='www.openchat.off/';


$accid=$_POST['accid'];
$chatid=$_POST['chatid'];
$filetype=$_POST['tipe'];
$personal_key=$_POST['personal_key'];



if($filetype != 1)
    $dis="disabled='disabled'";
else
{
    $personal_key_captureb=$personal_key;   
}
$sql="select fname from user where id='$accid';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$name=$row[0];
}



if($filetype == 1){
$verify=1;
}
else{
$sql="select id from chat where rights LIKE '%|$name|%';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
if($row[0] == $chatid){
$verify=1;
}
}
}



if($verify == 1){
$return.="<style type='text/css'>
@keyframes c{
0%{
opacity:1;
top:calc(50% - 50px);
left:calc(50% - 50px);
}
50%{
opacity:0;
top:calc(50% - 0px);
left:calc(50% - 100px);
}
100%{
opacity:1;
top:calc(50% - 50px);
left:calc(50% - 50px);
}
}

@keyframes anf{
0%{
opacity:0;
top:100%;
left:-100px;
}
20%{
opacity:1;
}
100%{
opacity:1;
top:calc(50% - 50px);
left:calc(50% - 50px);
}
}

@keyframes end{
0%{
opacity:1;
top:calc(50% - 50px);
left:calc(50% - 50px);
}
100%{
opacity:1;
top:-50px;
left:100%;
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

</style>

<input type='text' placeholder='Directory' maxlength='10' list='blabla' id='group' name='group' value='' style='display:none;position:fixed; box-shadow:0px 0px 5px black;width:200px;left:0px; top:0px;font-size:25px;color:black; background-color:white; padding:10px; border:0px;' autocomplete='off' $dis>

<input type='text' autocomplete='off' placeholder='Name' maxlength='20' id='bysend' name='bysend' value='' style='display:none;position:fixed; box-shadow:0px 0px
5px black;width:calc(100% - 200px); right:0px; top:0px;font-size:25px;color:black; background-color:white; padding:10px; border:0px;'>

<img src='../programm_files/send.png' style='animation:anf 1s;animation-fill-mode:forwards; opacity:1;width:100px;position:fixed;top:calc(50% - 50px); left:calc(50% - 50px)' id='send' onclick=\"anum.style.display='none'; bysend.style.display='none'; group.style.display='none';send.style.animation='end 1s'; send.style.animationFillMode='forwards'; setTimeout(function(){  loadscreen.style.display='block'; ende.value='1';  }, 1000 ); setTimeout(function(){ document.save.submit(); }, 3000);\">
<label for='filus' style='cursor:pointer;'><img src='../programm_files/send.png' style='animation:anf 1s;animation-fill-mode:forwards; opacity:1;width:100px;position:fixed;top:calc(50% - 50px); left:calc(50% - 50px)' id='sendus' ></label>
<input type='file' name='senda'
onchange=\"sendus.style.display='none';anum.style.display='none';bysend.style.display='block';group.style.display='block';send.style.animation='c 1s'; setTimeout(function(){ send.src='send_green.png'; }, 500); gibihm(); return true;\" style='display:none;' id='filus'>

<input onkeyup=\"sendus.style.display='none';bysend.style.display='block';group.style.display='block';send.style.animation='c 1s'; setTimeout(function(){ send.src='send_green.png'; }, 500); gibihm(); return true;\" type='text' autocomplete='off' id='anum' name='anum' value='' placeholder='SHARE-iD' style='animation:inp 1.5s; animation-delay:0.3s;animation-fill-mode:forwards;opacity:0;display:block;position:fixed; box-shadow:0px 0px 2px green;width:100%; text-align:center;right:0px; top:calc(50% + 100px);font-size:16px;color:black; background-color:white; padding:10px; border:0px;'>
<div style='display:none;position:absolute; width:100%; height:100%; left:0px; right:0px;cursor:wait;' id='loadscreen'>
<img src='load.gif' style='cursor:wait;width:200px; position:fixed; top:calc(50% - 100px); left:calc(50% - 100px);'>
</div>
";

//LIST      //

$praefix=array();

$sqla="select id, ownname, title from file where rmid='$accid' ORDER by title;";
$aska=mdq($bindung, $sqla);
while( $rowa=mysqli_fetch_row( $aska ) ){
$i=$rowa[0];

$fileid=md5($passwd.$rowa[0]);

$fileid=shell_exec("ls files/$fileid".'.*');
$fileowner=$rowa[1];
$fileidus=explode('.', $fileid);
$filetitle=$rowa[2];

$jetztpre="";

if($fileid == ""){
}
else{

if(strpos($filetitle,":")!==false){

$prae=explode(':', $filetitle);
$filetitle=$prae[1];

$praefix[]=$prae[0];
$jetztpre=$prae[0];
$anypre=1;
}



$ookay=1;

}
}


if($anypre == 1){

$return.="<datalist id='blabla'>";

foreach($praefix AS $pr){

if($pr != $vorpr)
$return.="<option value='$pr'>";

$vorpr=$pr;
}

$return.="</datalist>";

}

//[END]LIST//






$errorsay=0;
$anum=$_POST['anum'];
$bysend=$_POST['bysend'];

if($_POST['group'] == "")
$_POST['group']='ALL';

if($filetype != 1)
$_POST['group']="";

if($bysend == "" and $_POST['ende'] != "")
{
$errorsay=1;
$_FILES['senda']['error']='Please enter a file name.';
}

if(($_FILES['senda']['name'] != "" or $anum != "") and strpos($bysend,"'")===false and strpos($bysend,":")===false and strpos($_POST['group'],":")===false and $errorsay == 0){

if($_POST['group'] != ""){
$bysend=$_POST['group'].':'.$bysend;
}

$time=date( 'U' );



if($anum != ""){
$sql="select ownname, type, rmid from file where id='$anum' ;";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$ownname=$row[0];
$rmid=$row[1];
$chat=$row[2];
$filexis=shell_exec("ls files/".md5($passwd.$anum).".* 2>&1");
if(strpos($filexis,"No such")===false){
$weiter=1;
}

}

if($weiter == 1){

$sql="select id from user where fname='$ownname' ;";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$pass=$row[0];
}

if($rmid == 1){
$sql="select id from file where ownname='$name' and id='$anum' ;";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
    $verifyb=1;
    $personal_key_capture=$personal_key;
    }
}
else{
$sql="select id from chat where rights LIKE '%|$ownname|%' and rights LIKE '%|$name|%' and id='$chat';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$verifyb=1;
}
}
}

if($verifyb == 1)
$anum_id=md5($passwd.$anum);
else{
$pass="";

$errorsay=1;
$_FILES['senda']['error']='Invalid SHARE-iD';
}
}

if($filetype == "")
$filetype=0;

if($filetype == 1)
$chatid=$accid;



$sql="insert into file set aktu='$time', rmid='$chatid', type='$filetype', ownname='$name', title='$bysend';";
$ask=mdq($bindung, $sql);

$sql="select id from file where aktu='$time' and ownname='$name' and title='$bysend' and rmid='$chatid' and type='$filetype';";
$ask=mdq($bindung, $sql);
while( $row=mysqli_fetch_row( $ask ) ){
$id=$row[0];
}


$id=md5($passwd.$id);


if($verifyb != 1){
$dname=explode('.', $_FILES['senda']['name']);
}
else
{
$dname=shell_exec( "ls files/$anum_id.*" );
$dname=substr($dname, 0, -1);
$dname=str_replace('.gpg', '', $dname);
$dname=explode('.', $dname);
}

foreach($dname AS $dnama){
$filetype=$dnama;
}



if($verifyb == 1){
    
shell_exec( "cp files/".$anum_id.".* files/".$id.".".$filetype.".gpg");
shell_exec("gpg --batch --passphrase $pass$personal_key_capture --decrypt --output files/".$id.".".$filetype." files/".$anum_id.".".$filetype.".gpg");
shell_exec("rm -f files/$id.$filetype.gpg");
}
else{
move_uploaded_file($_FILES['senda']['tmp_name'], 'files/'.$id.'.'.$filetype );
}

shell_exec("gpg --batch --passphrase $accid$personal_key_captureb --symmetric files/$id.$filetype;");
shell_exec("rm files/$id.$filetype;");


if($_FILES['senda']['error'] == 0 or $verifyb == 1){

if($filetype != 1){
$text=urlencode($bysend).'%23456%3Ca%20href%3D%22getfile.php%3Faccid%3D'.$accid.'%26fileid%3D'.$id.'%26chatid%3D'.$chatid.'%22%20target%3D%22_blank%22%20style%3D%22text-decoration%3Anone%3B%20font-weight%3Abold%3B%20background-color%3A%23ab6c15%3B%20color%3Awhite%3B%20border-radius%3A3px%3Bpadding%3A3px%3Bpadding-left%3A8px%3B%20padding-right%3A8px%3B%22%3EI%20have%20a%20present%20for%20you!%3C%2Fa%3E';

$_buffer = implode('', file('http://'."$pfad".'insert.php?accid='.$accid.'&chatid='.$chatid.'&text='."$text" ));

}

}
else
$errorsay=1;

}
else{

if(strpos($bysend,"'")!==false){
$errorsay=1;
$_FILES['senda']['error']="Invalid character: > ' <";
}

if(strpos($bysend,":")!==false or strpos($_POST['group'],":")!==false){
$errorsay=1;
$_FILES['senda']['error']="Invalid character: > : <";
}


}

}



if($_POST['ende'] != "" and ( $errorsay != 1 ))
{
$return.="<a id='closa' href='javascript:window.close();'></a>";
}
else
$return.="<input type='button' style='display:none' id='closa'>";

$return.="
<input type='hidden' name='dataname' value='' id='dataname'>
<input type='hidden' name='accid' value='$accid'>
<input type='hidden' name='tipe' value='$filetype'>
<input type='hidden' name='chatid' value='$chatid'>
<input type='hidden' name='ende' value='0' id='ende'>
<input type='hidden' name='personal_key' value='$personal_key' id='personal_key'>
</form>
</body>
</html>
";


if($errorsay == 1){
$return="There was an ERROR: ".$_FILES['senda']['error'];
}

echo $return;

?>
