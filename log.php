<?php

?>
<html lang="en">
<head>
    <meta charset="utf-8" content="width=device-width,user-scalable=no" name="viewport" />
    <title>寻仙纪修复日志</title>
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
    <div class="fix">
	<h2>1.0版本更新日志:</h2>
	<p>热更新修复文字错误，修复bug</p>
    <p>修复不同玩家创建门派的bug</p>
    <p>NPC功能修复正常,可治疗,购买药品,兑换技能</p>
    <p>离线时间由原先的10分钟调至15分钟</p>
    <p>增加了bug提交和查看的功能</p>
    <p>优化了返回上一页和返回首页并排显示</p>
    <p>修复了门派功能没有下角时间的bug</p>
    <p>优化了聊天显示的字体大小</p>
    <p>登录界面增加了查看更多日志按钮</p>
    <p>修复了怪物身上显示的药品白板问题</p>
    <p>打怪经验调整，越级有5点加成</p>
    <p>聊天取消刷新，且不允许发送空白</p>
    <p>修复昵称可重复的bug</p>
	<h2>待修复内容:</h2>
	<p>1.任务页面调整(规划中)</p>
	<p>2.宠物出战不生效问题</p>
	<p>3.其余未知问题</p>
	<p>4.有计划调整装备套装(看规划情况和技术能力,不一定能添加,不要抱太大希望)</p>
	<p>5.单怪物自动攻击(看技术能力,不一定能添加,不要抱太大希望)</p>
        <br>
        <button type="button" onclick="window.location.href='./'">返回首页</button>
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
