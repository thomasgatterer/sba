<?php
  $incPath = "scripts/php/";
  include_once($incPath . "constants.inc.php");
  include_once($incPath . "html.inc.php");
  include_once($incPath . "cookie.inc.php");
  include_once($incPath . "db.inc.php");

  if (! cookieIsValid()) {
    cookieUnset();
    header("Location: index.php");
    exit;
  }

  if (isset($_POST['buttonPwCancel'])) {
    header("Location: eingabe.php");
    exit;
  }

  if (isset($_POST['pw1']) && isset($_POST['pw2'])) {
    $pw1 = $_POST['pw1'];
    $pw2 = $_POST['pw2'];
    $user = getCookieUser();
    if (strcmp($pw1, $pw2) != 0) {
      header("Location: pw.php");
      exit;
    }
    if (strlen($pw1) != 5) {
      header("Location: pw.php");
      exit;
    }
    $d = DB::getInstance();
    if ($d->connect()) {
      $q = "UPDATE lehrer SET Password='$pw1' WHERE Lehrer='$user'";
      $res = $d->doQuery($q);
      if ($res) {
        cookieSet($user, $pw1);
        header("Location: eingabe.php");
        exit;
      }
      else {
        header("Location: pw.php");
        exit;
      }
    }
    else {
      header("Location: eingabe.php");
      exit;
    }
  }
  else {
    buildHTMLHeader();
    buildPasswordForm(getCookieUser());
    buildHTMLFooter();
  }

?>
