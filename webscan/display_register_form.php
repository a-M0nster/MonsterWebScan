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
$displayForm = true;

if (isset($_SESSION['username'])) {
    echo '您当前登录中。您必须登录到一个新创建的帐户中';
    $displayForm = false;
} else {
    if (isset($_POST['regusername']) || isset($_POST['regpassword']) || isset($_POST['regpassword2']) || isset($_POST['email'])) {
        if (empty($_POST['regusername']) || empty($_POST['regpassword']) || empty($_POST['regpassword2']) || empty($_POST['email'])) {
            echo '有一个或多个输入字段是空的。您必须在所有输入字段填写';
        } else if ($_POST['regpassword'] != $_POST['regpassword2']) {
            echo '密码不匹配输入的第一个密码';
        } else if (!ctype_alnum($_POST['regusername']) || !ctype_alnum($_POST['regpassword'])) //only hav to check the first password as the second password entered is equal to this (checked above)
        {
            echo '用户名和密码必须是字母数字。请再试一次';
        } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo '输入的电子邮件地址似乎不是一个有效的电子邮件。如果它是一个有效的电子邮件地址，请联系我们的管理员';
        } else //everything should be ok if we make it to here
        {
            $username = $_POST['regusername'];
            $password = $_POST['regpassword'];
            $email = $_POST['email'];

            if (connectToDb($db)) {
                $query = "SELECT * FROM users WHERE username = '$username'";
                $result = $db->query($query);
                if ($result) {
                    $numRows = $result->num_rows;
                    if ($numRows > 0) echo '对不起，该用户名已经存在。请再试一次';
                    else {
                        $query = "SELECT * FROM users WHERE email = '$email'";
                        $result = $db->query($query);
                        if ($result) {
                            $numRows = $result->num_rows;
                            if ($numRows > 0) echo '对不起，帐户已经使用此电子邮件地址退出。请再试一次';
                            else {
                                $query = "INSERT INTO users VALUE('$username',SHA1('$password'),'$email')";
                                $result = $db->query($query);
                                if ($result) {
                                    echo '恭喜！您已成功注册，登陆享受我们的站点';
                                    $displayForm = false;
                                } else echo '有连接到数据库中的问题。如果问题仍然存在，请与管理员联系';
                            }
                        } else echo '有连接到数据库中的问题。如果问题仍然存在，请与管理员联系';
                    }
                } else echo '有连接到数据库中的问题。如果问题仍然存在，请与管理员联系';

            } else echo '有连接到数据库中的问题。如果问题仍然存在，请与管理员联系';

        }
    }
}
if ($displayForm) require_once($currentDir.'register_form.html');
?>