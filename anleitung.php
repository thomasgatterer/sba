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

  if (isset($_POST['buttonEingabe'])) {
    header("Location: eingabe.php");
    exit;
  }

  buildHTMLHeader();
?>

  <h1>Anleitung für das Web-Interface zu SBA @ AGI</h1>
  <table class="anleitungtable">
    <tr>
      <th>Aktion</th><th>Erkl&auml;rung</th>
    </tr>
    <tr>
      <td class="fett">Passwort</td>
      <td>Ein neues Passwort kann eingegeben werden.
        Das Passwort muss genau 5 Zeichen enthalten und darf
        aus Gro&szlig;- und Kleinbuchstaben sowie Ziffern bestehen.
      </td>
    </tr>
    <tr>
      <td class="fett">L&ouml;schen</td>
      <td>
        Beim L&ouml;schen eines Datensatzes müssen folgende Felder ausgef&uuml;llt sein:
        <ul>
          <li>Nummer (SbNr / ISBN)</li>
          <li>Klasse</li>
          <li>Checkbox Handexemplar(LH), wenn das Handexemplar gel&ouml;scht werden soll</li>
        </ul>
      </td>
    </tr>
    <tr>
      <td class="fett">Speichern</td>
      <td>
        Beim Speichern eines Datensatzes müssen folgende Felder ausgef&uuml;llt sein:
        <ul>
          <li>Titel</li>
          <li>Nummer (SbNr / ISBN)</li>
          <li>Preis</li>
          <li>Fach</li>
          <li>Klasse</li>
          <li>Wiederverwendung, wenn Exemplare zur Wiederverwendung vorhanden sind,
            sonst den Wert 0 eintragen (ist bereits voreingestellt).
          </li>
          <li>Checkbox Handexemplar(LH), wenn ein Handexemplar zus&auml;tzlich gew&uuml;nscht wird.</li>
          <li>Checkbox UeW (Unterrichtsmittel eigener Wahl),
            wenn ein Titel nicht in der Schulbuchliste vorhanden ist.
            Als Nummer muss eine g&uuml;ltige ISBN-Nummer (10 bzw. 13 Ziffern)
            eingetragen werden.
          </li>
        </ul>
      </td>
    </tr>
    <tr>
      <td class="fett">Nummer suchen</td>
      <td>Ein Titel aus der Schulbuchliste wird an Hand seiner SbNr (Schulbuchnummer) gesucht.
        Dies funktioniert nur mit Titeln aus der Schulbuchliste, nicht aber mit beliebigen ISBN-Nummern
        (Unterrichtsmittel eigener Wahl).
      </td>
    </tr>
    <tr>
      <td class="fett">Titel suchen</td>
      <td>Ein Titel aus der Schulbuchliste wird an Hand seines Titels gesucht.
        Am besten wird ein signifikanter Teil des Titels eingegeben (z.B. Treff f&uuml;r Treffpunkt Deutsch).
      </td>
    </tr>
    <tr>
      <td class="fett">Fach suchen</td>
      <td>Die verf&uuml;gbaren Titel des Faches aus der Schulbuchliste werden angezeigt.</td>
    </tr>
    <tr>
      <td class="fett">Klasse suchen</td>
      <td>Die bereits eingegebenen Titel f&uuml;r eine Klasse werden angezeigt.</td>
    </tr>
    <tr>
      <td class="fett">Eigene suchen</td>
      <td>Die bereits eingegebenen eigenen Titel (Klassen und Lehrerhandexemplare) werden angezeigt.</td>
    </tr>
    <tr>
      <td class="fett">Alles suchen</td>
      <td>Die gesamte Schulbuchliste wird angezeigt (Anzeige kann einige Zeit dauern, da sehr viele Titel vorhanden sind).</td>
    </tr>
    <tr>
      <td class="fett">Drucken</td>
      <td>Ein Druckformular wird aufgerufen (entweder f&uuml;r die gew&auml;hlte Klasse oder die eigenen Titel).
        Mit der Druckfunktion des Browsers kann die Liste ausgedruckt werden.
      </td>
    </tr>
    <tr>
      <td class="fett">Checkbox Handexemplar(LH)</td>
      <td>Ankreuzen, wenn ein Handexemplar gew&uuml;nscht wird.
        Handexemplare k&ouml;nnen nur eingegeben werden, wenn folgende Bedingungen erf&uuml;llt sind:
        <ul>
          <li>Titel nicht in der Anhangliste (in der Spalte Anhang gekennzeichnet mit ja)</li>
          <li>Titel kein Unterrichtsmittel eigener Wahl (UeW)</li>
        </ul>
      </td>
    </tr>
    <tr>
      <td class="fett">Checkbox UeW</td>
      <td>Ankreuzen, wenn ein Titel au&szlig;erhalb der Schulbuchliste bestellt wird.
        Dabei muss im Feld SbNr / ISBN eine g&uuml;ltige ISBN-Nummer (10 oder 13 Ziffern) eingegeben werden.
        Der Preis muss selbst ermittelt werden. Ein kostenloses Lehrerhandexemplar ist nicht m&ouml;glich.
      </td>
    </tr>
    <tr>
      <td class="fett">Listeneintr&auml;ge</td>
      <td>
        Die erste Spalte der Listen enth&auml;lt die Buchnummer (SbNr oder ISBN) und kann angeklickt werden.
        Dabei werden die Felder - wenn m&ouml;glich - ausgef&uuml;llt.
      </td>
    </tr>
    <tr>
      <td class="fett">Abmeldung</td>
      <td>Eine Abmeldung ist nicht notwendig. Durch Schlie&szlig;en des Browser-Fensters werden die Anmeldedaten gel&ouml;scht.
      </td>
    </tr>
    <tr>
      <td class="fett">Infos</td>
      <td>
        Datenbank und Java-Programm: MMag. Andreas Knapp 2002<br />
        Web-Interface: Mag. Thomas Neuhold, Februar 2020<br />
        Getestet mit Firefox, Chrome, Opera
      </td>
    </tr>
  </table>
  <p style="text-align: center;">
    <button type="button" class="color-green singlebutton"
      onclick="window.location.href='eingabe.php'" 
      name="buttonEingabe" id="buttonEingabe">Zur&uuml;ck</button>
  </p>

  <p>&nbsp;</p>
<?php
  buildHTMLFooter();
?>
