<!DOCTYPE>
<head>
  <meta charset="UTF-8">
  <title>湖南工业大学教务系统</title>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
  <meta content="yes" name="apple-mobile-web-app-capable">
  <meta content="black" name="apple-mobile-web-app-status-bar-style">
  <meta content="telephone=no" name="format-detection">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="renderer" content="webkit" />
  <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
  <link href="css/public.css" rel="stylesheet" type="text/css" >
  <link href="css/login.css" rel="stylesheet" type="text/css" >
</head>
<?php 
   session_start();
   $id=session_id();
   $_SESSION['id']=$id;
   $cookie=dirname(__FILE__).'/cookie/'.$_SESSION['id'].'.txt';
   $verify_code_url="http://218.75.197.123:83/CheckCode.aspx";
   $curl=curl_init();
   curl_setopt($curl, CURLOPT_URL, $verify_code_url);
   curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);  //保存cookie
   curl_setopt($curl, CURLOPT_HEADER, 0);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   $img = curl_exec($curl);  //执行curl
   curl_close($curl);
   $fp = fopen("verifyCode.jpg","w");  //文件名
   fwrite($fp,$img);  //写入文件
   fclose($fp);
   ?>
<body>
  <div class="main">
    <div class="logo">
      <img src="img/logo.jpg">
    </div>

    <div class="title">
      课表查询
    </div>

    <form name="queryForm" method="post" class="" action="course.php">

      <div class="so_box" id="11">
        <div class="label">学&nbsp;&nbsp;&nbsp;&nbsp;号：</div>
        <input name="xh" type="text" pattern="[0-9]*" class="txts"  placeholder="请输入学号" value=""/>
      </div>
      <div class="so_box" id="22">
        <div class="label">密&nbsp;&nbsp;&nbsp;&nbsp;码：</div>
        <input name="pw" type="password"  class="txts"  placeholder="请输入密码" value=""/>
      </div>
      <div class="searchbox">
      <p class="search_con">
      <span class="Label">学&nbsp;&nbsp;年:</span>
      <select name="ddlXN" id="ddlXN">
      <option value></option>
      <option value="2017-2018">2017-2018</option>
      <option value="2016-2017">2016-2017</option>
      <option value="2015-2016">2015-2016</option>
      <option value="2014-2015">2014-2015</option>
      </select>
      <span class="Label2">学&nbsp;&nbsp;期:</span>
      <select name="ddl_XQ" id="ddlXQ">
      <option value="1">1</option>
      <option value="2">2</option>
      </select>
      </p>
      </div>
      <div class="so_box" id="33">
      <div class="label">验证码:</div>
      <input name="code" type="text" class="txts" placeholder=" "/>
      <img src="verifyCode.jpg" id="Codes" OnClick="this.src="this.src='verifyCode.jpg';"/>
      </div>
      <div class="so_boxes">
        <input type="submit" name="button" class="buts" id="sub" value="查    询" />
      </div>
  </form>

  </div>

  <div class="append">2016-2017 同窗校园网 <br> Powered by none</div>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script src="https://cdn.bootcss.com/jquery-weui/1.0.0/js/jquery-weui.min.js"></script>
  <script>
      $(document).ready(function(){
        $(".buts").on("click",function(){
          i = 0;
          $("input").each(function(){
            if(!$(this).is(":hidden")&&$(this).val()==""){
              $(this).focus();
              $.toptip('请填写完整信息！', 'error');
              i++;
              return false;
            }
          });

          if(i==0){
            $.showLoading();
          }else{
            return false;
          }
        });
      });

      var tip = "";
      if(tip!=""){
        $.toptip(tip, 'error');
      }

      $("input").focus(function(){
        $(".append").hide();
      });
      $("input").blur(function(){
        $(".append").show();
      });
  </script>

</body>
