<?php

foreach ($_POST as $post_table => $content) {
    $post_table = str_replace('"', '#00100010#', $post_table);
    $post_table = str_replace("'", '#00100111#', $post_table);

    $content = str_replace('"', '#00100010#', $content);
    $content = str_replace("'", '#00100111#', $content);

    $_POST["$post_table"] = $content;
}

foreach ($_GET as $get_table => $content) {
    $get_table = str_replace('"', '#00100010#', $get_table);
    $get_table = str_replace("'", '#00100111#', $get_table);

    $content = str_replace('"', '#00100010#', $content);
    $content = str_replace("'", '#00100111#', $content);

    $_GET["$get_table"] = $content;
}

function krypti($str, $key)
{
    $cipher = 'AES-128-CBC';
    $key = pack('H*', md5($key));
    $iv = openssl_random_pseudo_bytes(16);
    $krypt = base64_encode(
        openssl_encrypt($str, $cipher, $key, OPENSSL_RAW_DATA, $iv)
    );
    $dekrypt = openssl_decrypt(
        base64_decode($krypt),
        $cipher,
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );
    $ascii_iv = base64_encode($iv);
    return $ascii_iv . $krypt;
}

function dekrypti($string, $key)
{
    $cipher = 'AES-128-CBC';
    $key = pack('H*', md5($key));
    $iv_ascii = substr($string, 0, 24);
    $iv = base64_decode($iv_ascii);
    $string = substr($string, 24);
    $dekrypt = openssl_decrypt(
        base64_decode($string),
        $cipher,
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );
    return $dekrypt;
}

$passwd =
    shell_exec('cat /usr/share/openchat-project/encryption_passwd.txt | tr -d " \t\n\r" ');

$sd = time();

$black = "#070707";
$white = "#d1d1d1";

$site = $_POST['site'];

if ($site == "") {
    $site = 0;
}

$join = 0;

$host = "localhost";
$benutzer = shell_exec('cat /usr/share/openchat-project/mysql_username.txt | tr -d " \t\n\r" ');
$passwort = shell_exec('cat /usr/share/openchat-project/mysql_password.txt | tr -d " \t\n\r" ');
$bindung = mysqli_connect($host, $benutzer, $passwort) or die("Verbindungsaufbau zur Daten-Zentrale nicht m&ouml;glich!");
$db = shell_exec('cat /usr/share/openchat-project/mysql_database.txt | tr -d " \t\n\r" ');

function mdq($bindung, $query)
{
    mysqli_select_db($bindung, shell_exec('cat /usr/share/openchat-project/mysql_database.txt | tr -d " \t\n\r" '));
    return mysqli_query($bindung, $query);
}

$online = 1;
$sql = "select id from user where id='0';";
$ask = mdq($bindung, $sql);
while ($row = mysqli_fetch_row($ask)) {
    $online = 0;
}

if ($online == 1) {
    $pfad = 'www.openchat.xyz/';
} else {
    $pfad = shell_exec('cat /usr/share/openchat-project/path.txt | tr -d " \t\n\r" ').'/code/';
}

$type = 'password';

if (isset($_POST['newid'])) {
    $verif = 0;
    while ($verif != 1) {
        $a = 0;
        while ($a <= 8) {
            $alpha = array(
                'a',
                'b',
                'c',
                'd',
                'e',
                'f',
                'g',
                'h',
                'i',
                'j',
                'k',
                'l',
                'm',
                'n',
                'o',
                'p',
                'q',
                'r',
                's',
                't',
                'u',
                'v',
                'w',
                'x',
                'y',
                'z'
            );

            $boz = rand(0, 1);

            if ($boz == 1) {
                $bu = rand(0, 25);
                $rand .= $alpha[$bu];
            } else {
                $rand .= rand(0, 9);
            }
            $a++;
        }

        $randi = md5($passwd.$rand);

        $drin = 0;
        $sql = "select id from user where id='$randi';";
        $ask = mdq($bindung, $sql);
        while ($row = mysqli_fetch_row($ask)) {
            $drin = 1;
        }

        if ($drin != 1) {
            $verif = 1;
        }

        if ($verif == 1) {
            $newids = $rand;
            $type = "text";

            $randi = $rand;
            $rand = md5($passwd.$rand);
            $sql = "insert into user set id='$rand', fname='anonymous';";
            $ask = mdq($bindung, $sql);

            $sql = "delete from message where nach='$:{$rand}';";
            $ask = mdq($bindung, $sql);

            $accmtitle = krypti('Your Account-iD', md5($passwd.$randi));
            $accmtext = krypti(
                "Your Account-iD is: <b>$randi</b><br><br>If you haven't saved your iD with browser you need it to log in!",
                md5($passwd.$randi)
            );

            $sql = "insert into message set von='SYSTEM', nach='$:{$rand}', text=\"$accmtext\", title=\"$accmtitle\", sd=$sd;";
            $ask = mdq($bindung, $sql);

            $site = 3;
        }
    }
}

$capture = $_POST['capture'];
$accidroh = $_POST['accid'];
$accid = md5($passwd.$_POST['accid']);
$personal_key=$_POST['accid'];

