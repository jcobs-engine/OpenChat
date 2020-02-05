<?php
//CHANGELOG
$changelog=array(
    "2.0.0::You can login now via File.",
    "2.0.0::Encrypted End-to-End Cookies",
    "2.0.0::Encrypted 1024bit Login-Files",
    "2.0.0::Progress-Bar in Uploading",
    "2.0.0::You can login now via File.",
    "2.0.0::No-EtE-Chat Messages will removed after 24h.",
    "2.0.0::All Chat Files will removed after 24h.",
    "2.0.0::EtE-Chat Messages will removed after one month.",
    "2.0.0::<i>Many Design-Updates</i>",
    
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
    "aCThK2kZu8o",
    "FyPCMdrjpe0",
);


echo "
<html>
<head>
<title>
OpenChat | Messenger
</title>    
<style>
@keyframes down{
0%{
top:0px;
}
40%{
top:100px;
}
60%{
top:-300px;
}
100%{
top:100%;
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

<body style='background-color:rgb(10%, 10%, 10%); overflow-y:hidden;' onload=\"whitemask.style.display='block'; whitemask.style.animation='down 1.5s';whitemask.style.animationDelay='0.5s';whitemask.style.animationFillMode='forwards';setTimeout(function(){ whitemask.style.display='none'; document.getElementsByTagName('body')[0].style.overflowY='auto'; }, 2000);\">

<div id='whitemask' style='display:block;position:absolute; top:0px; left:0px; width:100%; height:100%; background-color:white; box-shadow:0px 0px 10px black;z-index:10000;'></div>

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
<div style='box-shadow:0px 0px 5px black;width:400px; height:100%; position:fixed; overflow-y:scroll;top:0px; right:0px;background-color:black'>";
foreach($yt  AS $link){
echo "<a target='_blank' href='https://www.youtube.com/watch?v=".$link."'><img src='../programm_files/lty_".$link.".png' style='margin-top:15px;margin-bottom:10px;width:calc(100% - 70px);cursor:pointer;position:relative; left: 40px;box-shadow:0px 0px 3px white;'  onmouseover=\"this.src='../programm_files/lty_".$link."_red.png';\" onmouseout=\"this.src='../programm_files/lty_".$link.".png';\"></a>";
}
echo "</div>";

echo "
<form action='../index.html' name='infos'>
</form>
</body>
</html>";
?>
