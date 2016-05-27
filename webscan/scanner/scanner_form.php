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

?>

<script type="text/javascript">
    function beginScan(value, valueTwo, valueThree, valueFour, valueFive) {
        jQuery.post("scanner/begin_scan.php", {
            specifiedUrl: value,
            testId: valueTwo,
            username: valueThree,
            email: valueFour,
            testCases: valueFive
        });
    }


    function sizeTbl(h) {
        var tbl = document.getElementById('tbl');
        tbl.style.display = h;
    }

    checked = true;

    function checkedAll(form1) {
        var aa = document.getElementById('form1');
        if (checked == true) {
            checked = false
        } else {
            checked = true
        }
        for (var i = 0; i < aa.elements.length; i++) {
            aa.elements[i].checked = checked;
        }
    }
</script>

<?php 

require_once('functions/databaseFunctions.php');
require_once('classes/Logger.php');

if (isset($_SESSION['username'])) {
    //Get the user's username and email address
    $username = $_SESSION['username'];

    if (isset($_SESSION['email'])) $email = $_SESSION['email'];
    else $email = ''; //maybe email to administrator
    ?>

<body>
    <form id="form1" name="form1" method="post">
        <p>请输入URL:</p>
        <p>
            <label for="urlToScan"></label>
            <input type="text" size="40" name="urlToScan" id="urlToScan" />
            <br>
            <a href="javascript:sizeTbl('block')"><font size="3">选项</font></a></p>
        <div id=tbl name=tbl style="overflow:hidden;display:none">
            <a href="javascript:checkedAll(form1)"><font size="3">全选/全不选</font></a>
            <br>
            <br> 请选择要测试的漏洞:
            <br>
            <table border="0">
                <tr>
                    <td>
                        <input type="checkbox" name="rxss" value="rxss" checked />
                    </td>
                    <td>反射式跨站脚本</td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="sxss" value="sxss" checked />
                    </td>
                    <td>存储型跨站脚本</td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="sqli" value="sqli" checked />
                    </td>
                    <td>标准SQL注入</td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="basqli" value="basqli" checked />
                    </td>
                    <td>使用SQL注入失效的验证</td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="autoc" value="autoc" checked />
                    </td>
                    <td>敏感输入字段启用自动完成</td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="idor" value="idor" checked />
                    </td>
                    <td>（存在安全隐患）直接对象引用</td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="dirlist" value="dirlist" checked />
                    </td>
                    <td>目录列表存在</td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="bannerdis" value="bannerdis" checked />
                    </td>
                    <td>HTTP请求信息发现</td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="sslcert" value="sslcert" checked />
                    </td>
                    <td>SSL证书不信任</td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="unredir" value="unredir" checked />
                    </td>
                    <td>未经验证的重定向</td>
                </tr>
            </table>
            <br>
            <br>其它选项:
            <br>
            <table border="0">
                <tr>
                    <td>
                        <input type="checkbox" name="emailpdf" value="emailpdf" checked />
                    </td>
                    <td>发送PDF报告给email</td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="crawlurl" value="crawlurl" checked />
                    </td>
                    <td>爬取整个网站</td>
                </tr>
            </table>
        </div>
        <p>
            <input type="submit" class="button" name="submit" id="submit" value="开始扫描" />
        </p>
    </form>

    <?php 

if (isset($_POST['urlToScan'])) {
    $testCases = ' '; //options
    if (isset($_POST['rxss'])) $testCases .= $_POST['rxss'].' ';
    if (isset($_POST['sxss'])) $testCases .= $_POST['sxss'].' ';
    if (isset($_POST['sqli'])) $testCases .= $_POST['sqli'].' ';
    if (isset($_POST['basqli'])) $testCases .= $_POST['basqli'].' ';
    if (isset($_POST['autoc'])) $testCases .= $_POST['autoc'].' ';
    if (isset($_POST['idor'])) $testCases .= $_POST['idor'].' ';
    if (isset($_POST['dirlist'])) $testCases .= $_POST['dirlist'].' ';
    if (isset($_POST['bannerdis'])) $testCases .= $_POST['bannerdis'].' ';
    if (isset($_POST['sslcert'])) $testCases .= $_POST['sslcert'].' ';
    if (isset($_POST['unredir'])) $testCases .= $_POST['unredir'].' ';
    if (isset($_POST['emailpdf'])) $testCases .= $_POST['emailpdf'].' ';
    if (isset($_POST['crawlurl'])) $testCases .= $_POST['crawlurl'].' ';

    $urlToScan = trim($_POST['urlToScan']);
    if (!empty($urlToScan)) {
        $log = new Logger();
        $log->lfile('scanner/logs/eventlogs');

        $log->lwrite('Connecting to database');

        $connectionFlag = connectToDb($db);

        if (!$connectionFlag) {
            $log->lwrite('Error connecting to database');
            echo 'Error connecting to database';
            return;
        }

        $log->lwrite('Generating next test ID');
        $nextId = generateNextTestId($db);

        if (!$nextId) {
            $log->lwrite('Next ID generated is null');
            echo 'Next ID generated is null';
            return;
        } else {
            $log->lwrite("Next ID generated is $nextId");
            $testId = $nextId;
            $now = time();
            $query = "INSERT into tests(id,status,numUrlsFound,type,num_requests_sent,start_timestamp,finish_timestamp,scan_finished,url,username,urls_found) VALUES($nextId,'Creating profile for new scan...',0,'scan',0,$now,$now,0,'$urlToScan','$username','')";
            $result = $db->query($query);
            if (!$result) {
                $log->lwrite("Problem executing query: $query ");
                echo '插入一个新的测试到数据库中出现问题。 请再试一次.';
                return;
            } else {
                $log->lwrite("Successfully executed query: $query ");
            }
        }

        updateStatus($db, '挂起...', $testId);

        $query = "UPDATE tests SET numUrlsFound = 0 WHERE id = $testId;";
        $db->query($query);
        $query = "UPDATE tests SET duration = 0 WHERE id = $testId;";
        $db->query($query);

        echo '<script type="text/javascript">
				$(document).ready(function() {
				 $.post("scanner/getStatus.php", {testId:'."$testId".'}, function(data){$("#status").html(data)});
			   var refreshId = setInterval(function() {
				  $.post("scanner/getStatus.php", {testId:'."$testId".'}, function(data){$("#status").html(data)});
			   }, 500);
			   $.ajaxSetup({ cache: false });
				});</script>';

        echo '<script type="text/javascript">
				$(document).ready(function() {
				 $.post("scanner/getVulnerabilities.php", {testId:'."$testId".'}, function(data){$("#scanstatus").html(data)});
			   var refreshId = setInterval(function() {
				  $.post("scanner/getVulnerabilities.php", {testId:'."$testId".'}, function(data){$("#scanstatus").html(data)});
			   }, 1000);
			   $.ajaxSetup({ cache: false });
				});</script>';

        $urlToScan = $_POST['urlToScan'];

        $log->lwrite('Calling AJAX function beginCrawl()');
        echo '<script type="text/javascript">';
        echo "beginScan('$urlToScan','$testId','$username','$email', '$testCases');";
        echo '</script>';

    } else echo '错误：没有输入网址';
}

echo '<div id="status"></div><br>';
echo '<div id="scanstatus"></div><br>';
} else echo '您没有登录. 请您尝试登录.';
?>