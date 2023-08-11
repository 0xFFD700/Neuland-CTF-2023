<?php
echo '<script>
    var message = "Thank you for your application.";
    if (confirm(message)) {
        window.location.href = "index.php";
    }
</script>';
exit;
?>