if ($accid != md5('')) {
    $sql = "select id, fname, timezone, sel from user where id='$accid';";
    $ask = mdq($bindung, $sql);
    while ($row = mysqli_fetch_row($ask)) {
        $id = $row[0];
        $fname = $row[1];
        $timezone = $row[2];
        $sel = $row[3];

        if (isset($_POST['deleteacc'])) {
            $sql = "select rights, id from chat where rights LIKE '%|$fname|%';";
            $ask = mdq($bindung, $sql);
            while ($row = mysqli_fetch_row($ask)) {
                $newrights = str_replace("|$fname|", "", $row[0]);

                $sql = "update chat set rights='$newrights' where id=$row[1];";
                $aska = mdq($bindung, $sql);
            }

            $sql = "delete from user where id='$id';";
            $aski = mdq($bindung, $sql);
            $id = "";
            $fname = "";
            $id = '';
        }
    }
    
    if($_POST['newidb'] == 1){
        $verif = 0;
        while ($verif != 1) {
            $a = 0;
            while ($a <= 8) {
                $alpha = array(
                    'a',
                    'b',
                    'c',
                    'd',
                    'e',
                    'f',
                    'g',
                    'h',
                    'i',
                    'j',
                    'k',
                    'l',
                    'm',
                    'n',
                    'o',
                    'p',
                    'q',
                    'r',
                    's',
                    't',
                    'u',
                    'v',
                    'w',
                    'x',
                    'y',
                    'z'
                );

                $boz = rand(0, 1);

                if ($boz == 1) {
                    $bu = rand(0, 25);
                    $rand .= $alpha[$bu];
                } else {
                    $rand .= rand(0, 9);
                }
                $a++;
            }

            $randi = md5($passwd.$rand);
            
            $drin = 0;
            $sql = "select id from user where id='$randi';";
            $ask = mdq($bindung, $sql);
            while ($row = mysqli_fetch_row($ask)) {
                $drin = 1;
            }

            if ($drin != 1) {
                $verif = 1;
            }

            if ($verif == 1) {
                $randb = md5($passwd.$rand);
                $sql = "update user set id='$randb' where sel=$sel;";
                $ask = mdq($bindung, $sql);
                $accidroh=$rand;
                $accid=$randb;
                $personal_key=$rand;
            }
        }
        
        
        $sql = "select id, fname, timezone, sel from user where id='$accid';";
        $ask = mdq($bindung, $sql);
        while ($row = mysqli_fetch_row($ask)) {
            $id = $row[0];
            $fname = $row[1];
            $timezone = $row[2];
            $sel = $row[3];
        }

    }
    
    $sdstop = 0;
    $sql = "select sd from seen where user=$sel and type=0;";
    $ask = mdq($bindung, $sql);
    while ($row = mysqli_fetch_row($ask)) {
        $sdstop = 1;
    }
    if ($sdstop != 1) {
        $sql = "insert into seen set sd='0', user=$sel, type=0;";
        $ask = mdq($bindung, $sql);
    }

    $sdstop = 0;
    $sql = "select sd from seen where user=$sel and type=1;";
    $ask = mdq($bindung, $sql);
    while ($row = mysqli_fetch_row($ask)) {
        $sdstop = 1;
    }
    if ($sdstop != 1) {
        $sql = "insert into seen set sd='0', user=$sel, type=1;";
        $ask = mdq($bindung, $sql);
    }
    $sdstop = 0;
    
    
  // ete setcookie
    
    $sql = "select id, enc, rights from chat where rights LIKE '%|$fname|%' and enc!='NONE' ORDER by id desc;";
    $ask = mdq($bindung, $sql);
    while ($row = mysqli_fetch_row($ask)) {
        $dcid=$row[0];
        $dcenc=$row[1];
        $newcookie=str_replace(' ', '', $_POST["newcookie$dcid"]);

        if(md5($passwd.$newcookie) == $dcenc){
            setcookie( $dcid, $newcookie, strtotime( '+1 years' ) );
            $_COOKIE[$dcid]=$newcookie;
            $go[$dcid]=1;
        }
        
        if(isset($_POST["cn".$dcid]) or $_POST["czn".$dcid] == 1 ){
          // automaticly sendup
          // if(n === 8){ fokus.focus();  setTimeout(function(){ document.save.submit(); },100); }
            
            $locki="
            <div style='position:fixed; text-shadow:0px 0px 3px white;font-weight:bold; font-size:35px;top:0px; left:0px; width:100%; height:100%; background-color:rgba(0%, 0%, 0%, 0.9);z-index:100; text-align:center;color:#d1d1d1'>
            </div>
            <img src='../programm_files/lock.png' id='lockpng' style='z-index:100;height:200px;position:fixed; left:calc(50% - 100px);top:calc(50% - 100px)'>
            <input type='text' id='gibkey' maxlength='7' onkeypress=\"let n=this.value.length + 1; if(n === 4){ this.value=this.value+' '; } if(n === 8){ fokus.focus();  setTimeout(function(){ document.save.submit(); },100); } \" style='border:0px; border-bottom:2px solid black;position:fixed;z-index:100;font-size:32px; width:120px; background-color:transparent;text-align:left;font-weight:bold;left:calc(50% - 61px); top:calc(50% + 15px);' name='newcookie$dcid' autofocus autocomplete='off'>
            ";
        }
    }
    
  //[END]ete setcookie
    

  //       DOTS/

  // MESSAGES

    $messagedot = 'none';
    $sql = "select sd from seen where user=$sel and type=0;";
    $ask = mdq($bindung, $sql);
    while ($row = mysqli_fetch_row($ask)) {
        $ltime = $row[0];
    }

    $sql = "select sd from message where sd>$ltime and ( nach='$fname' or nach='$:{$id}' );";
    $ask = mdq($bindung, $sql);
    while ($row = mysqli_fetch_row($ask)) {
        $messagedot = 'block';
    }

  // CHATROOMS
    $rd = 0;
    $rid = 0;
    $chatdot = 'none';
    $sql = "select id, title, rights from chat where rights LIKE '%|$fname|%' and id!=1 ORDER by id desc;";
    $ask = mdq($bindung, $sql);
    while ($row = mysqli_fetch_row($ask)) {
        $cid = $row[0];
        $ctitle = $row[1];
        $rights = $row[2];

        $sql = "select sd from seen where user=$sel and type=$cid;";
        $aska = mdq($bindung, $sql);
        while ($row = mysqli_fetch_row($aska)) {
            $ctime = $row[0];
        }

        $sql = "select sd from tell where chatid=$cid and sd>$ctime;";
        $aska = mdq($bindung, $sql);
        while ($row = mysqli_fetch_row($aska)) {
            $chatdot = 'block';
            $rid++;
            if (
                isset($_POST["c$cid"]) or
                $_POST["cz$cid"] == 1 or
                isset($_POST["setupsend$cid"])
            ) {
                $rd++;
            }

            if ($_POST['deleteamember'] != "") {
                $blockdot = 1;
            }
        }
    }

  // CDOTS

    $cdot = array();

    $sql = "select id, title, rights from chat where rights LIKE '%|$fname|%' ORDER by id desc;";
    $ask = mdq($bindung, $sql);
    while ($row = mysqli_fetch_row($ask)) {
        $cid = $row[0];
        $ctitle = $row[1];
        $rights = $row[2];
        $ctime = 0;
	$cdot[$cid]='none';

        $sql = "select sd from seen where user=$sel and type=$cid;";
        $aska = mdq($bindung, $sql);
        while ($rowa = mysqli_fetch_row($aska)) {
            $ctime = $rowa[0];
        }

        $sql = "select sd from tell where chatid=$cid and sd>$ctime;";
        $aska = mdq($bindung, $sql);
        while ($rowa = mysqli_fetch_row($aska)) {
	    $cdot[$cid] = 'block';
        }
    }

    if ($blockdot == 1 or ($rd - $rid) == 0) {
        $chatdot = 'none';
        $sql = "select id from chat where rights LIKE '%|$fname|%' ORDER by id desc;";
        $aska = mdq($bindung, $sql);
        while ($rowe = mysqli_fetch_row($ask)) {
            $rowa = $rowe[0];
            $cdot[$rowa] = 'none';
        }
    }

  //[END]  DOTS/

    $sql = "select id, title, rights from chat where rights LIKE '%|$fname|%' ORDER by id desc;";
    $ask = mdq($bindung, $sql);
    while ($row = mysqli_fetch_row($ask)) {
        $cad = $row[0];
        $ctitle = $row[1];
        $rights = $row[2];

        if ($_POST["cz$cad"] == 1) {
            $site = 2;
        }
    }

    if (isset($_POST['nsend'])) {
        $firstname = $_POST['firstname'];

        if ($_POST['timezone'] != $timezone) {
            $access = "Timezone changed";
            $timezone = $_POST['timezone'];

            $sql = "update user set timezone='$timezone' where id='$id';";
            $ask = mdq($bindung, $sql);
        }

        $fnamaaa = md5($firstname);
        $sql = "select sel, id from user where MD5( fname )!='$fnamaaa' and fname='$firstname';";
        $ask = mdq($bindung, $sql);
        while ($row = mysqli_fetch_row($ask)) {
            $block = 1;
        }

        $sql = "select sel, id from user where fname='$firstname';";
        $ask = mdq($bindung, $sql);
        while ($row = mysqli_fetch_row($ask)) {
            $block = 1;
        }

        if ($firstname == "") {
            $block = 1;
        }

        $oldname = $fname;

        if ($fname == $firstname) {
            $dontchange = 1;
            $block = 0;
        }

        if ($block != 1 and $dontchange != 1) {
            $sql = "update user set fname='$firstname' where id='$id' ;";
            $ask = mdq($bindung, $sql);
            $access = "Name changed";

            $deletenext = 1;

            $fname = $firstname;
        }
    }

    if ($id == "") {
        $break = 1;
    } else {
        $title = "$fname";

        if ($deletenext == 1) {
            $sql = "select rights, id from chat where rights LIKE '%|$oldname|%';";
            $ask = mdq($bindung, $sql);
            while ($row = mysqli_fetch_row($ask)) {
                $newrights = str_replace("|$oldname|", "", $row[0]);

                $sql = "update chat set rights='$newrights' where id=$row[1];";
                $aska = mdq($bindung, $sql);
            }

            $sql = "delete from message where von='$title' or nach='$title';";
            $ask = mdq($bindung, $sql);

            $sql = "update chat set rights=CONCAT(rights,'|$firstname|') where id=1;";
            $ask = mdq($bindung, $sql);
        }

        $sql = "select id from message where nach='$fname' or nach='$:{$accid}' ;";
        $ask = mdq($bindung, $sql);
        while ($row = mysqli_fetch_row($ask)) {
            if ($_POST['delete'] == $row[0]) {
                $sql = "delete from message where id=$row[0];";
                $aska = mdq($bindung, $sql);
            }
        }

        $sql = "select id from chat where rights LIKE '%$fname%';";
        $ask = mdq($bindung, $sql);
        while ($row = mysqli_fetch_row($ask)) {
            if ($_POST['deleti'] == $row[0]) {
                $sql = "delete from chat where id=$row[0];";
                $aska = mdq($bindung, $sql);
            }
        }

        if (isset($_POST['msend'])) {
            $mtitle = $_POST['mtitle'];
            $mto = $_POST['mto'];
            $mtext = nl2br($_POST['mtext']);
            if ($mto == "anonymous") {
                $mto = "";
            }

            if (
                $mto != "" and
                $mtitle != "" and
                $mtext != "" and
                $mtext != "'"
            ) {
                $mtext = krypti($mtext, $id);
                $mtitle = krypti($mtitle, $id);

                $sql = "insert into message set von='$fname', nach='$mto', text='$mtext', title='$mtitle', sd=$sd;";
                $ask = mdq($bindung, $sql);
                $access = "Sent message";
            }
        }

        $messages = "$white";
        $files = "$white";
        $cloud = "$white";
        $keys = "$white";
        $preference = "$white";
        $titlecolor = "$white";

        $sasa = "asisu";
        if ($site == 0) {
            $echo = "<img src='../programm_files/grey.png' style='position:fixed; width:40%; left:40%; top:100px; opacity:0;animation:grey 1s; animation-fill-mode:forwards; animation-delay:1.5s'>
<img src='../programm_files/color.png' style='position:fixed; width:40%; left:40%; top:100px;opacity:0; animation:color 1s; animation-fill-mode:forwards; animation-delay:2.5s;'>
<img src='../programm_files/serv.png' style='position:fixed; width:calc(40% + 70px); left:100%; top:100px;animation:serv 1s; animation-fill-mode:forwards; animation-delay:3s;'>

<img src='../programm_files/openchat_text.png' style='position:fixed; width:calc(40% + 70px); left:40%; top:100px; opacity:0; animation:inp 1s steps(1); animation-fill-mode:forwards;animation-delay:3s'>

<img src='../programm_files/openchat_overdrive.png' style='position:fixed; width:calc(40% +
70px); left:40%; top:100px; opacity:0; animation:inp 1s steps(1), sm 1s
steps(60), pni 1s steps(1);animation-fill-mode:forwards;animation-delay:2.5s,
4s, 5.05s;border:0px;'></div>
<img src='../programm_files/openchat_overdrive_2.png' style='position:fixed; width:calc(40% + 70px); left:40%; top:100px; opacity:0; animation:inp 1s steps(1), sm 1s steps(60), pni 1s steps(1);animation-fill-mode:forwards;animation-delay:2.5s, 4s, 3.68s;border:0px;'></div>
<img src='../programm_files/openchat_overdrive_3.png' style='position:fixed; width:calc(40% + 70px); left:40%; top:100px; opacity:0; animation:inp 1s steps(1), sm 1s steps(60), pni 1s steps(1);animation-fill-mode:forwards;animation-delay:2.5s, 4s, 3.3s;border:0px;'></div>

<div style='right:0px; height:100%; top:0px; position:fixed;background-color:black; box-shadow:0px 0px 4px 3px black;width:10px'></div>
";

            $sasa = "";
            $titlecolor = "white; text-shadow:0px 0px 1px white";
        }
        if ($site == 1) {
            $messagedot = 'none';

            $echo = "<input type='submit' style='position:absolute; width:calc(100% - 360px);cursor:pointer; border:0px; padding:05px; font-weight:bold; text-align:center; background-color:$white;color:$black; font-size:25px;' value='Write Message' name='wm'><div style='height:80px;'></div>";

            $sql = "select sd from seen where user=$sel and type=0;";
            $ask = mdq($bindung, $sql);
            while ($row = mysqli_fetch_row($ask)) {
                $sql = "update seen set sd='$sd' where user=$sel and type=0;";
                $ask = mdq($bindung, $sql);
                $sdstop = 1;
            }
            if ($sdstop != 1) {
                $sql = "insert into seen set sd='$sd', user=$sel, type=0;";
                $ask = mdq($bindung, $sql);
            }

            $sql = "select text, von, title, id from message where nach='$fname' or nach='$:{$accid}' ORDER by id desc;";
            $ask = mdq($bindung, $sql);
            while ($row = mysqli_fetch_row($ask)) {
                $sqla = "select id from user where fname='$row[1]';";
                $aska = mdq($bindung, $sqla);
                while ($rowuuus = mysqli_fetch_row($aska)) {
                    $vonid = $rowuuus[0];
                }

                if ($row[1] == "SYSTEM") {
                    $vonid = $id;
                }

                $row[0] = dekrypti($row[0], $vonid);
                $row[2] = dekrypti($row[2], $vonid);

                $row[0] = str_replace('#00100111#', "'", $row[0]);
                $row[0] = str_replace('#00100010#', '"', $row[0]);

                $echo .= "<div class='messages' style='font-size:18px;background-color:$black;box-shadow:0px 0px 1px 3px $white; cursor:pointer;border-radius:5px;width:calc(100% - 25px); padding:10px;margin-bottom:20px;text-align:center;' onclick=\"sethide();setback();conti$row[3].style.display='block'; this.style.backgroundColor='$white'; this.style.color='$black'; conti$row[3].style.backgroundColor='$white'; conti$row[3].style.color='$black';\" ><img src='../programm_files/delete.svg' style='float:left;height:24px;box-shadow:0px 0px 1px 5px red;background-color:red;border-radius:25%;cursor:pointer;' onclick=\"deleta.value='$row[3]'; document.save.submit(); event.cancelBubble=true;\"><span style='opacity:0;'>|</span><span style='float:left;'> &nbsp; &nbsp; $row[1]</span><b>$row[2]</b><span style='opacity:0;'>|</span><img src='../programm_files/delete.svg' style='float:right;opacity:0;height:24px;box-shadow:0px 0px 1px 5px red;background-color:red;border-radius:25%;cursor:pointer;'><span style='float:right; opacity:0;'> &nbsp; &nbsp; $row[1]</span></div>";

                $echo .= "<div class='messages contentis' style='font-size:18px;background-color:$black;box-shadow:0px 0px 1px 3px $white; border-radius:5px;width:calc(100% - 45px); padding:10px;padding-left:30px;margin-bottom:20px;text-align:left;display:none;' id='conti$row[3]'>$row[0]</div>";

                $jep = 1;
            }

            if (isset($_POST['wm'])) {
                $echo = "<input type='text' style='font-family:sans-serif;position:absolute; width:calc(100% - 360px);border:0px; padding:05px;  text-align:left; background-color:$white;color:$black; font-size:25px;' list='user' pattern='[A-Za-z0-9]{1,10}' maxlength='10' placeholder='To...' name='mto' autocomplete='off' required='required' autofocus>";
                $echo .= "<input type='text' style='top:120px;font-family:sans-serif;position:absolute; width:calc(100% - 360px);border:0px; padding:05px; font-weight:bold; text-align:center; background-color:$white;color:$black; font-size:25px;' placeholder='Title (Max: 32)' pattern='[A-Za-zÄäÖöÜü0-9\s#ß?!]{1,32}' maxlength='32' name='mtitle' autocomplete='off' required='required'>";
                $echo .= "<p><textarea style='resize:vertical;position:absolute; font-family:sans-serif;top:170px;width:calc(100% - 360px);border:0px; padding:05px;  text-align:left; background-color:$white;color:$black; font-size:20px;' rows='8' placeholder='Text' maxlength='500' name='mtext' required='required'></textarea>";
                $echo .= "<input type='submit' style='position:absolute; bottom:50px;width:calc(100% - 360px);cursor:pointer; border:0px; padding:05px; font-weight:bold; text-align:center; background-color:$white;color:$black; font-size:25px;' value='Send' name='msend'><p>";
            }

            $messages = "white; text-shadow:0px 0px 1px white";
        }

        if ($site == 2) {
            if (isset($_POST['csend'])) {
                $ctitle = $_POST['ctitle'];
                $sql = "insert into chat set title='$ctitle', rights='|$fname|';";
                $ask = mdq($bindung, $sql);
                $access = "Chatroom added";
            }

            $echo = "<input type='submit' style='position:absolute; width:calc(100% - 360px);cursor:pointer; border:0px; padding:05px; font-weight:bold; text-align:center; background-color:$white;color:$black; font-size:25px;' value='Create Chatroom' id='fokus' name='newchat'><div style='height:70px;'></div>";
            $files = "white; text-shadow:0px 0px 1px white";
            
            
            $sql = "select id, title, rights, enc from chat where rights LIKE '%|$fname|%' ORDER by id desc;";
            $ask = mdq($bindung, $sql);
            while ($row = mysqli_fetch_row($ask)) {
                $cid = $row[0];
                $ctitle = $row[1];
                $rights = $row[2];
                $cenc = $row[3];
                
                if($cenc != "NONE"){
                    $lock='block';
                }
                else{
                    $lock='none';
                }
                    
                $blocktnext=0;
                
                if (isset($_POST["setupsend$cid"])) {
                    $go[$cid] = 1;
                    $setuptitle = $_POST['setuptitle'];
                    $setupmembera = $_POST['setupmember'];
                    $deleteamembera = $_POST['deleteamember'];
                    
                    $sql = "select fname from user where fname='$setupmembera';";
                    $askus = mdq($bindung, $sql);
                    while ($rowus = mysqli_fetch_row($askus)) {
                        $setupmember = $rowus[0];
                    }

                    $sql = "select fname from user where fname='$deleteamembera';";
                    $askus = mdq($bindung, $sql);
                    while ($rowus = mysqli_fetch_row($askus)) {
                        $deleteamember = $rowus[0];
                    }

                if ($setupmember == "anonymous") {
                    $setupmember = "";
                }

                if (strpos($rights, "|$setupmember|") !== false) {
                    $setupmember = "";
                }

                if (strpos($rights, "|$deleteamember|") === false) {
                    $deleteamember = "";
                }

                if ($setuptitle != $ctitle and $setuptitle != "") {
                    $setuptitlecover = str_replace(" ", "%20", $setuptitle);
                    $text = "%23215$title%23216%20edit%20the%20title%20to%20%23215$setuptitlecover%23216.";
                    $_buffer = implode(
                        '',
                        file(
                            'http://' .
                                "$pfad" .
                                'insert.php?accid=7cbff9f534bf023c49c773f3fdd33ba7&chatid=' .
                                "$cid" .
                                '&text=' .
                                "$text" .
                                '&roomkey=' .
                                $_COOKIE[$cid]
                        )
                    );
                    $ctitle = "$setuptitle";
                    $access = "Title changed";
                }

                if ($setupmember != "") {
                    $text = "%23235$title%23236%20added%20%23235$setupmember%23236.";
                    $_buffer = implode(
                        '',
                        file(
                            'http://' .
                                "$pfad" .
                                'insert.php?accid=7cbff9f534bf023c49c773f3fdd33ba7&chatid=' .
                                "$cid" .
                                '&text=' .
                                "$text" .
                                '&roomkey=' .
                                $_COOKIE[$cid]

                        )
                    );

                    $access = "Member added";
                    $rights .= "|$setupmember|";
                }
                    
                if ($deleteamember == "$fname") {
                    $rights = str_replace("|$deleteamember|", "", $rights);
                    $text = "%23225$title%23226%20kicked%20himself.";
                    $_buffer = implode(
                        '',
                        file(
                            'http://' .
                                "$pfad" .
                                'insert.php?accid=7cbff9f534bf023c49c773f3fdd33ba7&chatid=' .
                                "$cid" .
                                '&text=' .
                                "$text" .
                                '&roomkey=' .
                                $_COOKIE[$cid]

                        )
                    );
                    $go[$cid]=0;
                    $blocktnext=1;
                    $blocknext=0;
                    $access = "Chat leaved";
                } elseif ($deleteamember != "") {
                    $rights = str_replace("|$deleteamember|", "", $rights);
                    $text = "%23225$title%23226%20kicked%20%23225$deleteamember%23226.";
                    $_buffer = implode(
                        '',
                        file(
                            'http://' .
                                "$pfad" .
                                'insert.php?accid=7cbff9f534bf023c49c773f3fdd33ba7&chatid=' .
                                "$cid" .
                                '&text=' .
                                "$text" .
                                '&roomkey=' .
                                $_COOKIE[$cid]

                        )
                    );
                    $access = "Member kicked";
                }

                $sql = "update chat set title='$setuptitle', rights='$rights' where id=$cid;";
                $aska = mdq($bindung, $sql);
                
            }
                $nowgo=1;
                
                if($go[$cid] == 1){
                    $nowgo=0;
                }
                
                if($blocktnext == 1 or $blocknext == 1){
                    $nowgo=0;
                }
                
                if ( $nowgo == 1) {
                    
                    
                    if($_COOKIE['$cid'] == "" and $cenc != 'NONE'){
                           $cidcapture="n$cid";
                    }
                                            
                        if($cenc == 'NONE' or md5($passwd.$_COOKIE[$cid]) == $cenc)
                            $cidcapture=$cid;
                        
                    $echo .= "<button style='position:relative; width:calc(100% - 8px);cursor:pointer; border:0px; padding:05px; font-weight:bold; text-align:center; background-color:$black;color:$white; font-size:25px;box-shadow:0px 0px 5px 2px $white; border-radius:3px;top:20px;margin-bottom:30px' name='c$cidcapture' >
<img src='../programm_files/lock.png' style='display:$lock;width:30px;float:left;'>

<div style='display:$cdot[$cid];opacity:0;background-color:red;box-shadow:0px 0px 3px red;float:left;font-size:20px; width:15px; margin-left:5px;margin-top:10px;height:15px;border-radius:50%;'></div>


<span style='opacity:0;'>|</span>

$ctitle

<span style='opacity:0;'>|</span>


<div style='display:$cdot[$cid];background-color:red;box-shadow:0px 0px 3px red;float:right;font-size:20px; width:15px;margin-right:5px; margin-top:9px;height:15px;border-radius:50%;'></div>
<img src='../programm_files/lock.png' style='display:$lock;width:30px;float:right;opacity:0;'>

</button>

";
                }

                if (
                    isset($_POST["c$cid"]) or
                    $_POST["cz$cid"] == 1 or
                    $go[$cid] == 1 or isset($_POST["setuproom$cid"])
                ) {
                    $join = $cid;
                    $blocknext = 1;
                    $showchat = $cid;
                    $keychat=$_COOKIE[$cid];
                    if($keychat == ''){
                        $keychat='NONE';   
                    }
                    $rightsformated = str_replace("||", "</b>, <b>", $rights);
                    $rightsformated = str_replace("|", "", $rightsformated);

                    $echo = "<input type='text' style='font-family:sans-serif;position:absolute; bottom:50px; width:calc(100% - 420px);border:0px; padding:05px;  text-align:left; background-color:$white;color:$black; font-size:25px;' placeholder='Message...' autocomplete='off' maxlength='500' id='inputmes' name='center$cid' autofocus='autofocus'><img src='../programm_files/present.png' onclick=\"document.upload.submit();\" style='position:absolute; bottom:50px;width:45px;right:65px; cursor:pointer;' id='inputmesa' autofocus>
<div style='display:none;' id='nothing'></div>
<div onclick='autoscrolll();' id='autoscrol' style='position:absolute; top:125px; font-size:20px;right:100px; z-index:100;cursor:pointer; padding:5px; border:3px solid #238c0e; border-radius:5px;background-color:black; box-shadow:0px 0px 3px 7px #070707;' >live mode</div>



<div id='chat' style='position:absolute;top:100px; width:calc(100% - 396px);overflow-y:hidden;bottom:106px;border:3px solid $white; font-size:30px;border-radius:3px;padding:10px;padding-left:20px;'>

";


                    $echo .= "Loading Chat...";

                    if ($cid == 1) {
                        $disabled = "disabled='disabled'";
                    } else {
                        $disabled = "";
                    }

                    $echo .= "</div>
<input type='submit' style='position:absolute; width:calc(100% - 360px);cursor:pointer;top:40px; border:0px; padding:05px; font-weight:bold; text-align:center; background-color:$white;color:$black; font-size:25px;' value='$ctitle' name='setuproom$cid' $disabled><div style='position:absolute; top:12px; color:$white; font-size:15px;width:calc(100% - 360px);'><marquee style='width:100%;font-size:15px;' ><b>$rightsformated</b></marquee></div>";
                


                if (isset($_POST["setuproom$cid"])) {
                    $blocknext = 1;
		    
                    if ($cid != 1) {
                        $echo = "<input type='text' style='font-family:sans-serif;position:absolute; width:calc(100% - 360px);border:0px; padding:05px;  text-align:left; background-color:$white;color:$black; font-size:25px;' placeholder='Title (Max: 32)' pattern='[A-Za-zäÄöÖüÜ0-9\s#]{1,32}' name='setuptitle' value='$ctitle' autocomplete='off' required='required' maxlength='32'>";

                        $echo .= "<input type='text' style='font-family:sans-serif;position:absolute; width:calc(100% - 360px);top:120px;border:0px; padding:05px;  text-align:left; background-color:$white;color:$black; font-size:25px;' placeholder='(Add new member)' pattern='[A-Za-z0-9]{1,10}' list='user' name='setupmember' autocomplete='off' maxlength='10'>";

                        $echo .= "<input type='text' style='font-family:sans-serif;position:absolute; width:calc(100% - 360px);top:180px;border:0px; padding:05px;  text-align:left; background-color:$white;color:$black; font-size:25px;' placeholder='(Delete a member)' pattern='[A-Za-z0-9]{1,10}' list='user' name='deleteamember' autocomplete='off' id='deleteamember' maxlength='10'>";

                        $echo .= "<input type='button' onclick=\"deleteamember.value='$fname';setupsend.click();\" style='position:absolute; top:320px;width:calc(100% - 360px);cursor:pointer; border:0px; padding:05px; font-weight:bold; text-align:center; background-color:#eb2121;color:$black; font-size:25px;' value='Leave Chat' name='leave$cid'><p>";
                        if($cenc == 'NONE'){
                        $echo.="<img id='lock' src='../programm_files/lock.png' style='height:50px; top:245px; position:absolute; filter:grayscale(1); opacity:1'><div 
                        onmouseover=\"this.style.animation='hops 0.3s'; this.style.animationFillMode='forwards'\"
                        onmouseout=\"this.style.animation='hopsz 0.3s'; this.style.animationFillMode='forwards'\"
                        onclick=\"this.style.animation='anable 1s'; this.style.animationFillMode='forwards';lock.style.animation='enable 0.8s'; lock.style.animationFillMode='forwards';setTimeout(function(){ enc.value='$cid'; site.value=7; document.save.submit(); },1000)\"
                        style='height:35px;cursor:pointer; padding-left:50px;top:250px; padding-top:15px;position:absolute; opacity:0.4; filter:grayscale(1); font-size:22px; color:#d99b39'><b>End-to-End Encryption</b></div>";
                        }
                        else
                        {
                        $echo.="<img id='lock' src='../programm_files/lock.png' style='height:50px; top:245px; position:absolute; filter:grayscale(0); opacity:1'><div 
                        onclick=\"site.value=7; document.save.submit();\"
                        style='height:35px;cursor:pointer; padding-left:50px;top:250px; padding-top:15px;position:absolute; opacity:1; filter:grayscale(0); font-size:22px; color:#d99b39'><b>End-to-End Encrypted</b></div>";
                        }
                        $echo .= "<input type='submit' id='setupsend' style='position:absolute; bottom:50px;width:calc(100% - 360px);cursor:pointer; border:0px; padding:05px; font-weight:bold; text-align:center; background-color:$white;color:$black; font-size:25px;' value='Send' name='setupsend$cid'><p>";
                    }
                }
            }
        }

        if (isset($_POST['newchat'])) {
            $echo = "<input type='text' style='font-family:sans-serif;position:absolute; width:calc(100% - 360px);border:0px; padding:05px;  text-align:left; background-color:$white;color:$black; font-size:25px;' placeholder='Title (Letters, Numbers, Space and #. Max: 32)' pattern='[A-Za-z0-9\säÄöÖüÜ#]{1,32}' maxlength='32' name='ctitle' autocomplete='off' required='required' autofocus>";
            $echo .= "<input type='submit' style='position:absolute; bottom:50px;width:calc(100% - 360px);cursor:pointer; border:0px; padding:05px; font-weight:bold; text-align:center; background-color:$white;color:$black; font-size:25px;' value='Send' name='csend'><p>";
        }
    }

    if ($site == 5) {
        $cloud = "white; text-shadow:0px 0px 1px white";
        $echo = "<input type='button' onclick=\"black.style.display='block'; document.uplad.submit();\" style='position:absolute; width:calc(100% - 360px);cursor:pointer; border:0px; padding:05px; font-weight:bold; text-align:center; background-color:$white;color:$black; font-size:25px;' value='Upload file'><div style='height:70px;'></div>

<div id='black' style='display:none;position:fixed; top:0px; left:0px; width:100%; height:100%; background-color:rgba(0%, 0%, 0%, 0.8);z-index:10000000; text-align:center;color:white'>
<input type='submit' style='position:absolute; left:0px;top:50%;width:calc(100%);cursor:pointer; border:0px; padding:05px; \
font-weight:bold; text-align:center; background-color:$white;color:$black; font-size:25px;' value='reload'>
</div>

<div>
<h1>Your files:</h1>";

        $praefix = array();

        $sqla = "select id, ownname, title from file where rmid='$id' ORDER by title;";
        $aska = mdq($bindung, $sqla);
        while ($rowa = mysqli_fetch_row($aska)) {
            $i = $rowa[0];
            $openid = md5($passwd . $i);

            $fileid = md5($passwd . $rowa[0]);

            $fileid = shell_exec("ls ../user_files/$fileid" . '.*.gpg');
            $fileowner = $rowa[1];
            $fileidus = explode('.', $fileid);
            $filetitle = $rowa[2];

            $jetztpre = "";

            if ($fileid != "") {
                if ($_POST['rmfile'] == $i) {
                    $ergebniss = shell_exec("rm $fileid");
                } else {
                    if (strpos($filetitle, ":") !== false) {
                        $prae = explode(':', $filetitle);
                        $filetitle = $prae[1];

                        $praefix[] = $prae[0];
                        $jetztpre = $prae[0];

                        if ($jetztpre != 'ALL') {
                            $anypre = 1;
                        }
                    }
                    $ic = $i;

                    if ($jetztpre == "ALL") {
                        $i = '*$$';
                        $jetztpre = "";
                    }

                    $ookay = 1;

                    $echo .= "
<div class='$jetztpre all' onmouseover=\"a$i.style.animation='inp 0.5s'; a$i.style.animationFillMode='forwards';\"  onmouseout=\"a$i.style.animation='pni 0.5s'; a$i.style.animationFillMode='forwards';\" onclick=\"window.open( 'getfile.php?accid=$id&fileid=$openid&chatid=0&personal_key=$personal_key' );\" style=\"display:block;background-image:url( ../programm_files/datei.png );background-size:cover;background-repeat:no-repeat;background-position:center;word-wrap:break-word;box-shadow: 0px 0px 1px 2px #d1d1d1;float:left;margin:20px; width:160px; height:160px;padding:10px;border-radius:10px;background-color:#d1d1d1;cursor:pointer\">
<div style='width:160px; height:160px;position:absolute; '>

<div
onclick=\"rmfile.value='$ic';document.save.submit();event.cancelBubble=true;\" style='cursor:pointer;font-size:22px;color:white; border:2px solid black; border-radius:10px 10px 10px 10px;padding:5px;position:absolute; top:-05px; right:-05px;box-shadow:0px 0px 2px black;height:24px;width:24px;background-color:red;background-image:url( ../programm_files/delete.svg );background-size:70%;background-position:center;background-repeat:no-repeat;'></div>

<div style='cursor:pointer;font-size:18px;color:white;padding:2px;padding-left:5px; padding-right:6px;position:absolute; top:-05px; left:-5px;box-shadow:0px 0px 2px black;height:23px;background-color:#d1d1d1;border-radius:6px;color:black;'>$ic</div>

<span id='a$i' style='opacity:0;font-size:19px;color:#070707; border:2px solid black; border-radius:3px;padding:3px;padding-top:3px;padding-right:7px; padding-left:7px;position:absolute; top:48px; left:-05px;background-color:#2e852e;'><b>#</b>$jetztpre</span>

<div style='text-align:center;font-size:20px;font-weight:bold; color:#070707;position:absolute;width:170px;left:-10px;bottom:-10px;text-shadow:0px 0px 5px black;box-shadow:0px -3px 3px black;padding:5px;border-radius:03px 03px 10px 10px;background-color:#37f937'>$filetitle</div>

</div></div>";
                }
            }
        }

        if ($anypre == 1) {
            $echo .= "<select id='selectfeld' onchange=\"setnone( 'all' ); setblock( this.value );\" style='position:absolute; top:140px;right:60px;width:250px;cursor:pointer; border:0px; padding:05px; font-weight:bold; text-align:left; background-color:$white;color:$black; font-size:25px;'>";
            $echo .= "<option>ALL</option>";

            foreach ($praefix as $pr) {
                if ($pr != $vorpr and $pr != 'ALL') {
                    $echo .= "<option>$pr</option>";
                }

                $vorpr = $pr;
            }

            $echo .= "</select>";
        }

        if ($ookay == 1) {
            $echo .= "<div style='clear:left;'></div>";
        } else {
            $echo .=
                "<center style='font-size:20px;'> &nbsp; &nbsp; <b>NO FILES</b> &nbsp; &nbsp; </center>";
        }

        $echo .= "<h1>Shared with you:</h1>";

        $sql = "select id, title, rights, enc from chat where rights LIKE '%|$fname|%' ORDER by id desc;";
        $ask = mdq($bindung, $sql);
        while ($row = mysqli_fetch_row($ask)) {
            $cada = $row[0];
            $cadname = $row[1];
            $schongezeigt = 0;

            if($row[3] == 'NONE' or $row[3] == md5($passwd.$_COOKIE[$cada]))
            {

            $sqla = "select id, ownname, title from file where rmid=$cada ORDER by id desc;";
            $aska = mdq($bindung, $sqla);

            while ($rowa = mysqli_fetch_row($aska)) {
                $i = $rowa[0];

                $fileid = md5($passwd . $rowa[0]);

                $fileid = shell_exec("ls ../user_files/$fileid" . '.*');
                $fileowner = $rowa[1];
                $fileidus = explode('.', $fileid);
                $filetitle = $rowa[2];

                if ($fileid != "") {
                    if ($schongezeigt != 1) {
                        $schongezeigt = 1;
                    }
                    $aakay = 1;

                    $echo .=
                        "
<div onmouseover=\"i$i.style.animation='inp 0.5s'; i$i.style.animationFillMode='forwards';\"  onmouseout=\"i$i.style.animation='pni 0.5s'; i$i.style.animationFillMode='forwards';\"  onclick=\"window.open( 'getfile.php?accid=$id&fileid=" .
                        md5($passwd.$i) .
                        "&chatid=$cada' );\"  style=\"background-image:url( ../programm_files/datei.png );background-size:cover;cursor:pointer;background-repeat:no-repeat;background-position:center;word-wrap:break-word;box-shadow: 0px 0px 1px 2px #d1d1d1;float:left;margin:20px; width:160px; height:160px;padding:10px;border-radius:10px;background-color:#d1d1d1\">
<div style='width:160px; height:160px;position:absolute; '>

<span style='font-size:22px;color:white; border:2px solid black; border-radius:10px 10px 0px 10px;padding:4px;padding-top:4px;padding-right:10px; padding-left:10px;position:absolute; top:-05px; right:-05px;box-shadow:0px 0px 2px 0black;background-color:#0b2900;'><b>$fileowner</b></span>

<div onclick='this.select' style='cursor:pointer;font-size:18px;color:white;padding:2px;padding-left:5px; padding-right:6px;position:absolute; top:-05px; left:-5px;box-shadow:0px 0px 2px black;height:23px;background-color:#d1d1d1;border-radius:6px;color:black;'>$i</div>

<span id='i$i' style='opacity:0;font-size:19px;color:#070707; border:2px solid black; border-radius:3px;padding:3px;padding-top:3px;padding-right:7px; padding-left:7px;position:absolute; top:48px; left:-05px;background-color:#2e852e;'><b>#</b>$cadname</span>


<div style='text-align:center;font-size:20px;font-weight:bold; color:#070707;position:absolute;width:170px;left:-10px;bottom:-10px;text-shadow:0px 0px 5px black;box-shadow:0px -3px 3px black;padding:5px;border-radius:03px 03px 10px 10px;background-color:#37f937'>$filetitle</div>

</div></div>";
                }
            }
            }
        }

        $echo .= "</div>";

        if ($aakay != 1) {
            $echo .=
                "<center style='font-size:20px;'> &nbsp; &nbsp; <b>NO FILES</b> &nbsp; &nbsp; </center>";
        }
    }

        if ($site == 7){
            $keys = "white; text-shadow:0px 0px 1px white";
            
                if($_POST['disete'] != ""){
                    $sql = "select id from chat where rights LIKE '%|$fname|%' and id=".$_POST['disete']." and enc!='NONE' ORDER by id desc;";
                    $ask = mdq($bindung, $sql);
                    while ($row = mysqli_fetch_row($ask)) {
                        setcookie( $_POST['disete'], '', strtotime( '-1 years' ) );
                        $sql = "update chat set enc='NONE' where id=".$_POST['disete'].";";
                        $aska = mdq($bindung, $sql);
                        
                        $sql = "delete from tell where chatid=".$_POST['disete'].";";
                        $aska = mdq($bindung, $sql);
                        
                    }   
                }
            
                if($_POST['enc'] != ""){
                    $sql = "select id from chat where rights LIKE '%|$fname|%' and id=".$_POST['enc']." and enc='NONE' ORDER by id desc;";
                    $ask = mdq($bindung, $sql);
                    while ($row = mysqli_fetch_row($ask)) {
                        $encrand=rand(111111,999999);
                        setcookie( $_POST['enc'], $encrand, strtotime( '+1 years' ) );
                        $knowc=1;
                        $enci=$encrand;
                        $sql = "update chat set enc='".md5($passwd.$encrand)."' where id=".$_POST['enc'].";";
                        $aska = mdq($bindung, $sql);
                        $encb=$_POST['enc'];
                        
                        $sql = "delete from tell where chatid=".$_POST['enc'].";";
                        $aska = mdq($bindung, $sql);

                        
                    }
                }
            
                
                $echo="<h1>Personal-Key (Account-ID):</h1>";
                $echo.="<table style='border-spacing:0;margin-bottom:100px;cursor:default;width:100%; font-size:20px;'><tr><td style='padding:6px;padding-bottom:8px;border-top:2px solid white;border-bottom:2px solid white;font-weight:bold;text-shadow:0px 0px 3px #eb2121;'>$personal_key</td><td style='width:25px;text-align:right;padding:6px;padding-bottom:8px;border-top:2px solid white;border-bottom:2px solid white;'><img src='../programm_files/reload.png' style='height:20px;cursor:pointer;' onclick=\"newidb.value=1;document.save.submit();\"></td></tr></table>";

                $echo.="<h1>Room-Keys (End-to-End):<img src='../programm_files/disable_ete.png' style='display:blocki;height:22px; float:right;padding-top:30px;'></h1>";
                $echo.="<table style='border-spacing:0;cursor:default;width:100%; font-size:20px;'>";
            
                $sql = "select id from chat where rights LIKE '%|$fname|%' and enc!='NONE' ORDER by id desc;";
                $ask = mdq($bindung, $sql);
                while ($row = mysqli_fetch_row($ask)) {
                    $dcid=$row[0];
                    
                    if(isset($_POST['deletec'])){
                        setcookie( $dcid, '', strtotime( '-1 years' ) );
                    }
                    
                    $sql = "select title from chat where id=$dcid ORDER by id desc;";
                    $askas = mdq($bindung, $sql);
                    while ($rowas = mysqli_fetch_row($askas)) {
                        $dcti=$rowas[0];
                    }
                    
                    $dcen=$_COOKIE[$dcid];
                    
                    if($_COOKIE[$dcid] != "")
                        $knowc=1;
                    
                    if($dcid == $encb){
                    $dcen="$enci";
                    }
                    
                    if($dcen == "" or isset($_POST['deletec'])){
                        $dcen="??????";   
                    }
                        
                    $dcen=$dcen[0].$dcen[1].$dcen[2].' '.$dcen[3].$dcen[4].$dcen[5];
                    
                    $echo.="<tr>
                    <td style='width:4em;padding:6px;padding-bottom:5px;border-top:2px solid white;font-weight:bold;text-shadow:0px 0px 3px green;'>$dcen</td>
                    <td style='text-align:center;padding:6px;padding-right:calc(4em - 25px);padding-top:0px;padding-bottom:0px;border-top:2px solid white;'>$dcti</td>
                    <td style='width:25px;text-align:right;padding:6px;padding-bottom:5px;border-top:2px solid white;border-bottom:0px solid white;'><img src='../programm_files/delete_white.png' style='height:20px;cursor:pointer;' onclick=\"disete.value=$dcid;document.save.submit();\"></td></tr>";
                    $in=1;
                }
            
                if($in != 1){
                        $echo=str_replace('blocki', 'none', $echo);
                        $echo.="<center style='font-size:20px;'> &nbsp; &nbsp; <b>No Room with End-to-End Encryption</b> &nbsp; &nbsp; </center>";
                }
                else{    
                    $echo=str_replace('blocki', 'block', $echo);

                    $echo.="<tr>
                <td style='width:4em;padding:6px;padding-bottom:8px;border-top:2px solid white;'></td>
                <td style='text-align:center;padding:6px;padding-right:calc(4em - 25px);padding-bottom:8px;border-top:2px solid white;'></td>
                <td style='width:25px;text-align:right;padding:6px;padding-bottom:8px;border-top:2px solid white;border-bottom:0px solid white;'><img src='../programm_files/delete_white.png' style='height:20px;cursor:pointer;opacity:0' onclick=\"newidb.value=1;document.save.submit();\"></td></tr>";
                }
            
            $echo.="</table>";
            
            if($in == 1 and $knowc == 1 and !isset($_POST['deletec'])){
            $echo.="<input type='submit' style='position:relative; width:100% ;cursor:pointer; border:0px; padding:05px; font-weight:bold; text-align:center; background-color:#eb2121;color:$black; font-size:25px;' value='Delete all keys from this device' name='deletec'>";
            }
            
        }
        
    if ($site == 3 or "$fname" == "anonymous") {
        $sasa = "klh";
        $messages = "$white";
        $files = "$white";
        $preference = "$white";
        $titlecolor = "$white";

        $fnamecover = $fname;

        if ($fname == "anonymous") {
            $block = 1;
        }

        if ($block == 1) {
            $an = "animation:block 1s;";
            $fnamecover = "$fname";
        } else {
            $an = "animation:free 1s;";
        }

        $echo = "<input type='text' style='font-family:sans-serif;position:absolute; width:calc(100% - 360px);border:0px; padding:05px;  text-align:left; background-color:$white;color:$black; font-size:25px;$an' placeholder='Name (Letters and Numbers. Max: 10)' pattern='[A-Za-z0-9]{1,10}' maxlength='10' name='firstname' value='$fnamecover' autocomplete='off'>
<div style='font-family:sans-serif;position:absolute; top:100px;width:calc(100% - 360px);border:0px; padding:05px;  text-align:left;color:$white; font-size:18px;font-weight:bold'>If you change your name, chatrooms and messages will be reset!</div>
";

        $echo .= "<input type='submit' style='position:absolute; top:250px;width:calc(100% - 360px);cursor:pointer; border:0px; padding:05px; font-weight:bold; text-align:center; background-color:#eb2121;color:$black; font-size:25px;' value='Delete Account' name='deleteacc'>";

        $echo .= "<select style='position:absolute; top:150px;width:calc(100% - 360px);cursor:pointer; border:0px; padding:05px; font-weight:bold; text-align:left; background-color:$white;color:$black; font-size:25px;' name='timezone'>";

        if ($timezone == '-12') {
            $m12 = "selected";
        }

        if ($timezone == '-11') {
            $m11 = "selected";
        }

        if ($timezone == '-10') {
            $m10 = "selected";
        }

        if ($timezone == '-09') {
            $m09 = "selected";
        }

        if ($timezone == '-08') {
            $m08 = "selected";
        }

        if ($timezone == '-07') {
            $m07 = "selected";
        }

        if ($timezone == '-06') {
            $m06 = "selected";
        }

        if ($timezone == '-05') {
            $m05 = "selected";
        }

        if ($timezone == '-04') {
            $m04 = "selected";
        }

        if ($timezone == '-03') {
            $m03 = "selected";
        }

        if ($timezone == '-02') {
            $m02 = "selected";
        }

        if ($timezone == '-01') {
            $m01 = "selected";
        }

        if ($timezone == '00') {
            $gmt = "selected";
        }

        if ($timezone == '01') {
            $p01 = "selected";
        }

        if ($timezone == '02') {
            $p02 = "selected";
        }

        if ($timezone == '02') {
            $p02 = "selected";
        }

        if ($timezone == '03') {
            $p03 = "selected";
        }

        if ($timezone == '04') {
            $p04 = "selected";
        }

        if ($timezone == '05') {
            $p05 = "selected";
        }

        if ($timezone == '06') {
            $p06 = "selected";
        }

        if ($timezone == '07') {
            $p07 = "selected";
        }

        if ($timezone == '08') {
            $p01 = "selected";
        }

        if ($timezone == '09') {
            $p09 = "selected";
        }

        if ($timezone == '10') {
            $p10 = "selected";
        }

        if ($timezone == '11') {
            $p11 = "selected";
        }

        if ($timezone == '12') {
            $p12 = "selected";
        }

        $echo .= "
<option value='-12' $m12>GMT -12</option>
<option value='-11' $m11>GMT -11</option>
<option value='-10' $m10>GMT -10</option>
<option value='-9'  $m09>GMT -09</option>
<option value='-8'  $m08>GMT -08</option>
<option value='-7'  $m07>GMT -07</option>
<option value='-6'  $m06>GMT -06</option>
<option value='-5'  $m05>GMT -05</option>
<option value='-4'  $m04>GMT -04</option>
<option value='-3'  $m03>GMT -03</option>
<option value='-2'  $m02>GMT -02</option>
<option value='-1'  $m01>GMT -01</option>
<option value='0'   $gmt>GMT ±00</option>
<option value='1'  $p01>GMT +01</option>
<option value='2'  $p02>GMT +02</option>
<option value='3'  $p03>GMT +03</option>
<option value='4'  $p04>GMT +04</option>
<option value='5'  $p05>GMT +05</option>
<option value='6'  $p06>GMT +06</option>
<option value='7'  $p07>GMT +07</option>
<option value='8'  $p08>GMT +08</option>
<option value='9'  $p09>GMT +09</option>
<option value='10' $p10>GMT +10</option>
<option value='11' $p11>GMT +11</option>
<option value='12' $p12>GMT +12</option>

</select>
";

        $echo .= "<input type='submit' style='position:absolute; bottom:50px;width:calc(100% - 360px);cursor:pointer; border:0px; padding:05px; font-weight:bold; text-align:center; background-color:$white;color:$black; font-size:25px;' value='Send' name='nsend'><p>";
        $preference = "white; text-shadow:0px 0px 1px white";
    }


    }

    $content = "
