<html>
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
  <link href="css/select.css" rel="stylesheet" type="text/css" >
</head>
<body>
<div class="main">
<div class="title">
教务查询
</div>
<ul class="select">
<li class="cj">
<div class="icon sl_for">
</div>
<p>成&nbsp;&nbsp;&nbsp;&nbsp;绩</p>
</li>
<li class="kb">
<div class="icon _sl_one">
</div>
<p>课&nbsp;&nbsp;&nbsp;&nbsp;表</p>
</li>
</ul>
</div>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script>
$(document).ready(function(){
 $(".cj").on("click",function(){
 // $.showLoading();
   var id = $(this).data("id");
   window.location.href="cjcx.php";
 });

 $(".kb").on("click",function(){
 // $.showLoading();
   var id = $(this).data("id");
   window.location.href="kbcx.php";
 });
});
</script>
</body>
</html>

