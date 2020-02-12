<?php
  $incPath = "scripts/php/";
  include_once($incPath . "constants.inc.php");
  include_once($incPath . "html.inc.php");
  include_once($incPath . "cookie.inc.php");

  $user = "";
  $password = "";

  // show Login-Form
  if (! $_POST) {
    cookieUnset();
    buildHTMLHeader();
    buildLoginForm();
    buildHTMLFooter();
    exit;
  }

  // jump to eingabe.php
  if ($_POST) {
    $user = $_POST['user'];
    $password = $_POST['password'];
    cookieSet($user, $password);
    header("Location: eingabe.php");
    exit;
  }

?>