<input type='hidden' value='$accidroh' name='accid'>
<input type='hidden' value='$capture' name='capture'>
<input type='hidden' value='' name='enc' id='enc'>
<input type='hidden' value='0' name='newidb' id='newidb'>
<input type='hidden' value='' name='disete' id='disete'>
<table style='width:calc(100% - 20px); position:absolute; top:10px; left:10px; height:calc(100% - 20px);'>
<tr><td onclick=\"site.value='0'; document.save.submit();\" style='cursor:pointer;width: 200px;height:30px; box-shadow:0px 0px 0px $white, inset 0px 0px 0px $white;padding:15px;text-align:left;padding-left:30px;font-size:25px; font-weight:bold;color:$titlecolor'>$title</td><td rowspan='7' style='padding: 50px; vertical-align:top; box-shadow:0px 0px 2px 2px $white, inset 0px 0px 2px 2px $white; animation: blink 1s$sasa; animation-fill-mode:forwards; background-color:transparent;animation-delay:0.5s'>
$echo
</td></tr>


<tr><td onclick=\"site.value='1'; document.save.submit();\" style='cursor:pointer;width: 200px; height:30px;box-shadow:0px 0px 2px 2px $white, inset 0px 0px 2px 2px $white;padding:15px;color:$messages;padding-left:30px;font-size:25px; font-weight:bold;'>Messages

