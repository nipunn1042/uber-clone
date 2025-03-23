<?php
session_start();
session_unset();
session_destroy();

// Prevent back button from accessing previous pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

echo "<script>
    setTimeout(function() {
        window.location.href = 'login.php';
    }, 100);
</script>";

// Redirect to login page
header("Location: login.php");
exit();
?>