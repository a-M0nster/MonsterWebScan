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

$currentDir = './';
require_once($currentDir.'functions/databaseFunctions.php');

isset($_POST['testId']) ? $testId = $_POST['testId'] : $testId = 0;

$query = 'SELECT * FROM test_results WHERE test_id = '.$testId;
connectToDb($db);
$result = $db->query($query);
if ($result) {
    $numRows = $result->num_rows;
    if ($numRows > 0) {
        echo '<b>找到的漏洞:</b>';

        for ($i = 0; $i < $numRows; $i++) {
            $row = $result->fetch_object();
            $type = $row->type;
            $method = strtoupper($row->method);
            $url = $row->url;
            $info = $row->attack_str;

            if ($type == 'rxss') {
                $type = 'Reflected Cross-Site Scripting';
                $info = 'Query Used: '.$info;
            } else if ($type == 'sxss') {
                $type = 'Stored Cross-Site Scripting';
                $info = 'Query Used: '.$info;
            } else if ($type == 'sqli') {
                $type = 'SQL Injection';
                $info = 'Query Used: '.$info;
            } else if ($type == 'idor') {
                $type = '(Potentially Insecure) Direct Object Reference';
                $info = 'Object Referenced: '.$info;
            } else if ($type == 'basqli') {
                $type = 'Broken Authentication using SQL Injection';
                $info = 'Query Used: '.$info;
            } else if ($type == 'unredir') {
                $type = 'Unvalidated Redirects';
                $info = 'URL Requested: '.$info;
            } else if ($type == 'dirlist') {
                $type = 'Directory Listing enabled';
                $info = 'URL Requested: '.$info;
            } else if ($type == 'bannerdis') {
                $type = 'HTTP Banner Disclosure';
                $info = 'Information Exposed: '.$info;
            } else if ($type == 'autoc') {
                $type = 'Autocomplete not disabled on password input field';
                $info = 'Input Name: '.$info;
            } else if ($type == 'sslcert') {
                $type = 'SSL certificate is not trusted';
                $info = 'URL Requested: '.$info;
            }

            echo "<p><b>$type</b><br>";
            $urlHtml = htmlspecialchars($url);
            echo "$method $urlHtml<br>";
            $infoHtml = htmlspecialchars($info);
            echo "$infoHtml</p>";
        }
        $result->free();
        $db->close();
    } else {
        echo '<b>没有漏洞被发现。</b>';
    }
}
?>