<div style='display:$messagedot;background-color:red;box-shadow:0px 0px 3px red;float:right;font-size:20px; width:15px; margin-top:9px;height:15px;border-radius:50%;'></div>

</td></tr>
<tr><td onclick=\"site.value='2'; document.save.submit();\" style='cursor:pointer;width: 200px; height:30px;box-shadow:0px 0px 2px 2px $white, inset 0px 0px 2px 2px $white;padding:15px;padding-left:30px;font-size:25px; font-weight:bold;color:$files'>Chatrooms

<div style='display:$chatdot;background-color:red;box-shadow:0px 0px 3px red;float:right;font-size:20px; width:15px; margin-top:9px;height:15px;border-radius:50%;'></div>

</td></tr>
<tr><td onclick=\"site.value='5'; document.save.submit();\" style='cursor:pointer;width: 200px; height:30px;box-shadow:0px 0px 2px 2px $white, inset 0px 0px 2px 2px $white;padding:15px;padding-left:30px;font-size:25px; font-weight:bold;color:$cloud'>Cloud</td></tr>

<tr><td style='width: 100px;font-size:20px; vertical-align:top; padding-right:10px;padding-top:40px;padding-bottom:40px;'>
<div id='onlana'></div>
</td></tr>

<tr><td onclick=\"site.value='7'; document.save.submit();\" style='cursor:pointer;width: 200px; height:30px;box-shadow:0px 0px 2px 2px $white, inset 0px 0px 2px 2px $white;padding:15px;padding-left:30px;font-size:25px; background-color:#500006;font-weight:bold;color:$keys'>Keys</td></tr>

