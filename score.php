
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
  <meta content="yes" name="apple-mobile-web-app-capable">
  <meta content="black" name="apple-mobile-web-app-status-bar-style">
  <meta content="telephone=no" name="format-detection">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <link href="css/score.css" rel="stylesheet" type="text/css"/>
  <link href="css/public.css" rel="stylesheet" type="text/css"/>
</head>
<?php 
 error_reporting(0);
  include_once('simple_html_dom.php');
  session_start();
  //header("Content-type:text/html;charset=gbk");
  
  function login_post($url,$cookie,$post){
  	$ch=curl_init();
  	curl_setopt($ch,CURLOPT_URL,$url);//获取内容的url
  	curl_setopt($ch,CURLOPT_HEADER,1);//获取http头部信息
  	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//返回数据流，不直接输出
    //curl_setopt($ch, CURLOPT_NOBODY, true);
  	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
  	curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie);
  // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  	curl_setopt($ch,CURLOPT_REFERER,'http://218.75.197.123:83/');
  	curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
  	$con2=curl_exec($ch);
  	curl_close($ch);
  	return $con2;
  }
  $_SESSION['xh']=$_POST['xh'];
  $xh=$_POST['xh'];
  $pw=$_POST['pw'];
  $code=$_POST['code'];
  $cookie=dirname(__FILE__).'/cookie/'.$_SESSION['id'].'.txt';
  $url="http://218.75.197.123:83";
  $con1=login_post($url,$cookie,'');
  preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $con1, $view);
  $post=array(
  '__VIEWSTATE'=>$view[1][0],
  'txtUserName'=>$xh,
  'TextBox2'=>$pw,
  'txtSecretCode'=>$code,
  'RadioButtonList1'=>'%D1%A7%C9%FA',  //“学生”的gbk编码
  'Button1'=>'',
  'lbLanguage'=>'',
  'hidPdrs'=>'',
  'hidsc'=>''
  );
 $con2=login_post($url,$cookie,http_build_query($post));
 preg_match_all('/^Location: (.+?)$/m',$con2,$Location);
 $tmp=$Location[1][0];
 if(!isset($tmp)){
  echo "你输入的信息有误，可能是密码或验证码有错，请再检查一遍哒！";
  sleep(10);
   header("Location:shouye.php");
 }
preg_match_all('/<span id="xhxm">([^<>]+)/',$con,$xm);
$xm[1][0]=substr($xm[1][0],0,-4);
$url2="http://218.75.197.123:83/xscjcx.aspx?xh=".$_SESSION['xh']."&xm=".$xm[1][0];
$viewstate=login_post($url2,$cookie,'');
 preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $viewstate, $vs);
    $state=$vs[1][0];
 $post1=array(
 	'__EVENTARGET'=>'',
 	'__EVENTARGUMENT'=>'',
 	'__VIEWSTATE'=>$state,
 	'hidLanguage'=>'',
 	'ddlXN'=>$_POST['ddlXN'],
 	'ddlXQ'=>$_POST['ddlXQ'],
 	'ddl_kcxz'=>'', 
 	'btn_xq'=>'%D1%A7%C6%DA%B3%C9%BC%A8');
$content=login_post($url2,$cookie,http_build_query($post1));
$html=new simple_html_dom();
$html=str_get_html($content);
//echo $html;
$score=array();
foreach($html->find('table#Datagrid1')as $table){
  foreach($table->find(tr)as $k=>$tr){
    if($tr->find('td',0)->plaintext=="学年"){
      continue;
    }
    else{
      $score[$k]['coursename']=$tr->find('td',3)->plaintext;
      $score[$k]['credit']=$tr->find('td',6)->plaintext;
      $score[$k]['gradepoint']=$tr->find('td',7)->plaintext;
      $score[$k]['score']=$tr->find('td',8)->plaintext;
    }
  }
}
?>
<body>
<div class="main">
<div class="title">
本学期的成绩
</div>
<div class="score">
<table class="datelist" cellspacing="2" cellpadding="3" border="0" id="Datagraid2" width="80%">
<tr class="datelisthead1">
<td>课程名称</td><td>学分</td><td>绩点</td><td>成绩</td>
<?php 
foreach($score as $key=>$value){
   print_r("<tr>");
   foreach($value as $key2=>$value2){
  print_r("<td>".$value2."</td>");
   }
   print_r("</tr>");
}
?>
</tr>
</table>
</div>
</div>
</body>
</html>

