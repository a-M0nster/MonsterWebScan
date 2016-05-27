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


//Common database functions used.

//Connects to database. Returns true on success, False on failure.
function connectToDb( & $db) {
    $db = $db = new mysqli('localhost', 'root', 'root', 'webvulscan');
    if (mysqli_connect_errno()) {
        return false;
    }
    return true;
}

//Update status of test in db
function updateStatus($db, $newStatus, $testId) {
    $query = "UPDATE tests SET status = '$newStatus' WHERE id = $testId;";
    $result = $db->query($query);
    return $result;
}

function insertTestResult($db, $testId, $type, $method, $url, $attackStr) {
    $query = "INSERT into test_results(test_id, type, method, url, attack_str) VALUES($testId,'$type','$method','$url','$attackStr')";
    $result = $db->query($query);
    return $result;
}

//Generates the next test id
//Return the next test id on success. Otherwise returns false.
function generateNextTestId($db) {
    $query = "SELECT MAX(id) FROM tests";
    $result = $db->query($query);
    if (!$result) return $result;

    $row = $result->fetch_array();

    $maxId = $row[0] + 1;
    //$maxId = $row->id;//or else $row->MAX(id)
    return $maxId;
}

//Adds 1 to the current number of HTTP requests sent
//Returns true on success, false on failure
function incrementHttpRequests($db, $testId) {
    $query = "UPDATE tests SET num_requests_sent = (num_requests_sent + 1) WHERE id = $testId";
    $result = $db->query($query);
    return $result;
}

?>