<tr><td onclick=\"site.value='3'; document.save.submit();\" style='cursor:pointer;background-color:#93000b;width: 200px; height:30px;box-shadow:0px 0px 2px 2px $white, inset 0px 0px 2px 2px $white;padding:15px;padding-left:30px;font-size:25px; font-weight:bold;color:$preference'>Preferences</td></tr>
</table>
";
}

if ($accid == md5("") or $break == 1) {
  //  <li><b style='color:#d42626'>TITLE</b><br>DESCRIPTION</li><p>
  //  <li><b>TITLE</b><br>DESCRIPTION</li><p>

    $release_list = "<b style='color:#d42626;'>THIS IS A 2-WEEK TRIAL PERIOD WITH MANY RE-UPDATES! THERE MAY BE BUGS!</b>";

    $content = "<div style='position:relative; margin-left:auto; margin-right:auto; margin-top:100px; width:500px; font-size:50px;text-align:center;text-shadow: 0px 0px 10px black;cursor:default'>

<div id='release' style='text-align:left;background-color:#070707;overflow-y:scroll;padding:5px;padding-left:0px;padding-top:100px;width:330px; height:80%; top:3px; left:3px; position:fixed;font-size:15px;border:2px solid white; border-radius:8px;opacity:0;'>

<ul style='margin-bottom:50px;'>

$release_list

</ul>

<img src='../programm_files/jcobs-engine_white.png' style='position:relative; margin-bottom:50px;width:200px; left:50px'>


</div>



<div style='background-color:#070707;width:300px;position:fixed; font-size:22px; top:5px; left:15px; height:80px;box-shadow:0px 10px 10px 05px #070707;'></div>

<div id='v'

onclick=\"
this.style.animation='startm 0.7s'; this.style.animationFillMode='forwards'; release.style.animation='getin 1.5s';  release.style.animationFillMode='forwards'
\"

style='background-color:#070707;position:fixed; cursor:help;font-size:22px; top:10px; left:15px;'>Version:
<b>v<span style='color:white'>0.0.0</span></b></div>

<img src='../programm_files/openserv.png' style='height:80px;background-color:$white; padding:30px; border-radius:10px'>

<input type='submit' name='newid' value='New Account' onclick=\"accid.value='';\" style='position:fixed; bottom:0px; right:0px; font-size:25px; border:0px;font-weight:bold; border-radius:10px 0px 0px 0px;cursor:pointer; background-color:$white; color:$black; padding:5px;padding-left:20px;padding-top:10px;padding-right:10px'>

<p>
<input type='$type' onkeypress=\"let n=this.value.length + 1; if(n === 9){ this.style.backgroundColor='#57ff5f';sendid.focus(); }\" maxlength='9' name='accid' id='accid' placeholder='Account-iD' style='width:400px; border:0px; font-size:25px; padding:10px;padding-left:30px; border-radius:5px;font-weight:bold;box-shadow:0px 0px 3px 3px #1d1d1d, 0px 0px 10px 4px black;' value='$newids' autocomplete='off'><input type='button' id='sendid' autofocus style='color:$black;width:100px; text-align:center; border:0px; background-color:$white; font-size:25px;padding:5px;border-radius:5px;cursor:pointer;box-shadow:0px 0px 0px 5px $white, 0px 0px 3px 8px #1d1d1d, 0px 0px 10px 9px black' value='send' onclick=\"accid.type='password'; setTimeout(function(){ document.save.submit(); }, 500);\" ><div style='position:relative; height:0px'></div>


<iframe width='560' height='315' style='position:relative;left:calc(50% - 280px); border-radius:5px;box-shadow:0px 0px 2px 3px white;' src='https://www.youtube-nocookie.com/embed/vIrZb3VA7Qg?controls=0' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>
<a style='background-color:#070707;position:fixed; bottom:3px; left:3px;font-size:18px;padding:5px; border:2px solid white;border-radius:10px; text-decoration:none;color:white;cursor:pointer;' onclick=\"kontakt.style.display='block';\">Contact</a>
<a style='background-color:#070707;position:fixed; bottom:3px; left:100px;font-size:18px;padding:5px; border:2px solid white;border-radius:10px; text-decoration:none;color:white;cursor:pointer' href='impressum.pdf' download>Impressum</a>

<div style='color:black;background-color:rgba(100%, 100%, 100%, 0.8);padding:20px; position:fixed; bottom:50px; left:3px; font-size:20px;text-align:left;text-shadow:0px 0px 0px black;border-radius:10px;display:none;' id='kontakt' onmouseout=\"this.style.display='none'\"><b>Contact</b><hr>Jcobs Engine AG<br>Zempiner Weg 2<br>25336 Klein Nordende</div>
";
}

