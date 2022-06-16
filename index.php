<?php
include 'pdo.php';
require_once 'class/encode.php';

//header('Access-Control-Allow-Origin:*');

$encode = new \encode\encode();
session_start();
$a = '';
$username = $_SESSION['username'];
$userpass = $_SESSION['userpass'];

if (isset($_POST[ 'submit']) && $_POST['submit']){

    $username = $_POST['username'];
    $userpass = $_POST['userpass'];
    $username = htmlspecialchars($username);
    $userpass = htmlspecialchars($userpass);
    $sql = "select * from userinfo where username = ? and userpass = ?";
    $stmt = $dblj->prepare($sql);
    $bool = $stmt->execute(array($username,$userpass));
    $stmt->bindColumn('username',$cxusername);
    $stmt->bindColumn('userpass',$cxuserpass);
    $stmt->bindColumn('token',$cxtoken);
    $exeres = $stmt->fetch(PDO::FETCH_ASSOC);

    if ((strlen($username) < 6 || strlen($userpass) < 6) && !$exeres){
        $a = '账号或密码错误';
    }elseif ($cxusername == $username && $cxuserpass == $userpass){

        $sql = "select * from game1 where token='$cxtoken'";
        $cxjg = $dblj->query($sql);
        $cxjg->bindColumn('sid',$sid);
        $cxjg->fetch(PDO::FETCH_ASSOC);
        if ($sid==null){
            $cmd = "cmd=cj&token=$cxtoken";
        }else{
            $cmd = "cmd=login&sid=$sid";
            $nowdate = date('Y-m-d H:i:s');

            $sql = "update game1 set endtime = '$nowdate',sfzx=1 WHERE sid=?";
            $stmt = $dblj->prepare($sql);
            $stmt->execute(array($sid));
        }
        $cmd = $encode->encode($cmd);
		$_SESSION['username']=$username;
        $_SESSION['userpass']=$userpass;
        header("refresh:1;url=game.php?cmd=$cmd");
    }
}
?>
<html lang="en">
<head>
    <meta charset="utf-8" content="width=device-width,user-scalable=no" name="viewport" />
    <title>寻仙纪</title>
    <link rel="stylesheet" href="css/gamecss.css">
</head>
<body>
<div class="main">
<img src="images/11.jpg" width="300" height="200"><br/>
<div id="mainfont">
月冷千山江自碧，冰崖万丈无留意。<br/>
寻道只影莲花落，竹音寥落听新曲。<br/>
仙人听谁醉明月，踏浪踏风随燕去。<br/>
纪纲人伦心如桑，一醉红尘消百绪。<br/>
魔前叩首三千年，回首红尘不做仙。<br/>
</div>
<div class="login">
<form action=<?php echo $_SERVER['PHP_SELF']; ?> method="post">
    
    <input type="text" name="username" placeholder="帐号" class="input" value="<?php if(!empty($username)) echo $username; ?>"/><br/>

    <input type="password" name="userpass" placeholder="密码" class="input" value="<?php if(!empty($userpass)) echo $userpass; ?>"/><br/>
    <?php echo $a ?>
    <p><input type="submit" name="submit" class="btn-login" value="登录"/> <a href="reguser.php" id="btn">注册</a></p>
</form>
</div>
<div class="fix">
    <h2>寻仙纪小公告:</h2>
	<p>更新错别字</p>
	<p>修复武器可以穿戴全身的bug</p>
    <p>新增新地图人族领地“天帝城”</p>
    <p>新增天帝城“npc ”</p>
    <p>新增天帝城“日常任务 ”</p>
    <p>新增护甲“须弥之甲”</p>
	<p>新增物品“龙鳞”</p>
	<p>新增怪物“蛟龙”</p>
    <br>
    <button type="button" onclick="window.location.href='log.php'">查看更多日志</button>
</div>
</div>
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
</footer>
</div>
</body>
</html>