<!DOCTYPE html>
<html>
<head>
<link rel="icon" href="favicon.png">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<style>
  .small, .number { text-align:center; line-height:42px; font-family:Roboto; }
  .small { font-size: 22px; }
  .number { font-size:96px; font-weight:900; }
  #container { width: 100%; padding-top: 40px; position: absolute; transform: translateY(-45%); }
  body { overflow-x: hidden; overflow-y: hidden; }
</style>
<title>Error <?php echo $_GET["code"]; ?></title>
<?php $codeMsg = array("400" => "Bad Request", "401" => "Unauthorized", "403" => "Forbidden", "404" => "Not Found", "503" => "Service Temporarily Unavailable"); ?>
</head>

<body>
<div id="container">
	<div class="small">Error</div><br>
	<div class="number"><?php echo $_GET["code"]; ?></div><br>
	<div class="small"><?php echo $codeMsg[$_GET["code"]]; ?></div>
  <div class="small"><br /><img src="https://i.redd.it/cunw1gutoll31.png"></img>
</div>
<script type="text/javascript">
  function center() {
    document.getElementById("container").style.paddingTop = (document.documentElement.clientHeight * 0.8) + "px";
    setTimeout(center, 1000);
  }

  center();
</script>
</body>
</html>