$content .= "<datalist id='user'>";
$sql = "select fname from user where (fname!='anonymous' and fname!='SYSTEM');";
$ask = mdq($bindung, $sql);
while ($row = mysqli_fetch_row($ask)) {
    $listname = $row[0];
    $content .= "<option value='$listname'>";
}
$content .= "</datalist>";

echo "
<html>
<head>
<title>OpenChat | Messenger</title>
<meta name='description' content='A free and secure instant messenger.'>
<meta name='keywords' content='OpenChat, Chat, secure, free, messenger,
sicher, Nachrichten, chatten, freunde'>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>


<script>

$('#body').ready(
function(){
$('#onlana').load('getonline.php?accid=$id');
}
);

$(document).on('keypress',function(e) {
    if(e.which == 13) {
    	 var er = escape( $('#inputmes').val() );
	 var er = encodeURIComponent(er);
	 $('#inputmes').val('');
    	 $('#nothing').load('insert.php?accid=$id&chatid=$showchat&roomkey=$keychat&text=' + er);
        
}
});


setTimeout(function(){
    $('#scrolla').val('1');
}, 0);



setInterval(function(){
if($('#scrolla').val() == 1){
if($('#scrolli').val() == 1){
$('#chat').animate({scrollTop:$('#table').height()}, 400);
$('#chat').load('getchat.php?chatid=$showchat&accid=$id&roomkey=$keychat');
}
$('#onlana').load('getonline.php?accid=$id');
}
}, 1000);

