<?php
include 'pdo.php';
$a = '';
    if (isset($_POST[ 'submit']) && $_POST['submit'] ){
        $username = $_POST['username'];
        $userpass = $_POST['userpass'];
        $userpass2 = $_POST['userpass2'];
        $username = htmlspecialchars($username);
        $userpass = htmlspecialchars($userpass);
        $sql = "select * from userinfo where username=?";
        $stmt = $dblj->prepare($sql);
        $stmt->execute(array($username));
        $stmt->bindColumn('username',$cxusername);
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);

        if($userpass2 != $userpass){
            $a = '两次输入密码不一致';
			echo $a;
        }elseif (strlen($username) < 6 or strlen($userpass)< 6){
            $a = '账号或密码长度请大于或等于6位';
			echo $a;
        }elseif ($ret){
            $a = '注册失败,账号'.$cxusername.'已经存在';
            echo $a;
        }else{
            $token = md5("$username.$userpass".strtotime(date('Y-m-d H:i:s')));
            $sql = "insert into userinfo(username,userpass,token) values('$username','$userpass','$token')";
            $cxjg = $dblj->exec($sql);
            $a = '注册成功';
            echo $a;
            header("refresh:1;url=index.php");
        }
    }

    ?>
<html lang="en">
<head>
    <meta charset="utf-8" content="width=device-width,user-scalable=no" name="viewport">
    <title>寻仙纪</title>

    <link rel="stylesheet" href="css/gamecss.css">
</head>
<body>
<div class="main">
<img src="images/11.jpg" width="300" height="200">
<div id="mainfont">
		<p>天下风云出我辈，一入江湖岁月催</p>
		<p>皇图霸业谈笑中，不胜人生一场醉</p>
		<p>提剑跨骑挥鬼雨，白骨如山鸟惊飞</p>
		<p>尘事如潮人如水，只叹江湖几人回</p>
</div>
<div class="login">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="text" name="username" placeholder="帐号" class="input">
	<input type="password" name="userpass" placeholder="密码" class="input">
	<input type="password" name="userpass2" placeholder="确认密码" class="input">
	<br>
	<p><input type="submit" name="submit" value="注册" class="btn-login"> <a href="index.php" id="btn">登录</a></p>
</form>
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
</footer> <!---* 中国源码网：zgymw.com--->
</div>
</body>
</html>