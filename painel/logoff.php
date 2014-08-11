<?

session_start();
session_unset();
session_destroy();
header("location: logon.php");
//echo scripthtml('msg','Successfully logout.');
//echo "<script type='text/javascript'> parent.location = 'index.php' </script>";
?>