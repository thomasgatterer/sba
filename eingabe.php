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

  if (isset($_POST['buttonPassword'])) {
    header("Location: pw.php");
    exit;
  }

  $tablemode = 2;     // normale Tabelle, 2 = eigene, 3 = Klasse
  $actionmode = 1;    // normale Aktion, 2 = delete, 3 = insert (Kombination mit tablemode 2)
  $klasseHidden = ""; // hiddenData value, if search Klasse

  $qFront = "SELECT Nr, Titel, Preis, Fach, Klasse FROM liste ";
  $q = $qFront . "ORDER BY Fach, Titel";

  if (isset($_POST['buttonAlles'])) {
    $tablemode = 1;
  }

  if (isset($_POST['buttonBuchnummer'])) {
    if ($_POST['buchnummer'] != null) {
      $q = $qFront . "WHERE Nr=" . $_POST['buchnummer'] . " ORDER BY Nr";
      $tablemode = 1;
    }
  }

  if (isset($_POST['buttonTitel'])) {
    if ($_POST['buchtitel'] != null) {
      $q = $qFront . "WHERE Titel LIKE '%" . $_POST['buchtitel'] . "%' ORDER BY Titel";
      $tablemode = 1;
    }
  }

  if (isset($_POST['buttonFach'])) {
    $v = $_POST['faecher'];
    if ($v != "KEINES")
      $q = $qFront . "WHERE Fach LIKE '%" . $v . "%' ORDER BY Titel";
      $tablemode = 1;
  }

  if (isset($_POST['buttonKlasse'])) {
    $v = $_POST['klassen'];
    if ($v != "KEINE") {
      $tablemode = 3;
      $klasseHidden = strtolower($_POST['klassen']);
    }
  }

  if (isset($_POST['buttonEigene'])) {
    $tablemode = 2;
  }

  if (isset($_POST['buttonDelete'])) {
    $tablemode = 2;
    $actionmode = 2;
  }

  if (isset($_POST['buttonInsert'])) {
    $tablemode = 2;
    $actionmode = 3;
  }

  buildHTMLHeader();
  buildEingabeForm("SBA @ AGI, Lehrer/in: " . getCookieUser() . " (" . ACTYEAR . ")", $klasseHidden);
  switch ($tablemode) {
    case 1 : showNormalTable(); break;
    case 2 : showEigeneTable(); break;
    case 3 : showKlasseTable(); break;
    default : showNormalTable();
  }
  buildHTMLFooter();

  function showNormalTable() {
    global $q;
    $d = DB::getInstance();
    if ($d->connect()) {
      $d->queryTable($q, true);
    }
  }

  function showEigeneTable() {
    global $actionmode;
    global $FAECHER;
    $user = getCookieUser();
    $d = DB::getInstance();
    if ($d->connect()) {
      switch ($actionmode) {
        case 2 :
          $lh = isset($_POST['lehrerhand']);
          $d->deleteTitle($user, $_POST['buchnummer'], $_POST['klassen'], $lh);
          break;
        case 3 :
          $lh = isset($_POST['lehrerhand']);
          $uew = isset($_POST['uew']);
          $d->insertTitle($user, $_POST['buchnummer'],
            $_POST['buchtitel'], $_POST['buchpreis'],
            $FAECHER[$_POST['faecher']], $_POST['klassen'], $lh, $uew);
          break;
        default : break;
      }
      $d->getTitlesForUser($user);
    }
  }

  function showKlasseTable() {
    $v = $_POST['klassen'];
    $d = DB::getInstance();
    if ($d->connect()) {
      $d->getKlasse($v);
    }
  }
?>
