<?php 
/////////////////////////////////////////////////////////////////////////////
// MonsterWebscan
// - web应用漏洞扫描软件
//
// 这个程序是一个免费软件，可以免费使用
//
//
// 这个程序使用了以下开源项目：
// - PHPCrawl(http://phpcrawl.cuab.de/)
// - PHP HTTP Protocol Client(http://www.phpclasses.org/package/3-PHP-HTTP-client-to-access-Web-site-pages.html)
// - PHP Simple HTML DOM Parser (http://simplehtmldom.sourceforge.net/)
// - TCPDF(http://www.tcpdf.org/)
// - jQuery(http://jquery.com/)
// - Calliope(http://www.towfiqi.com/xhtml-template-calliope.html)
/////////////////////////////////////////////////////////////////////////////

session_start();
$currentDir = './';
require_once($currentDir.'scanner/functions/databaseFunctions.php');
?>
<!DOCTYPE html>

<head>
    <title>MonsterWebScan</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/favicon.gif" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="js/swfobject/swfobject.js"></script>
    <script type="text/javascript">
        var flashvars = {};
        flashvars.xml = "config.xml";
        flashvars.font = "font.swf";
        var attributes = {};
        attributes.wmode = "transparent";
        attributes.id = "slider";
        swfobject.embedSWF("cu3er.swf", "cu3er-container", "960", "270", "9", "expressInstall.swf", flashvars, attributes);
    </script>
    <script type="text/javascript" src="jquery-1.6.4.js"></script>
</head>

<body>
    <!--Header Begin-->
    <div id="header">
        <div class="center">
            <div id="logo"><a href="#">MonsterWebScan</a></div>
            <!--Menu Begin-->
            <div id="menu">
                <?php require_once($currentDir.'session_control.php'); ?>
            </div>
            <div id="menu">
                <ul>
                    <li><a class="active" href="index.php"><span>主页</span></a></li>
                    <li><a href="about.php"><span>关于  </span></a></li>
                    <li><a href="crawler.php"><span>爬虫系统</span></a></li>
                    <li><a href="scanner.php"><span>扫描系统</span></a></li>
                    <li><a href="history.php"><span>扫描历史</span></a></li>
                </ul>
            </div>
            <!--Menu END-->
        </div>
    </div>
    <!--Header END-->
    <!--Toprow Begin-->
    <div id="toprow">
        <div class="center">
            <div id="cubershadow">
                <div id="cu3er-container">
                    <a href="http://www.adobe.com/go/getflashplayer"> <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="" /> </a>
                </div>
            </div>
        </div>
    </div>
    <!--Toprow END-->

    <!--BottomRow Begin-->
    <div id="bottomrow">
        <div class="textbox">
            <h1>MonsterWebScan - web应用漏洞扫描</h1>
            <p>WebVulScan首先对目标网站的URL进行爬取，以确定属于该网站的所有URL。它对生成的URL探测许多漏洞。一旦扫描完成，会将扫描结果的详细结果以PDF的形式发送至您的邮箱。</p>
        </div>
    </div>
    <!--BottomRow END-->
    <!--Footer Begin-->
    <div id="footer">
        <div class="foot"> <span>powered </span> by <a href="http://www.dlnu.edu.cn">LanXing</a>&nbsp;&nbsp;&nbsp;&nbsp;制作于2016年</div>
    </div>
    <!--Footer END-->
</body>

</html>