</script>


<script type='text/javascript'>
function stopRKey(evt) {
  var evt = (evt) ? evt : ((event) ? event : null);
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
  if ((evt.keyCode == 13) && (node.type=='text'))  {return false;}
}

document.onkeypress = stopRKey; 

function scroll(){
var objDiv = document.getElementById('chat');
objDiv.scrollTop = objDiv.scrollHeight;
}

function sethide(){
var x = document.getElementsByClassName('contentis');
var i;
for (i = 0; i < x.length; i++) {
  x[i].style.display = 'none';
}
}

function setblock( a ){
var x = document.getElementsByClassName(a);
var i;
for (i = 0; i < x.length; i++) {
  x[i].style.display = 'block';
}
}

function setnone( a ){
var x = document.getElementsByClassName(a);
var i;
for (i = 0; i < x.length; i++) {
  x[i].style.display = 'none';
}
}


function setback(){
var x = document.getElementsByClassName('messages');
var i;
for (i = 0; i < x.length; i++) {
  x[i].style.color = '$white';
  x[i].style.backgroundColor = '$black';
}
}

function autoscrolll(){
if(scrolli.value == '1'){
chat.style.animation='pni 0.5s';
chat.style.animationFillMode='forwards';
inputmes.style.animation='pni 0.5s';
inputmes.style.animationFillMode='forwards';

inputmesa.style.animation='pni 0.5s';
inputmesa.style.animationFillMode='forwards';

scrolli.value='0';

setTimeout(function(){

autoscrol.style.border='3px solid #c10b0b';

chat.style.overflowY='scroll';

tabletwo.style.display='block';
tableone.style.display='none';



var x = document.getElementsByClassName('time');
var i;
for (i = 0; i < x.length; i++) {
  x[i].style.display = 'block';
}

var x = document.getElementsByClassName('lines');
var i;
for (i = 0; i < x.length; i++) {
  x[i].style.opacity = '1';
}


}, 500);

setTimeout(function(){
var objDiv = document.getElementById('chat');
objDiv.scrollTop = objDiv.scrollHeight;

chat.style.animation='inp 0.5s';
chat.style.animationFillMode='forwards';
}, 750);

}
else
{
chat.style.animation='pni 0.5s';
chat.style.animationFillMode='forwards';

scrolli.value='1';

setTimeout(function(){



autoscrol.style.border='3px solid #238c0e';
chat.style.overflowY='hidden';

var x = document.getElementsByClassName('time');
var i;
for (i = 0; i < x.length; i++) {
  x[i].style.display = 'none';
}

var x = document.getElementsByClassName('lines');
var i;
for (i = 0; i < x.length; i++) {
  x[i].style.opacity = '0';
}

}, 500);

setTimeout(function(){
var objDiv = document.getElementById('chat');
objDiv.scrollTop = objDiv.scrollHeight;

chat.style.animation='inp 0.5s';
chat.style.animationFillMode='forwards';

inputmes.style.animation='inp 0.5s';
inputmes.style.animationFillMode='forwards';

inputmesa.style.animation='inp 0.5s';
inputmesa.style.animationFillMode='forwards';

}, 750);
}
}

