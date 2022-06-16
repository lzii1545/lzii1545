<?php
//error_reporting(0);
require_once 'class/player.php';
require_once 'class/encode.php';
include_once 'pdo.php';

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$encode = new \encode\encode();
$player = new \player\player();
$guaiwu = new \player\guaiwu();
$clmid = new \player\clmid();
$npc = new \player\npc();

$ym = 'game/nowmid.php';
$Dcmd = $_SERVER['QUERY_STRING'];
$pvpts ='';
$tpts = '';
session_start();
//$allow_sep = "220";
//function getMillisecond() {
//    list($t1, $t2) = explode(' ', microtime());
//    return (float)sprintf('%.0f',(floatval($t1) + floatval($t2)) * 1000);
//}
//if (isset($_SESSION["post_sep"]))
//{
//
//    if (getMillisecond() - $_SESSION["post_sep"] < $allow_sep)
//    {
//
//        $msg = '<meta charset="utf-8" content="width=device-width,user-scalable=no" name="viewport">你点击太快了^_^!<br/><a href="?'.$Dcmd.'">继续</a>';
//        exit($msg);
//    }
//    else
//    {
//        $_SESSION["post_sep"] = getMillisecond();
//    }
//}
//else
//{
//    $_SESSION["post_sep"] = getMillisecond();
//}

