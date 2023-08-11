<html>
<?php require("ls.php"); ?>
<head>
<style type="text/css">
h1 {color: #6DD15E;}
body {background: #E6F1F7;}
div#header {text-align: center; }
div#logo {position: absolute; top: 1%; right: 5%}
div#linkList {float: right}
div#LS_CONTENT {position: static; top: 10%; left: 15%; right: 15%; bottom: 10%}
.trend{      margin-bottom: 20px;
      padding: 10px;
      border: 1px solid #ccc;}
.course{      margin-bottom: 20px;
      padding: 10px;
      border: 1px solid #ccc;}
div#mainContent {

}
div#rightMatter {
  float: right;
  padding: 10px; border: 2px solid #CCCC99;
  background: #f0f0f0;
}
div#footer {position: static; top: 90%;}
  
</style>
  <title><?php insertTitle(); ?></title>
</head>
<body>

<div id="header">
   <?php insertCommonHeader(); ?>
</div>

<div id="logo">
   <?php insertLogo(); ?>
</div>

<div id="rightMatter">
   <?php insertRightMatter('') ?>
</div>

  <div id="mainContent">
    <?php insertFileContent(); ?>
  </div>


<div id="footer">
  <?php insertCommonFooter(); ?>
</div>

</body>
</html>

