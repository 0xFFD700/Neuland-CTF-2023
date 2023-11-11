<?php
session_start();


if (!isset($_SESSION['user'])) {

    header("Location: login.php");
    exit;
}


$username = $_SESSION['user'];


if (isset($_POST['logout'])) {

    session_unset();
    session_destroy();
    

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php if($username==="administrator") {
		echo "<h4>nland{yOu_ArE_N0t_OuR_aDMinI5tr4T0r!}</h4>";
	} else {
		echo "<h4>Only our administrator is aware of the deepest mystery...</h4>";	
	}
    ?>

<div class="cmd-window" id="cmdWindow">
    <div id="cmdContent">C:\Users\<?php echo htmlspecialchars($username); ?>&gt;</div>
    <input type="text" class="cmd-input" id="cmdInput" autofocus  />
</div>
<br>
    <form method="post" action="logout.php">
        <input type="submit" name="logout" value="Logout">
    </form>

</body>
</html>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
  let input = document.getElementById('cmdInput');
  let cmdWindow = document.getElementById('cmdWindow');
  let cmdContent = document.getElementById('cmdContent');

  input.addEventListener('keydown', function(e) {
    if (e.keyCode === 13) {

      e.preventDefault();

      let command = input.value;
      let userCommandDisplay = 'C:\\Users\\' + '<?php echo htmlspecialchars($username); ?>' + '&gt;' + command;
      let response = "<div>> Unknown command: " + command + "</div> <br>";


      cmdContent.innerHTML += '<div>' + userCommandDisplay + '</div>' + response;


      input.value = "";

      cmdWindow.scrollTop = cmdWindow.scrollHeight;
    }
  });
});
</script>
