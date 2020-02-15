<?php
  $incPath = "scripts/php/";
  include_once($incPath . "constants.inc.php");
  include_once($incPath . "html.inc.php");
  include_once($incPath . "cookie.inc.php");
  include_once($incPath . "db.inc.php");

  //var_dump($_POST);

  if (! cookieIsValid()) {
    cookieUnset();
    header("Location: index.php");
    exit;
  }

  if (!in_array($_SERVER['REMOTE_ADDR'], ALLOWEDCLIENTS)) {
    cookieUnset();
    header("Location: index.php");
    exit;
  }

  if (isset($_POST['buttonAnleitung'])) {
    header("Location: anleitung.php");
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

  if (isset($_POST['buttonDel'])) {
    $tablemode = 2;
    $actionmode = 2;
  }

  if (isset($_POST['buttonInsert'])) {
    $tablemode = 2;
    $actionmode = 3;
  }

  buildHTMLHeader();
  $actKlasse = (isset($_POST['klassen']) && isset($_POST['buttonKlasse'])) ? $_POST['klassen'] : "";
  $actFach = (isset($_POST['faecher']) && isset($_POST['buttonFach'])) ? $_POST['faecher'] : "";
  buildEingabeForm("SBA @ AGI, Lehrer/in: " . getCookieUser() .
    " (" . ACTYEAR . ")", $klasseHidden, $actKlasse, $actFach);
  switch ($tablemode) {
    case 1 : showNormalTable(); break;
    case 2 : showEigeneTable(); break;
    case 3 : showKlasseTable(); break;
    default : showNormalTable();
  }
  print getDeleteForm();
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
      $meldung = null;
      switch ($actionmode) {
        case 2 :
          $lh = (isset($_POST['lehrerhandDelete'])) && ($_POST['lehrerhandDelete'] == 1) ? true : false;
          $meldung = $d->deleteTitle($user, $_POST['buchnummerDelete'], $_POST['klasseDelete'], $lh);
          break;
        case 3 :
          $lh = (isset($_POST['lehrerhand'])) ? true : false;
          $uew = (isset($_POST['uew'])) ? true : false;
          $meldung = $d->insertTitle($user, $_POST['buchnummer'],
            $_POST['buchtitel'], $_POST['buchpreis'],
            $FAECHER[$_POST['faecher']], $_POST['klassen'],
            $lh, $uew, $_POST['wiederverwendung']);
          break;
        default : break;
      }
      $d->getTitlesForUser($user);
      if ($meldung != null) {
        print getMeldungForm($meldung);
      }
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
