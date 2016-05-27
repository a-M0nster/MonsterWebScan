<meta charset="utf-8">
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
//require_once('classes/Logger.php');

isset($_POST['testId']) ? $testId = $_POST['testId'] : $testId = 0;

connectToDb($db);

$query = "SELECT * FROM tests WHERE id = $testId;";
$result = $db->query($query);
$row = $result->fetch_object();
$finished = $row->scan_finished;

//Update finish time to current time while scan is not finished
if ($finished == 0) {
    $now = time();
    $query = "UPDATE tests SET finish_timestamp = $now WHERE id = $testId;";
    $result = $db->query($query);
}

$query = "SELECT * FROM tests WHERE id = $testId;";
$result = $db->query($query);

$row = $result->fetch_object();
$status = $row->status;
$startTime = $row->start_timestamp;
$finTime = $row->finish_timestamp;
$count = $row->numUrlsFound;
$numRequests = $row->num_requests_sent;

$duration = $finTime - $startTime;
$mins = intval($duration / 60);
$seconds = $duration % 60;
$secondsStr = strval($seconds);
$secondsFormatted = str_pad($secondsStr, 2, "0", STR_PAD_LEFT);

$query = "SELECT * FROM test_results WHERE test_id = $testId;";
$result = $db->query($query);
$numVulns = $result->num_rows;

//TODO: Put table here, looks bit messy
echo '<b>扫描详细:</b><br>';
echo '状态: '.$status;

echo "<br><br>找到的URL数目: $count";
echo "<br>花费的时间: $mins:$secondsFormatted";
echo "<br>发送的HTTP请求数: $numRequests";
echo "<br>发现的漏洞数: $numVulns";

$result->free();
$db->close();

?>