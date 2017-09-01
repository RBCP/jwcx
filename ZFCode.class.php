<?php 
class ZFCode{
	public function Login_post($url,$cookie,$post){
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
	public function Login(){
		session_start();
		 $SESSION['xh']=$_POST['xh'];
		 $xh=$_PSOT['xh'];
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
    $con2=$this->login_post($url,$cookie,http_build_query($post));
	return $con2;
 }
 public function score(){
 	session_start();
 	$con=$this->Login();
 	include_once('simple_html_dom.php');
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
 	'ddlXN'=>'2015-2016',
 	'ddlXQ'=>2,
 	'ddl_kcxz'=>'', 
 	'btn_xq'=>'%D1%A7%C6%DA%B3%C9%BC%A8');
    $content=login_post($url2,$cookie,http_build_query($post1));
     echo $content;
    $html=new simple_html_dom();
    $html=str_get_html($content);
    $score=array();
    foreach($html->find('table#Datagrid1')as $table){
    foreach($table->find(tr)as $k=>$tr){
    if($tr->find('td',0)->plaintext=="学年"){
      continue;
    }
    else{
      $score[$k]['schoolyear']=$tr->find('td',0)->plaintext;
      $score[$k]['schoolterm']=$tr->find('td',1)->plaintext;
      $score[$k]['coursename']=$tr->find('td',3)->plaintext;
      $score[$k]['coursexingzhi']=$tr->find('td',4)->plaintext;
      $score[$k]['credit']=$tr->find('td',6)->plaintext;
      $score[$k]['gradepoint']=$tr->find('td',7)->plaintext;
      $score[$k]['score']=$tr->find('td',8)->plaintext;
      $score[$k]['collegename']=$tr->find('td',12)->plaintext;
    }
  }
    return $score;
 }
}