</script>

<style type='text/css'>
#gibkey {
    color: transparent;
    text-shadow: 0 0 0 black;

    &:focus {
        outline:none;
    }
}

@keyframes enable{
0%{
filter:grayscale(1);
}
100%{
filter:grayscale(0);
}
}

@keyframes anable{
0%{
filter:grayscale(1);
background-color:transparent;
box-shadow:0px 0px 0px transparent;
text-shadow:0px 0px 0px #b99d39;
color:#b99d39;
}
50%{
filter:grayscale(0.5);
background-color:#b99d39;
box-shadow:0px 0px 5px 2000px #b99d39;
text-shadow:0px 0px 5px white;
color:white;
}
100%{
filter:grayscale(0);
background-color:transparent;
box-shadow:0px 0px 0px transparent;
text-shadow:0px 0px 0px blue;
color:#b99d39;
opacity:1;
}
}

@keyframes hops{
0%{
opacity:0.4;
}
100%{
opacity:1;
}
}

@keyframes hopsz{
0%{
opacity:1;
}
100%{
opacity:0.4;
}
}


@keyframes getin{
0%{
height:100px;
opacity:0;
}
20%{
height:100px;
opacity:1;
}
25%{
height:100px;
opacity:1;
}
100%{
height:60%;
opacity:1;
}
}

@keyframes startm{
0%{
top:10px;
left:15px;
font-size:22px;
}
100%{
top:40px;
left:60px;
font-size:30px;
}
}

@keyframes stopm{
0%{
top:40px;
left:60px;
font-size:30px;
}
40%{
top:10px;
left:60px;
font-size:30px;
}
100%{
top:10px;
left:15px;
font-size:22px;
}

}

@keyframes blendout{
0%{
transform:scale(1);
background-color:rgba(0%, 0%, 0%, 0);
color:rgba(100%, 100%, 100%, 0);
}
15%{
background-color:rgba(0%, 0%, 0%, 0.9);
transform:scale(1);
}
60%{
opacity:1;
color:rgba(100%, 100%, 100%, 1);
transform:scale(1.1);
}
98%{
color:white;
opacity:0;
}
100%{
color:white;
transform:scale(2);
opacity:0;
}
}

@keyframes sm{
0%{
left:40%;
}
100%{
left:calc(80% + 40px);
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


@keyframes pni{
0%{
opacity:1;
}
100%{
opacity:0;
}
}


@keyframes block{
0%{
background-color:$white;
}
50%{
background-color:#ff0000;
}
100%{
background-color:$white;
}
}

@keyframes free{
0%{
background-color:$white;
}
50%{
background-color:#00ff00;
}
100%{
background-color:$white;
}
}

@keyframes blink{
0%{
background-color:transparent;
}
10%{
background-color:$white;
}
18%{
background-color:transparent;
}
100%{
background-color:$white;
}
}

@keyframes grey{
0%{ opacity:0; }
30%{ opacity:0.5; }
40%{ opacity:0.3; }
100%{ opacity:1; }
}

@keyframes color{
0%{ opacity:0; filter:grayscale(1);}
30%{ opacity:0.5; filter:grayscale(0);}
80%{ opacity:0.4; filter:grayscale(0.7);}
100%{ opacity:1; filter:grayscale(0);}
}

@keyframes serv{
0%{ left:100%; }
20%{ left:50%; }
70%{ left:60%; }
100%{ left:40%; }
}

</style>
</head>";
echo "
<body id='body' onload=\"setTimeout(function(){ access.style.display='none'; }, 1110)\" style='background-color:$black; color:$white; font-size:18px;font-family:sans-serif'>
<form name='save' method='post' action=''>

$content";

if ($title != "anonymous") {
    echo "<input type='hidden' name='site' id='site' value='$site'>";
}

/// !SYSMESSAGES!
/// If Re-Release this part, update it, for End-to-End Encryption!
//if($join != $_POST['join'])
//{
//$lastjoin=$_POST['join'];
//if($join == 0){
//$text="%23255$title%23256%20leaves%20the%20room.";
//$_buffer = implode('', file('http://'."$pfad".'insert.php?accid=7cbff9f534bf023c49c773f3fdd33ba7&chatid='."$lastjoin".'&text='."$text"));
//}
//else{
//$text="%23245$title%23246%20joins%20the%20room.";
//$_buffer = implode('', file('http://'."$pfad".'insert.php?accid=7cbff9f534bf023c49c773f3fdd33ba7&chatid='."$join".'&text='."$text"));
//}

//}

//ACCES-MESSAGES//

if ($access != "") {
    echo "<div id='access' style='animation:blendout 1.1s ease-out; animation-fill-mode:forwards;position:fixed; text-shadow:0px 0px 3px white;font-weight:bold; font-size:35px;top:0px; left:0px; width:100%; height:100%; background-color:rgba(0%, 0%, 0%, 0.9);z-index:10000000; text-align:center;color:#d1d1d1'>
<div style='position:relative; cursor:default;top:calc(50% - 28px);'>$access</div>
</div>";
}

///[END]ACCESS MESSAGES//

echo "
<input type='hidden' value='1' id='scrolli' name='scroll'>
<input type='hidden' value='' id='rmfile' name='rmfile'>
<input type='hidden' value='0' id='scrolla' name='scrolla'>
<input type='hidden' value='$join' id='join' name='join'>
<input type='hidden' name='delete' id='deleta' value='0'>
<input type='hidden' name='deleti' id='deleti' value='0'>
$locki
</form>

<form name='upload' action='upload.php' method='POST' target='_blank'>
<input type='hidden' name='accid' value='$id'>
<input type='hidden' name='chatid' value='$showchat'>
<input type='hidden' name='personal_key' value='$personal_key'>
</form>

<form name='uplad' action='upload.php' method='POST' target='_blank'>
<input type='hidden' name='accid' value='$id'>
<input type='hidden' name='tipe' value='1'>
<input type='hidden' name='personal_key' value='$personal_key'>
</form>

</body>
</html>
";
?>
