<?php
  include_once("constants.inc.php");
  include_once("db.inc.php");

  function cookieSet($user, $password) {
    $d = DB::getInstance();
    if ($d->connect()) {
      $q = "SELECT ID, Lehrer, Password FROM lehrer WHERE BINARY Lehrer='$user' " .
        "AND BINARY Password='$password'";
      $r = $d->queryOne($q);
      if (! $r) {
        return false;
      }
      else {
        $c = $r['ID'] . "|" . $r['Lehrer'] . "|" . $r['Password'];
        setcookie(COOKIENAME, $c, 0, '/');
      }
    }
    else {
      return false;
    }
  }

  function cookieUnset() {
    if (isset($_COOKIE[COOKIENAME])) {
      setcookie(COOKIENAME, "", time() - 3600, '/');
    }
  }

  function cookieIsValid() {
    return isset($_COOKIE[COOKIENAME]);
  }

  function getCookieID() {
    if (isset($_COOKIE[COOKIENAME])) {
      $c = explode("|", $_COOKIE[COOKIENAME]);
      return $c[0];
    }
    else {
      return -1;
    }
  }

  function getCookieUser() {
    if (isset($_COOKIE[COOKIENAME])) {
      $c = explode("|", $_COOKIE[COOKIENAME]);
      return $c[1];
    }
    else {
      return "";
    }
  }

  function getCookiePassword() {
    if (isset($_COOKIE[COOKIENAME])) {
      $c = explode("|", $_COOKIE[COOKIENAME]);
      return $c[2];
    }
    else {
      return "";
    }
  }
?>
