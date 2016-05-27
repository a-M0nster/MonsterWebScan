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
                    <li><a href="index.php"><span>主页</span></a></li>
                    <li><a class="active" href="about.php"><span>关于</span></a></li>
                    <li><a href="crawler.php"><span>爬虫系统</span></a></li>
                    <li><a href="scanner.php"><span>扫描系统</span></a></li>
                    <li><a href="history.php"><span>扫描历史</span></a></li>
                </ul>
            </div>
            <!--Menu END-->
        </div>
    </div>
    <!--Header END-->
    <!--SubPage Toprow Begin-->
    <div id="toprowsub">
        <div class="center">
            <h2>关于</h2>
        </div>
    </div>
    <!--Toprow END-->
    <!--SubPage MiddleRow Begin-->
    <div id="midrow">
        <div class="center">
            <div class="textbox2">
                <p>本程序由兰星开发.该程序主要用于自己使用和毕业设计.</p>
                <p>本扫描器的最终目标是建立一个快速的web漏洞检测工具，同时将结果通过良好的UI展现出来。本web工具将为安全测试人员提供一定的帮助，提高网站的安全性。本工具可能会对网站造成破坏，请您测试前获得授权。未经授权测试网站是违法的。</p>
            </div>
        </div>
    </div>
    <!--MiddleRow END-->

    <!--Footer Begin-->
    <div id="footer">
        <div class="foot"> <span>powered </span> by <a href="http://www.dlnu.edu.cn">LanXing</a>&nbsp;&nbsp;&nbsp;&nbsp;制作于2016年</div>
    </div>
    <!--Footer END-->
</body>

</html>