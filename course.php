
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
  <meta content="yes" name="apple-mobile-web-app-capable">
  <meta content="black" name="apple-mobile-web-app-status-bar-style">
  <meta content="telephone=no" name="format-detection">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
  <link href="css/course.css" rel="stylesheet" type="text/css"/>
  <link href="css/public.css" rel="stylesheet" type="text/css"/>
<?php 
 error_reporting(0);
  include_once('simple_html_dom.php');
  session_start();
  header("Content-type:text/html;charset=utf-8");
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
   header("Location:kbcx.php");
 }
preg_match_all('/<span id="xhxm">([^<>]+)/',$con,$xm);
$xm[1][0]=substr($xm[1][0],0,-4);
$url2="http://218.75.197.123:83/tjkbcx.aspx?xh=".$_SESSION['xh']."&xm=".$xm[1][0];
$con2=login_post($url2,$cookie);
$html=new simple_html_dom();
$html=str_get_html($con2);
$course=array();
foreach($html->find('Table.blacktab') as $table){
  foreach($table->find(tr) as $k=>$tr){
  if($tr->find('td',0)->plaintext=="时间"||$tr->find('td',0)->plaintext=="早晨"){
  continue;
  }
 else{
  if($tr->find('td',0)->plaintext=="上午"||$tr->find('td',0)->plaintext=="下午"||$tr->find('td',0)->plaintext=="晚上"){
  $course[$k]['time']=$tr->find('td',1)->plaintext;
  $course[$k]['course1']=$tr->find('td',2)->plaintext;
  $course[$k]['course2']=$tr->find('td',3)->plaintext;
  $course[$k]['course3']=$tr->find('td',4)->plaintext;
  $course[$k]['course4']=$tr->find('td',5)->plaintext;
  $course[$k]['course5']=$tr->find('td',6)->plaintext;
  }
  else
  {
  $course[$k]['time']=$tr->find('td',0)->plaintext;
  $course[$k]['course']=$tr->find('td',1)->plaintext;
  $course[$k]['course1']=$tr->find('td',2)->plaintext;
  $course[$k]['course2']=$tr->find('td',3)->plaintext;
  $course[$k]['course3']=$tr->find('td',4)->plaintext;
  $course[$k]['course4']=$tr->find('td',5)->plaintext;
  }
  }
}
}
?>
<body>
<div class="main">
<div class="title">
本学期的课表
</div>
<div class="score">
<table class="datelist">
<tr>
<td width="30">时间</td><td width="100">星期一</td><td width="100">星期二</td><td width="100">星期三</td><td width="100">星期四</td><td width="100">星期五</td>
<?php 
$arr=array();
$k1=1;
foreach($course as $key=>$value){
  $k2=1;
   foreach($value as $key2=>$value2){
    $arr[$k1][$k2]=$value2;
    $k2++;
  }
  $k1++;
   }
$flag=array(0);
for($i=1;$i<$k1;$i++){
  print_r("<tr height=100>");
for($j=1;$j<=6;$j++){
  $count=strlen($arr[$i][$j]);
  if($count<10){
    if($flag[$i][$j])
      continue;
  print_r("<td>".$arr[$i][$j]."</td>");}
  else{
    $flag[$i+1][$j]=1;
    print_r("<td rowspan=2>");
    $str=$arr[$i][$j];
     $arr1=preg_split("/[\s]+/",$str); 
    $count=count($arr1);
    for($k=0;$k<$count;$k++){
    print_r("<br>".$arr1[$k]);
    }
    print_r("</td>");
  }
}
print_r("</tr>");
}

?>



