<?php
//CHANGELOG
$changelog=array(
    "1.0.0::OpenChat has now an icon.",
    "1.0.0::Files can now be renamed and moved.",
    "1.0.0::There is now a button to leave the chatroom.",
    "1.0.0::System-Messages are now also displayed in live mode.",
    "1.0.0::You can now change your Account-iD.",
    "1.0.0::Now the end-to-end encryption can be activated.",
    "1.0.0::<i>Many Security-Updates</i>",

);

$yt=array(
    "vIrZb3VA7Qg",
 );


echo "
<html>
<head>
<title>
OpenChat | Messenger
</title>    
<style>
@keyframes blendin{
0%{
opacity:0;
background-color:#070707;
}
100%{
opacity:1;
background-color:rgb(10%, 10%, 10%);
}
}
@keyframes goup{
0%{
    opacity:0;top:calc(100% - 65px);background-color:rgba(100%, 100%, 100%, 0.1);transform:rotate(0deg);left:calc(50% - 32px);
}
100%{
    opacity:1;top:20px;background-color:rgba(0%, 0%, 0%, 0.5);transform:rotate(90deg);left:15px;width:25px;
}
}
</style>
</head>

<body style='background-color:rgb(10%, 10%, 10%); animation:blendin 3s;'>

<img src='../programm_files/arrow.svg' style='animation:goup 2s; animation-delay:1s;animation-fill-mode:forwards;opacity:0;top:calc(100% - 65px);background-color:rgba(100%, 100%, 100%, 0.1);transform:rotate(0deg);left:calc(50% - 32px); width:35px; position:fixed;  padding:10px; border-radius:40px;z-index:1000;cursor:pointer;' onclick=\"document.infos.submit();\">



<!-- Release-List -->
<div style='width:calc(100% - 400px); height:100%; position:absolute; top:0px; left:0px;'>";

echo "<img src='../programm_files/openserv.png' style='height:70px; padding-top:5px;position:relative; left:0px;padding-left:calc(50% - 160px);padding-right:calc(50% - 160px);background-color:#d1d1d1;border-radius:0px; box-shadow:0px 0px 15px 20px #d1d1d1;'>";

foreach($changelog AS $log){
    
    $log=explode('::', $log);
    $version=$log[0];
    $log=$log[1];

    if($version != $altversion){        
        echo "<div style='height:50px;'></div><div style='width:100%;padding-top:5px;height:60px; margin-bottom:20px;border-radius:0px; box-shadow:0px 0px 3px 5px #d1d1d1; background-color:#d1d1d1; text-align:center; color:black;font-size:40px;text-shadow:0px 0px 2px black;display:block;'><b><span style='color:rgb(20%, 20%, 20%);'>v</span>$version</b></div>";        
    }

    echo "<div style='width:calc(100% - 50px); height:45px; margin-bottom:10px;border-radius:0px; box-shadow:0px 0px 3px 5px #d1d1d1; background-color:#d1d1d1; text-align:left; padding-left:50px;color:black;font-size:30px;text-shadow:0px 0px 2px black;display:block;'>$log</div>";
        
    $altversion=$version;    
}

echo "</div>
<div style='box-shadow:0px 0px 10px 30px black, inset 10px 0px 10px black;width:370px; height:100%; position:fixed; overflow-y:auto;top:0px; right:0px;'>";
foreach($yt  AS $link){
echo "<a target='_blank' href='https://www.youtube.com/watch?v=".$link."'><img src='../programm_files/lty_".$link.".png' style='margin-left:5px;width:calc(100% - 5px);cursor:pointer;'  onmouseover=\"this.src='../programm_files/lty_".$link."_red.png';\" onmouseout=\"this.src='../programm_files/lty_".$link.".png';\"></a>";
}
echo "</div>";

echo "
<form action='../index.html' name='infos'>
</form>
</body>
</html>";
?>