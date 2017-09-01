
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-
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
   header("Location:kbcx.php");
 }
preg_match_all('/<span id="xhxm">([^<>]+)/',$con,$xm);
$xm[1][0]=substr($xm[1][0],0,-4);
$url2="http://218.75.197.123:83/xskbcx.aspx?xh=".$_SESSION['xh']."&xm=".$xm[1][0];
 function getDaySchedule($scheduleUrl,$cookie,$day = '') {
      $con = login_post($scheduleUrl, $cookie, '');
      $html = new simple_html_dom();
      $html=str_get_html($con);
      $table = $html->find('table.blacktab tbody tr td');
      $sum = 0;
      $classNameArr = array(
          '第1节',
          '第2节',
          '第3节',
          '第4节',
          '第5节',
          '第6节',
          '第7节',
          '第8节',
          '第9节',
          '第10节',
          '第11节',
          '第12节',
          '第13节'
      );
    foreach ($table as $key => $value) {
        $temp = preg_split("/\s+/i", $value->plaintext);
        if (count($temp) == 1) {
            $data[$sum] = $temp['0'];
        }
        if (in_array($temp['0'], $classNameArr)) {
            $pos[] = $sum;
        } else {
            $data[$sum] = $temp;
        }
        $sum++;
    }
    unset($table);
    $pos[] = count($data);
    $posCount = count($pos);
    $classNum = 1;
    for ($i = 0; $i < $posCount - 1; $i++) {
        $startPos = $pos[$i];
        $endPos = $pos[$i + 1];
        for ($j = $startPos; $j < $endPos; $j++) {
            if ($data[$j] == '&nbsp;') {
                $result[$classNum][] = NULL;
            } else {
                $result[$classNum][] = $data[$j];
            }
        }
        $classNum++;
    }
    $data = $result;
    foreach ($data as $k1 => $v1) {
        foreach ($v1 as $k2 => $v2) {
            if (is_array($v2)) {
                foreach ($v2 as $k3 => $v3) {
                    if ($v3 == '&nbsp;') {
                        $v2[$k3] = NULL;
                    }
                    if ($v3 == '下午' || $v3 == '晚上') {
                        unset($v2[$k3]);
                    }
                }
                $data1[] = $v2;
            }
        }
    }
    $data2 = array_values(array_filter($data1));
    foreach ($data2 as $k4 => $v4) {
        $data3[] = array_filter($v4);
    }
    foreach ($data3 as $k5 => $v5) {
        if (!empty($v5)) {
            $data4[] = $v5;
        }
    }
    $data5 = array();
    foreach ($data4 as $k6 => $v6) {
        foreach ($v6 as $k7 => $v8) {
            switch ($day) {
                case '星期一':
                    if (is_int(strpos($v8, '周一'))) {
                        $data5['星期一'][] = $v6;
                    }
                    break;
                case '星期二':
                    if (is_int(strpos($v8, '周二'))) {
                        $data5['星期二'][] = $v6;
                    }
                    break;
                case '星期三':
                    if (is_int(strpos($v8, '周三'))) {
                        $data5['星期三'][] = $v6;
                    }
                    break;
                case '星期四':
                    if (is_int(strpos($v8, '周四'))) {
                        $data5['星期四'][] = $v6;
                    }
                    break;
                case '星期五':
                    if (is_int(strpos($v8, '周五'))) {
                        $data5['星期五'][] = $v6;
                    }
                    break;
                case '星期六':
                    if (is_int(strpos($v8, '周六'))) {
                        $data5['星期六'][] = $v6;
                    }
                    break;
                case '星期日':
                    if (is_int(strpos($v8, '周日'))) {
                        $data5['星期日'][] = $v6;
                    }
                    break;
            }
        }
    }
    $data6 = array();
    foreach ($data5 as $k7 => $v7) {
        foreach ($v7 as $k8 => $v8) {
            if (array_key_exists($k8, $v7) && array_key_exists($k8 + 1, $v7)) {
                $result = array_diff_assoc($v7[$k8], $v7[$k8 + 1]);
                if (empty($result)) {
                    unset($v7[$k8]);
                }
            }
        }
        $data6[$day] = array_values(array_filter($v7));
    }
        
    $data7=array();
    foreach ($data6 as $k9 => $v9) {
        foreach ($v9 as $k10 => $v10) {
            $count1 = count($v10);
            //元素数量小于等于4的(3和4两种情况)
            if ($count1 <= 4) {
                if ($count1 == 3) {
                    unset($v10[2]);
                } else {
                    unset($v10[2]);
                    unset($v10[3]);
                }
            }
            //元素数量大于4，小于等于8
            if ($count1 > 4 && $count1 <= 8) {
                if ($count1 == 6) {
                    unset($v10[2]);
                    unset($v10[5]);
                } else {
                    unset($v10[2]);
                    unset($v10[3]);
                    unset($v10[6]);
                    unset($v10[7]);
                }
            }
            $data7[] = array_values(array_filter($v10));
        }
    }
    $data8[$day] = $data7;
    return $data8;
}
 function getSchedule($scheduleUrl,$cookie) {
      $Monday = getDaySchedule($scheduleUrl,$cookie,'星期一');
      $Tuesday =getDaySchedule($scheduleUrl,$cookie,'星期二');
      $Wednesday = getDaySchedule($scheduleUrl,$cookie,'星期三');
      $Thursday = getDaySchedule($scheduleUrl,$cookie,'星期四');
      $Friday = getDaySchedule($scheduleUrl,$cookie,'星期五');
      $Saturday = getDaySchedule($scheduleUrl,$cookie,'星期六');
      $Sunday = getDaySchedule($scheduleUrl,$cookie,'星期日');
      $timetable = array_merge_recursive($Monday, $Tuesday, $Wednesday, $Thursday, $Friday, $Saturday, $Sunday);
      return $timetable;
  }
$course=getSchedule($url2,$cookie);
var_dump($course);
//$con1=login_post($url2,$cookie,'');
//echo $con1;
//$html=new simple_html_dom();
//$html=str_get_html($con1);
/*foreach($html->find('table#Table1')as $table){
  foreach($table->find(tr)as $k=>$tr){
    if($tr->find('td',0)->plaintext=="时间"){
      continue;
    }
    else{
      $course[$k]['mon']=$tr->find('td',1)->plaintext;
      $course[$k]['thus']=$tr->find('td',2)->plaintext;
      $course[$k]['wed']=$tr->find('td',3)->plaintext;
      $course[$k]['thur']=$tr->find('td',4)->plaintext;
      $course[$k]['fir']=$tr->find('td',5)->plaintext;
      $course[$k]['sat']=$tr->find('td',6)->plaintext;
      $course[$k]['sun']=$tr->find('td',7)->plaintext;
    }
  }
}
var_dump($course);*/
?>
<body>
<div class="main">
<div class="title">
本学期的课表
</div>
<div class="score">
<table class="datelist" cellspacing="2" cellpadding="3" border="0" id="Datagraid2" width="80%">
<tr class="datelisthead1">
<td>星期一</td><td>星期二</td><td>星期三</td><td>星期四</td><td>星期五</td><td>星期六</td><td>星期天</td>
<?php 
foreach($course as $key=>$value){
   print_r("<tr>");
   foreach($value as $key2=>$value2){
  print_r("<td>".$value2."</td>");
   }
   print_r("</tr>");
}
?>