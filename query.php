<?php
  $incPath = "scripts/php/";
  include_once($incPath . "constants.inc.php");
  include_once($incPath . "db.inc.php");

  if (! $_GET && ! $_POST) return;
  if ($_GET) {
    exit;
  }

  if ($_POST) {
    $d = DB::getInstance();
    if (! $d->connect()) exit;
    if ($_POST['Nr']) {
      $res = $d->queryOne(
        "SELECT Nr, Titel, Preis FROM liste WHERE Nr=" . $_POST['Nr'], false);
      if (! $res) {
        print $_POST['Nr'] . "||";
        exit;
      }
      $s = "";
      foreach($res as $r) {
        $s .= trim($r) . "|";
      }
      print $s;
    }
    else {
      print $_POST['Nr'] . "||";
    }
  }

?>
