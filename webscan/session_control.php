<meta charset="UTF-8">
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
$loginMsg = '';

if(isset($_GET['action']))
{
	$action = $_GET['action'];
	if($action == 'logout')
	{
		if(isset($_SESSION['username']))
		{
			unset($_SESSION['username']);
			$loginMsg = '您成功登录！';
		}
		else
			$loginMsg = '您当前未登录！';
	
	}
}

//Check if user has just made a login attempt
if(isset($_POST['email']) && isset($_POST['password']))
{
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	$continueLogin = true;
	
	if(!filter_var($email, FILTER_VALIDATE_EMAIL) || !ctype_alnum($password))
	{
		$loginMsg = '电子邮件或密码无效， 请重新尝试！';
		$continueLogin = false;
	}
	
	if(connectToDb($db) && $continueLogin)
	{
		$query = "SELECT * FROM users WHERE email = '$email' AND password = SHA1('$password')";
		$result = $db->query($query);
		if($result)
		{
			$numRows = $result->num_rows;
			if($numRows == 0)
				$loginMsg = '电子邮件或密码无效， 请重新尝试！';
			else
			{
				$row = $result->fetch_object();
				$username = $row->username;
				$_SESSION['username'] = $username;
				$_SESSION['email'] = $email;
				$loginMsg = '您已成功登录！';
			}
		}
		else
		{
			$loginMsg = '出现一个问题，请检查您的凭据。如果问题还存在，请于管理员联系!';
		}
	}
}


//Check if user is logged
if(isset($_SESSION['username']))
{
	echo '<h5>欢迎 ' . $_SESSION['username'] . ' | <a href="logout.php?action=logout"> 退出</a></h5>';
	if(!isset($loginMsg))
		$loginMsg = '您已成功登录！';
}
else
{
	require_once($currentDir . 'login_form.html');
}