parse_str($Dcmd);
if (isset($cmd)){

    if ($cmd == 'cjplayer'){
        $Dcmd = $encode->encode($Dcmd);
        header("refresh:1;url=?cmd=$Dcmd");
        exit();
    }
    if ($cmd == 'djinfo'){
        $Dcmd = $encode->encode($Dcmd);
        header("refresh:1;url=?cmd=$Dcmd");
        exit();
    }
    if ($cmd == 'zbinfo'){
        $Dcmd = $encode->encode($Dcmd);
        header("refresh:1;url=?cmd=$Dcmd");
        exit();
    }
    if ($cmd == 'npc'){
        $Dcmd = $encode->encode($Dcmd);
        header("refresh:1;url=?cmd=$Dcmd");
        exit();
    }
    if ($cmd == 'duihuan'){
        $Dcmd = $encode->encode($Dcmd);
        header("refresh:1;url=?cmd=$Dcmd");
        exit();
    }
    if ($cmd == 'sendliaotian'){
        $Dcmd = $encode->encode($Dcmd);
        //$ym = 'game/liaotian.php';
        //header("refresh:1;url=?cmd=$Dcmd");
        //exit();
    }
    $Dcmd = $encode->decode($cmd);
//    var_dump($Dcmd);
    parse_str($Dcmd);
    switch ($cmd){
        case 'cj':
            $ym = 'game/cj.php';
            break;
        case 'login';
            $player = \player\getplayer($sid,$dblj);
            $gonowmid = $encode->encode("cmd=gomid&newmid=$player->nowmid&sid=$sid");
            $nowdate = date('Y-m-d H:i:s');
            $sql = "update game1 set endtime='$nowdate',sfzx=1 WHERE sid='$sid'";
            $cxjg = $dblj->exec($sql);
            header("refresh:1;url=?cmd=$gonowmid");
            exit();
            break;
        case 'zhuangtai';
            $ym = 'game/zhuangtai.php';
            break;
        case 'cjplayer':

            if (isset($token) && isset($username) && isset($sex)){
                $username = htmlspecialchars($username);
                $sql = "SELECT * FROM game1 where uname = '".$username."'";//昵称查询
                $ltcxjg = $dblj->query($sql);
                if ($ltcxjg) {
                    $ret = $ltcxjg->fetchAll(PDO::FETCH_ASSOC);
                    if(count($ret)>0){
                        echo "该昵称已经被人使用过了！";
                        $ym = 'game/cj.php';
                        break;
                    }
                }
                if(strlen($username)<6 || strlen($username)>12){
                    echo "用户名不能太短或者太长";
                    $ym = 'game/cj.php';
                    break;
                }
                $sid = md5($username.$token.'229');
                $sql="select * from game1 where token='$token'";
                $cxjg = $dblj->query($sql);
                $cxjg->bindColumn('sid',$player->sid);
                $ret = $cxjg->fetch(PDO::FETCH_ASSOC);
                $nowdate = date('Y-m-d H:i:s');
                if ($player->sid ==''){
                    $gameconfig = \player\getgameconfig($dblj);
                    $firstmid = $gameconfig->firstmid;
                    $sql = "insert into game1(token,sid,uname,ulv,uyxb,uczb,uexp,uhp,umaxhp,ugj,ufy,uwx,usex,vip,nowmid,endtime,sfzx) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $stmt = $dblj->prepare($sql);
                    $stmt->execute(array($token,$sid,$username,'1','2000','100','0','35','35','12','5','0',$sex,'0',$firstmid,$nowdate,1));

                    $gonowmid = $encode->encode("cmd=gomid&newmid=$gameconfig->firstmid&sid=$sid");
                    echo '<meta charset="utf-8" content="width=device-width,user-scalable=no" name="viewport">';
                    echo $username."欢迎来到寻仙纪";
                    $sql = "insert into ggliaotian(name,msg,uid) values(?,?,?)";
                    $stmt = $dblj->prepare($sql);
                    $stmt->execute(array('系统通知',"万中无一的{$username}踏上了仙途",'0'));
                    header("refresh:1;url=?cmd=$gonowmid");
                }
                exit();
            }
            break;
        case 'gomid':
            $ym = 'game/nowmid.php';
            break;
        case 'getginfo':
            $ym = 'game/ginfo.php';
            break;
        case 'pve':
            $ym = 'game/pve.php';
            break;
        case 'pvp':
            $ym = 'game/pvp.php';
            break;
        case 'pvegj':
            $ym = 'game/pve.php';
            break;
        case 'sendliaotian':
            if (isset($ltlx) && isset($ltmsg) && mb_strlen($ltmsg)>=2){
                switch ($ltlx){
                    case 'all':
                        $player = player\getplayer($sid,$dblj);
                        if ($player->uname!=''){
                            $ltmsg = htmlspecialchars($ltmsg);
                            $sql = "insert into ggliaotian(name,msg,uid) values(?,?,?)";
                            $stmt = $dblj->prepare($sql);
                            $exeres = $stmt->execute(array($player->uname,$ltmsg,$player->uid));
                        }
                        $ym = 'game/liaotian.php';
                        break;
                    case "im":
                        $player = player\getplayer($sid,$dblj);
                        if ($player->uname!=''){
                            $ltmsg = htmlspecialchars($ltmsg);
                            $sql = "insert into imliaotian(name,msg,uid,imuid) values('$player->uname','$ltmsg',$player->uid,{$imuid})";

                            $cxjg = $dblj->exec($sql);
                        }
                        $ym = 'game/liaotian.php';
                        break;
                }
            }
            elseif (isset($ltlx) && isset($ltmsg) && mb_strlen($ltmsg)<2){
                echo ('发送失败！聊天内容请保证2个数字以上');
            }
            break;
        case 'liaotian':
            $ym ='game/liaotian.php';
            break;
        case 'getplayerinfo':
            $ym ='game/otherzhuangtai.php';
            break;
        case 'getbuginfo':
            $ym ='game/buginfo.php';
            break;
        case 'zbinfo':
            $ym = 'game/zbinfo.php';
            break;
        case 'npc':
            $ym = "npc/npc.php";
            break;
        case 'paihang';
            $ym = 'game/paihang.php';
            break;
        case 'chakanzb':
            $ym = 'game/zbinfo.php';
            break;
        case 'djinfo':
            $ym = 'game/djinfo.php';
            break;
        case 'getbagzb':
            $ym = 'game/bagzb.php';
            break;
        case 'getbagyp':
            $ym = 'game/bagyp.php';
            break;
        case 'getbagjn':
            $ym = 'game/bagjn.php';
            break;
        case 'xxzb':
            $ym = 'game/zhuangtai.php';
            break;
        case 'setzbwz':
            $ym = 'game/zhuangtai.php';
            break;
        case 'allmap':
            $ym = 'game/allmap.php';
            break;
        case 'delezb':
            $ym = 'game/bagzb.php';
            break;
        case 'getbagdj':
            $ym = 'game/bagdj.php';
            break;
        case 'upzb':
            $ym = 'game/zbinfo.php';
            break;
        case 'goxiulian':
            $ym = 'game/xiulian.php';
            break;
        case 'startxiulian':
            $ym = 'game/xiulian.php';
            break;
        case 'endxiulian':
            $ym = 'game/xiulian.php';
            break;
        case 'task':
            $ym = 'game/task.php';
            break;
        case 'mytask':
            $ym = 'game/playertask.php';
            break;
        case 'mytaskinfo':
            $ym = 'game/playertaskinfo.php';
            break;
        case 'boss':
            $ym = 'game/bossinfo.php';
            break;
        case 'ypinfo':
            $ym = 'game/ypinfo.php';
            break;
        case 'pvb':
            $ym = 'game/boss.php';
            break;
        case 'chongwu':
            $ym = 'game/chongwu.php';
            break;
        case 'bugreport':
            $ym = 'game/bugreport.php';
            break;
        case 'jninfo':
            $ym = 'game/jninfo.php';
            break;
        case "zbinfo_sys":
            $ym = 'game/zbinfo_sys.php';
            break;
        case "tupo":
            $ym = 'game/tupo.php';
            break;
        case "fangshi":
            $ym = "game/fangshi.php";
            break;
        case "club":
            $ym = "game/club.php";
            break;
        case "clublist":
            $ym = "game/clublist.php";
            break;
        case "duihuan":
            $ym = "game/duihuan.php";
            break;
        case "im":
            $ym = "game/im.php";
            break;
    }
    if (!isset($sid) || $sid=='' ){

        if ($cmd!='cj' && $cmd!=='cjplayer'){
            header("refresh:1;url=index.php");
            exit();
        }
    }else{
        if ($cmd != 'pve' && $cmd!='pvegj'){
            $sql = "delete from midguaiwu where sid='$sid'";//删除地图该玩家已经被攻击怪物
            $dblj->exec($sql);
        }
        $player = \player\getplayer($sid,$dblj);
        if ($player->ispvp!=0){
            $pvper = \player\getplayer1($player->ispvp,$dblj);
            $pvpcmd = $encode->encode("cmd=pvp&uid=$pvper->uid&sid=$sid");
            $pvpcmd = "<a href='?cmd=$pvpcmd'>还击</a>";
            $pvpts = "$pvper->uname 正在攻击你：$pvpcmd<br/>";
        }
        if (\player\istupo($sid,$dblj) !=0 && $player->uexp >= $player->umaxexp){
            $tupocmd = $encode->encode("cmd=tupo&sid=$sid");
            $tupocmd = "<a href='?cmd=$tupocmd'>突破</a>";
            $tpts =  "你即将需要突破,否则将不能获得经验:$tupocmd<br/>";
        }
        $nowdate = date('Y-m-d H:i:s');
        $second=floor((strtotime($nowdate)-strtotime($player->endtime))%86400);//获取刷新间隔
        if ($second>=900){
            echo '<meta charset="utf-8" content="width=device-width,user-scalable=no" name="viewport">';
            echo $player->uname."离线时间过长，请重新登陆";
            header("refresh:1;url=index.php");
            exit();
        }else{
            $sql = "update game1 set endtime='$nowdate',sfzx=1 WHERE sid='$sid'";
            $dblj->exec($sql);
        }
    }
}else{
    header("refresh:1;url=index.php");
    exit();
}
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8" content="width=device-width,user-scalable=no" name="viewport">
    <title>寻仙纪</title>
    <link rel="stylesheet" href="css/gamecss.css">
    <link rel="icon" href="images/logo.ico" type="image/x-icon">
</head>
<body>
<div class="main">
<?php

    if (!$ym==''){
        echo $tpts;
        if ($ym!="game/pvp.php"){
            echo $pvpts;
        }

        include "$ym";
    }?>
</div>
</body>
<div class="footer">
<footer>
    <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
	<script>
	function changetime(){
	var ary = Array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
	var Timehtml = document.getElementById('CurrentTime');
	var date = new Date();
	Timehtml.innerHTML = ''+date.toLocaleString()+' '+ary[date.getDay()];
	}
	window.onload = function(){
	changetime();
	setInterval(changetime,1000);
	}
	</script>
	<div id="CurrentTime"><?php echo date('Y-m-d H:i:s') ?></div>
</footer><!-------中-国-源-码-网-w-w-w.-z-g-y-m-w-.-c-o-m---------->
</div>
</html>