<?php
  include_once("constants.inc.php");
  include_once("db.inc.php");

  /* HTML-header */
  function buildHTMLHeader() {
    $h = "<!doctype html>\n<html lang='de'>\n";
    $h .= "<head>\n";
    $h .= "<title>SBA@AGI</title>\n";
    $h .= "<meta charset='utf-8'>\n";
    $h .= "<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
    $h .= "<meta http-equiv='expires' content='0'>\n";
    $h .= "<link href='scripts/css/sba.css' rel='stylesheet' type='text/css' />";
    $h .= "</head>\n";
    $h .= "<body>\n";
    print $h;
  }

  function buildHeader($h = "<h1>SBA @ AGI</h1>\n") {
    print $h;
  }

  function buildHTMLFooter() {
    $f = "<script src='scripts/js/tools.js'></script></body>\n</html>\n";
    print $f;
  }

  function buildLoginForm() {
    $f = "<div class='loginform'>\n";
    $f .= "<form action='index.php' id='login' method='POST'>\n";
    $f .= "<p><label class='h2' form='login'>SBA: Login</label></p>\n";
    $f .= "<p><label for='user'>Benutzer</label>\n";
    $f .= "<input type='text' name='user' id='user' maxlength='10'>\n";
    $f .= "</p><p>\n";
    $f .= "<label for='password'>Passwort</label>\n";
    $f .= "<input type='password' name='password' id='password' maxlength='10'>\n";
    $f .= "</p><p>\n";
    $f .= "<button type='submit'>OK</button>\n";
    $f .= "</p>\n";
    $f .= "</form>\n";
    $f .= "</div>\n";
    print $f;
  }

  function buildPasswordForm($user) {
    $f = "<div class='passwordform'>\n";
    $f .= "<form action='pw.php' id='passwordform' method='POST'>\n";
    $f .= "<p><label class='h2' form='passwordform'>SBA: Passwort &auml;ndern</label></p>\n";
    $f .= "<p><label for='user'>Benutzer</label>\n";
    $f .= "<input type='text' name='user' id='user' maxlength='10' disabled value='$user'>\n";
    $f .= "</p><p>\n";
    $f .= "<label for='pw1'>neues Passwort</label>\n";
    $f .= "<input type='password' name='pw1' id='pw1' maxlength='10'>\n";
    $f .= "</p><p>\n";
    $f .= "<label for='pw2'>Passwort wiederholen</label>\n";
    $f .= "<input type='password' name='pw2' id='pw2' maxlength='10'>\n";
    $f .= "</p><p>\n";
    $f .= "<button type='submit' name='buttonPwOK' id='buttonPwOK' class='color-green'>OK</button>\n";
    $f .= "</p><p>\n";
    $f .= "<button type='submit' name='buttonPwCancel' id='buttonPwCancel' class='color-red'>Abbrechen</button>\n";
    $f .= "</p>\n";
    $f .= "<p>Passwort: 5 Zeichen<br/>\n";
    $f .= "(Gro&szlig;-, Kleinbuchstaben und Ziffern)</p>\n";
    $f .= "</form>\n";
    $f .= "</div>\n";
    print $f;
  }

  function buildEingabeForm($header, $hiddenValue="", $actKlasse="", $actFach="") {
    global $FAECHER;
    global $KLASSEN;
    $f = "<h1>$header</h1>\n";
    $f .= "<div class='eingabeform no-print'>\n";
    $f .= "<h1>$header</h1>\n";
    $f .= "<form action='eingabe.php' name='eingabeform' id='eingabeform' method='POST'>\n";
    $f .= "<input type='hidden' name='hiddenData' id='hiddenData' value='$hiddenValue'>\n";
    $f .= "<table>\n";
    $f .= "<tr><td colspan='3' class='noborder'>";
    $f .= "<label for='buchtitel'>Titel</label>\n";
    $f .= "<input type='text' name='buchtitel' id='buchtitel' maxlength='100' tabindex='1'>\n";
    $f .= "</td>";
    $f .= "<td class='noborder eingabe'>";
    $f .= "<button type='submit' class='color-yellow' name='buttonAnleitung' id='buttonAnleitung' tabindex='16'>Anleitung</button>";
    $f .= "</td>\n<td class='noborder eingabe'>";
    $f .= "<button type='submit' class='color-yellow' name='buttonPassword' id='buttonPassword' tabindex='17'>Passwort</button>";
    $f .= "</td>\n<td class='noborder eingabe'>";
    //$f .= "<button type='submit' class='color-red' name='buttonDelete' id='buttonDelete' tabindex='18'>L&ouml;schen</button>";
    $f .= "<button type='button' class='color-red' name='buttonDelete' id='buttonDelete' " .
      "onclick='showDeleteForm();' tabindex='18'>L&ouml;schen</button>";
    $f .= "</td>\n<td class='noborder eingabe'>";
    $f .= "<button type='submit' class='color-green' name='buttonInsert' id='buttonInsert' tabindex='19'>Speichern</button>";
    $f .= "</tr>\n";
    $f .= "<tr><td class='noborder eingabe'>";
    $f .= "<label for='buchnummer'>SbNr / ISBN</label><br />\n";
    $f .= "<input type='text' name='buchnummer' id='buchnummer' maxlength='30' tabindex='2'>\n";
    $f .= "</td><td class='noborder eingabe'>";
    $f .= "<label for='buchpreis'>Preis</label><br />\n";
    $f .= "<input type='text' name='buchpreis' id='buchpreis' maxlength='30' tabindex='3'>\n";
    $f .= "</td><td class='noborder eingabe'>";
    $f .= "<label for='faecher'>Fach</label><br />\n";
    $f .= populateOptionGroup("faecher", $actKlasse, $actFach);
    $f .= "</td><td class='noborder eingabe'>";
    $f .= "<label for='klassen'>Klasse</label><br />\n";
    $f .= populateOptionGroup("klassen", $actKlasse, $actFach);
    $f .= "</td><td class='noborder eingabe'>";
    $f .= "<label for='wiederverwendung'>Wiederverwendung</label><br />\n";
    $f .= "<input type='text' name='wiederverwendung' id='wiederverwendung' maxlength='30' value='0' tabindex='6'>\n";
    $f .= "</td><td class='noborder eingabe'>";
    $f .= "Handexemplar(LH)<br /><input type='checkbox' name='lehrerhand' id='lehrerhand' value='cbLH' tabindex='7'>\n";
    $f .= "</td><td class='noborder eingabe'>";
    $f .= "UeW<br /><input type='checkbox' name='uew' id='uew' value='cbUew' tabindex='8'>\n";
    $f .= "</td></tr>\n";
    $f .= "<tr><td class='noborder eingabe'>";
    $f .= "<button type='submit' class='color-blue' name='buttonBuchnummer' id='buttonBuchnummer' tabindex='9'>Nummer suchen</button>";
    $f .= "</td>\n<td class='noborder eingabe'>";
    $f .= "<button type='submit' class='color-blue' name='buttonTitel' id='buttonTitel' tabindex='10'>Titel suchen</button>";
    $f .= "</td>\n<td class='noborder eingabe'>";
    $f .= "<button type='submit' class='color-blue' name='buttonFach' id='buttonFach' tabindex='11'>Fach suchen</button>";
    $f .= "</td>\n<td class='noborder eingabe'>";
    $f .= "<button type='submit' class='color-blue' name='buttonKlasse' id='buttonKlasse' tabindex='12'>Klasse suchen</button>";
    $f .= "</td>\n<td class='noborder eingabe'>";
    $f .= "<button type='submit' class='color-blue' name='buttonEigene' id='buttonEigene' tabindex='13'>Eigene suchen</button>";
    $f .= "</td>\n<td class='noborder eingabe'>";
    $f .= "<button type='submit' class='color-blue' name='buttonAlles' id='buttonAlles' tabindex='14'>Alles suchen</button>";
    $f .= "</td>\n<td class='noborder eingabe'>";
    $f .= "<button type='button' onclick='gotoPrint();' " .
      "name='buttonPrint' id='buttonPrint' tabindex='15'>Drucken</button>";
    $f .= "</td></tr>\n";
    $f .= "</table>\n";
    $f .= "</form>\n";
    $f .= "</div>\n";
    print $f;
  }

  function getMeldungForm($meldung=null) {
    $f = "<div class='meldungform' name='meldungform' id='meldungform'>\n";
    $f .= "<table class='meldungtable'>\n";
    $f .= "<tr><th>Meldung</th></tr>\n";
    $f .= "<tr><td class='meldungtext'>$meldung</td></tr>\n";
    $f .= "<tr><td><p style='text-align: center;'>\n";
    $f .= "<button type='button' class='color-green singlebutton' \n" .
      "onclick='document.getElementById(\"meldungform\").style.visibility=\"hidden\"' \n" .
      "name='buttonEingabe' id='buttonEingabe'>OK</button>\n";
    $f .= "</p></td></tr>\n";
    $f .= "</table>\n";
    $f .= "</div>\n";
    return $f;
  }

  function getDeleteForm() {
    $f = "<div class='deleteform' name='deleteform' id='deleteform'>\n";
    $f .= "<table class='meldungtable'>\n";
    $f .= "<tr><th colspan=2>L&ouml;schen</th></tr>\n";
    $f .= "<tr><td class='meldungtext' colspan=2>Soll der Datensatz gel&ouml;scht werden?</td></tr>\n";
    $f .= "<tr><td style='width:50%; border:0'>\n";
    $f .= "<p style='text-align: center;'><button type='button' class='color-green' \n" .
      "onclick='deleteData();' \n" .
      "name='buttonDeleteOK' id='buttonDeleteOK'>OK</button></p></td>\n";
    $f .= "<td style='border:0;'><p style='text-align: center;'><button type='button' class='color-red' \n" .
      "onclick='document.getElementById(\"deleteform\").style.visibility=\"hidden\"' \n" .
      "name='buttonDeleteCancel' id='buttonDeleteCancel'>Abbrechen</button></p>\n";
    $f .= "</td></tr>\n";
    $f .= "</table>\n";
    $f .= "</div>\n";
    return $f;
  }

  function getOptionGroupFromDB($which, $res, $klasse, $fach) {
    global $FAECHER;
    if ($res == null) return;
    $tabindex =  " tabindex='4'";
    if ($which == "klassen") {
      $tabindex =  " tabindex='5'";
    }
    $o = "<select name='" . $which . "' id='" . $which . "'$tabindex>\n";
    $o .= "<option value='KEIN'>---</option>\n";
    $size = sizeof($res);
    for($i = 0; $i < $size; $i++) {
      $selectedKlasse = "";
      $selectedFach = "";
      switch ($which) {
        case "klassen" :
          if (strcmp(strtolower($res[$i][0]), strtolower($klasse)) == 0) $selectedKlasse = "selected";
          $o .= "<option $selectedKlasse value='" . $res[$i][0] . "'>" . $res[$i][0] . "</option>\n";
          break;
        case "faecher" :
          $fachlang = $res[$i][0];
          if ($fach)
            if (strcmp($FAECHER[$fach], $res[$i][0]) == 0) $selectedFach = "selected";
          $fachlang = array_keys($FAECHER, $res[$i][0])[0];
          $o .= "<option $selectedFach value='" . $fachlang . "'>" . $res[$i][0] . "</option>\n";
          break;
        default :
          $selected = "";
      }
    }
    $o .= "</select>\n";
    return $o;
  }

  function populateOptionGroup($which, $klasse="", $fach="") {
    $d = DB::getInstance();
    if ($d->connect()) {
      switch ($which) {
        case "faecher" :
          $q = "SELECT * FROM faecher ORDER BY Fach";
          break;
        case "klassen" :
          $q = "SELECT * FROM klassen ORDER BY Klasse";
          break;
        default :
          return "";
      }
      $res = $d->query($q, false, false);
      if ($res) {
        return getOptionGroupFromDB($which, $res, $klasse, $fach);
      }
    }
  }

?>
