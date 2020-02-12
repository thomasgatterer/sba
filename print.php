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

buildHTMLHeader();
print "<h1>SBA @ AGI, Lehrer/in: " . getCookieUser() . " (" . ACTYEAR . ")</h1>";
if (isset($_GET['klasse'])) {
  showKlasseTable($_GET['klasse']);
}
else {
  showEigeneTable();
}

print "\n<p style='text-align: center;'><button type='button' class='singlebutton no-print color-blue' " .
  "onclick='window.location.href=\"eingabe.php?modus=eigene\";'>Zur&uuml;ck</button></p>";
print "<p>&nbsp;</p>";
buildHTMLFooter();

function showEigeneTable() {
  $user = getCookieUser();
  $d = DB::getInstance();
  if ($d->connect()) {
    $d->getTitlesForUser(getCookieUser(), true);
  }
}

function showKlasseTable($klasse) {
  $d = DB::getInstance();
  if ($d->connect()) {
    $d->getKlasse($klasse, true);
  }
}
?>
