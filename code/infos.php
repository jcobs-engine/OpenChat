<?php
//CHANGELOG
$changelog=array(
    "2.0.0::The Changelog is opened",
    "2.0.0::The Changelog is opened",
    
    "1.0.0::The Changelog is opened",
    "1.0.0::Hey"
);

echo "
<html>
<head>
<title>
OpenChat | Messenger
</title>    
</head>

<body style='background-image:;'>
<!-- Release-List -->
<div style='width:100%; height:100%; position:absolute; top:0px; left:0px;'>";

echo "<img src='../programm_files/openserv.png' style='height:70px; position:relative; left:0px;padding-left:calc(50% - 160px);padding-right:calc(50% - 160px);background-color:#d1d1d1;border-radius:0px; box-shadow:0px 0px 15px 20px #d1d1d1;'>";

foreach($changelog AS $log){
    
    $log=explode('::', $log);
    $version=$log[0];
    $log=$log[1];

    if($version != $altversion){        
        echo "<div style='height:50px;'></div><div style='width:100%;padding-top:5px;height:60px; margin-bottom:20px;border-radius:0px; box-shadow:0px 0px 3px 5px #d1d1d1; background-color:#d1d1d1; text-align:center; color:black;font-size:40px;text-shadow:0px 0px 2px black;display:block;'><b><span style='color:rgb(20%, 20%, 20%);'>v</span>$version</b></div>";        
    }

    echo "<div style='width:100%; height:45px; margin-bottom:10px;border-radius:0px; box-shadow:0px 0px 3px 5px #d1d1d1; background-color:#d1d1d1; text-align:center; color:black;font-size:30px;text-shadow:0px 0px 2px black;display:block;'>$log</div>";
        
    $altversion=$version;    
}

echo "</body>
</html>";
?>