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


require_once('functions/databaseFunctions.php');

global $user;

if (isset($_SESSION['username'])) {
    //Get the user's username and email address
    $username = $_SESSION['username'];

    if (!connectToDb($db)) {
        echo '发现一个连接数据库的问题.';
        return;
    }

    $query = "SELECT * FROM tests WHERE type = 'scan' AND username = '$username'";
    //echo $query;
    $result = $db->query($query);
    if ($result) {
        $numRows = $result->num_rows;
        if ($numRows == 0) echo '您以前没执行任何扫描';
        else {
            echo '<table border="3" width="900"><tr><th>编号</th><th>开始时间</th><th>URL</th><th>漏洞数目</th><th>报表</th></tr>';
            for ($i = 0; $i < $numRows; $i++) {
                $row = $result->fetch_object();
                $id = $row->id;
                $startTime = $row->start_timestamp;
                $startTimeFormatted = date('l jS F Y h:i:s A', $startTime);
                $url = $row->url;

                $numVulns = 'Unknown';
                $query = "SELECT * FROM test_results WHERE test_id = $id";
                $resultTwo = $db->query($query);
                if ($resultTwo) $numVulns = $resultTwo->num_rows;

                $report = '<a href="scanner/reports/Test_'.$id.'.pdf" target="_blank">View</a>';

                echo '<tr>';
                echo "<td align='center'>$id</td>";
                echo "<td align='left'>$startTimeFormatted</td>";
                echo "<td align='left'>$url</td>";
                echo "<td align='center'>$numVulns</td>";
                echo "<td align='center'>$report</td>";
                echo '</tr>';

            }
            echo '</table>';

        }

    } else echo '往数据库插入数据时发现一个问题';
} else echo '您没有登录. 请尝试重新登录.';





?>