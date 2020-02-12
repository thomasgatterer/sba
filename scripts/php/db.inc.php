<?php
  include_once('constants.inc.php');

  /*
    class DB
    access to database (singleton-object)
  */

  class DB {
    static private $instance = false;
    static private $link = false;
    /*
      __construct and __clone private declaration
      disable multiple instances of DB
    */
    private function __construct() {}
    private function __clone() {}
    /*
      function getInstance
      access to class DB
      @return reference on class DB
    */
    public static function getInstance() {
      if (! self::$instance) {
        self::$instance = new DB();
      }
      return self::$instance;
    }
    /*
      function connect
      connection to database
      @return true on success or false
    */
    public function connect() {
      self::$link = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
      if (! self::$link) {
        return false;
      }
      return true;
    }
    /*
      function getAssocResult
      @return assocResult
    */
    public function getAssocResult($queryString) {
      if (! self::$link) return null;
      return self::$link->mysqli_query($queryString);
    }
    /*
      function getFetchArray
      @return fetchArray
    */
    public function getFetchArray($result) {
      if (! self::$link) return null;
      return self::$link->mysqli_fetch_array($result);
    }
    /*
      function query
      @param queryString SELECT-command
      @param withFieldNames first dataset contains fieldnames
      @param assoc true = associative array
      @return associative array containing datasets or false
    */
    public function query($queryString, $withFieldNames=false,$assoc=true) {
      if (! self::$link) return null;
      $result = mysqli_query(self::$link, $queryString);
      if (! $result) {
        return false;
      }
      $rows = array();
      if ($withFieldNames) {
        $numFields = mysqli_num_fields($result);
        $i = 0;
        $n = array();
        for ($i = 0; $i < $numFields; $i++) {
          $n[] = mysqli_fetch_field_direct($result, $i)->name;
        }
        $rows[] = $n;
      }
      if ($assoc) {
        while ($row = mysqli_fetch_assoc($result)) {
          $rows[] = $row;
        }
      }
      else {
        while ($row = mysqli_fetch_row($result)) {
          $rows[] = $row;
        }
      }
      mysqli_free_result($result);
      return $rows;
    }
    /*
      function queryTable
      @param queryString SELECT-command
      @param withFieldNames first dataset contains fieldnames
      @return associative array containing datasets or false
    */
    public function queryTable($queryString, $withFieldNames=false) {
      if (! self::$link) return null;
      $result = mysqli_query(self::$link, $queryString);
      if (! $result) {
        return false;
      }
      $table = "<table class='resulttable'>\n";
      $numFields = mysqli_num_fields($result);
      if ($withFieldNames) {
        $table .= "<tr>\n";
        $i = 0;
        for ($i = 0; $i < $numFields; $i++) {
          $table .= "<th>" . mysqli_fetch_field_direct($result, $i)->name . "</th>";
        }
        $table .= "</tr>\n";
      }
      while ($row = mysqli_fetch_row($result)) {
        $table .= "<tr>";
        for ($i = 0; $i < $numFields; $i++) {
          if ($i == 0) {
            $table .= "<td><a href='javascript:sendRequest(\"query.php\", \"Nr=" .
              trim($row[$i]) . "\", NUMMER)'>" . trim($row[$i]) . "</a></td>";
          }
          else {
            $table .= "<td>" . trim($row[$i]) . "</td>";
          }

        }
        $table .= "</tr>\n";
      }
      $table .= "</table>\n";
      mysqli_free_result($result);
      print $table;
    }
    /*
      function doQuery
      @param queryString for INSERT, UPDATE, DELETE, CREATE TABLE
    */
    public function doQuery($queryString) {
      if (! self::$link) return null;
      return mysqli_query(self::$link, $queryString);
    }
    /*
      function queryOne
      @param queryString for SELECT that returns only one dataset
      @param assoc = true returns associative array
    */
    public function queryOne($queryString, $assoc=true) {
      $rows = self::query($queryString, false, $assoc);
      if (! $rows) {
        return false;
      }
      return $rows[0]; // return the first dataset found
    }
    /*
      function queryField
      @param queryString SELECT with syntax SELECT <FIELD> FROM ...
      @return content of <FIELD> or false
    */
    public function queryField($queryString) {
      $fields = explode(" ", $queryString);
      $field = $fields[1];
      $rows = self::queryOne($queryString);
      if (! $rows) {
        return false;
      }
      return $rows[$field];
    }
    /*
      function insert
      @param insertString contains INSERT-command
      @return Insert-ID, 0 (if no autoincrement) or false on error
    */
    public function insert($insertString) {
      if (! self::$link) return null;
      $ret = mysqli_query(self::$link, $insertString);
      if (! $ret) {
        return false;
      }
      return @mysqli_insert_id();
    }
    /*
      function update
      @param updateString contains UPDATE-command
      @return true or false
    */
    public function update($updateString) {
      if (! self::$link) return null;
      return self::$link->mysqli_query($updateString);
    }
    /*
      function toggle
      toggles value 0 or 1
      @param table table in database
      @param field to toggle
      @param where condition for toggling
    */
    public function toggle($table, $field, $where) {
      if (! self::$link) return null;
      $f = self::queryField("SELECT $field FROM $table WHERE $where");
      $f = ($f == 1 ? $f = 0 : $f = 1);
      return self::$link->mysqli_query("UPDATE $table SET $field = $f WHERE $where");
    }
    /*
      function delete
      @param deleteString contains DELETE-command
      @return true or false
    */
    public function delete($deleteString) {
      if (! self::$link) return null;
      return mysqli_query(self::$link, $deleteString);
    }
    /*
      function getNumRows
      @param result = resultSet to count
      @return count rows
    */
    public function getNumRows($result) {
      if (! self::$link) return null;
      return mysqli_num_rows($result);
    }

    /*
      function getLastID
      @param table (with ID-field)
      @return null or highest ID
    */
    public function getLastID($table) {
      if (! self::$link) return null;
      $q = "SELECT ID FROM $table ORDER BY ID DESC LIMIT 1";
      $res = self::doQuery($q);
      if ($res) {
        $a = mysqli_fetch_array($res, MYSQLI_NUM);
        return $a[0];
      }
      else {
        return 0;
      }
    }

    public function getTitlesForUser($user, $printing=false) {
      if (! self::$link) return null;
      // search in tables 1a .. 8d
      $klassen = mysqli_query(self::$link, "SELECT Klasse FROM klassen ORDER By Klasse");
      $kl = [];
      foreach ($klassen as $k) {
        $kl[] = $k['Klasse'];
      }
      mysqli_free_result($klassen);
      // go through Klassen
      $table = "<table class='resulttable'>\n";
      if ($printing) {
        $table = "<table class='printtable'>\n";
      }
      $table .= "<tr><th>Nr</th><th>Titel</th><th>Preis</th><th>Fach</th>" .
        "<th>Anzahl</th><th>Wert</th><th>Klasse</th><th>Jahr(LH)</th></tr>\n";
      foreach($kl as $k) {
        $q = "SELECT * FROM " . strtolower($k) . " WHERE Lehrer='$user'";
        $klassebuch = mysqli_query(self::$link, $q);
        if ($klassebuch) {
          foreach($klassebuch as $kb) {
            $table .= "<tr>";
            if ($printing) {
              $table .= "<td>" . trim($kb['Nr']) . "</td>";
            }
            else {
              $table .= "<td><a href='javascript:sendRequest(\"query.php\", \"Nr=" .
                trim($kb['Nr']) . "\", NUMMER)'>" . trim($kb['Nr']) . "</a></td>";
            }
            $table .= "<td>" . trim($kb['Titel']) . "</td>";
            $table .= "<td>" . trim($kb['Preis']) . "</td>";
            $table .= "<td>" . trim($kb['Fach']) . "</td>";
            $table .= "<td>" . trim($kb['Anzahl']) . "</td>";
            $table .= "<td>" . trim($kb['Wert']) . "</td>";
            $table .= "<td>" . strtoupper($k) . "</td>";
            $table .= "<td>&nbsp;</td>";
            $table .= "</tr>\n";
          }
        }
        mysqli_free_result($klassebuch);
      }
      $q = "SELECT * FROM lehrerhand WHERE Lehrer='$user'";
      $lehrerhand = mysqli_query(self::$link, $q);
      if ($lehrerhand) {
        foreach($lehrerhand as $lh) {
          $table .= "<tr>";
          if ($printing) {
            $table .= "<td>" . trim($lh['Nr']) . "</td>";
          }
          else {
            $table .= "<td><a href='javascript:sendRequest(\"query.php\", \"Nr=" .
              trim($lh['Nr']) . "\", NUMMER)'>" . trim($lh['Nr']) . "</a></td>";
          }
          $table .= "<td>" . trim($lh['Titel']) . "</td>";
          $table .= "<td>LH</td>";
          $table .= "<td>" . trim($lh['Fach']) . "</td>";
          $table .= "<td>&nbsp;</td>";
          $table .= "<td>&nbsp;</td>";
          $table .= "<td>&nbsp;</td>";
          $table .= "<td>" . YEARPREFIX . trim($lh['Jahr']) . "</td>";
          $table .= "</tr>\n";
        }
      }
      $table .= "</table>";
      print $table;
    }

    function getKlasse($klasse, $printing=false) {
      if (! self::$link) return null;
      $tableclass = "resulttable";
      if ($printing) {
        $tableclass = "printtable";
      }
      $table = "<table class='$tableclass'>\n";
      $table .= "<tr><th>Nr</th><th>Titel</th><th>Preis</th><th>Fach</th>" .
        "<th>Klasse</th><th>Anzahl</th><th>Lehrer</th><th>Wert</th></tr>\n";
      $kl = mysqli_query(self::$link, "SELECT * FROM " . strtolower($klasse) . " ORDER BY Lehrer");
      if ($kl) {
        foreach($kl as $k) {
          $table .= "<tr>";
          if ($printing) {
            $table .= "<td>" . trim($k['Nr']) . "</td>";
          }
          else {
            $table .= "<td><a href='javascript:sendRequest(\"query.php\", \"Nr=" .
              trim($k['Nr']) . "\", NUMMER)'>" . trim($k['Nr']) . "</a></td>";
          }
          $table .= "<td>" . trim($k['Titel']) . "</td>";
          $table .= "<td>" . trim($k['Preis']) . "</td>";
          $table .= "<td>" . trim($k['Fach']) . "</td>";
          $table .= "<td>" . strtoupper($klasse) . "</td>";
          $table .= "<td>" . trim($k['Anzahl']) . "</td>";
          $table .= "<td>" . trim($k['Lehrer']) . "</td>";
          $table .= "<td>" . trim($k['Wert']) . "</td>";
          $table .= "</tr>\n";
        }
      }
      $table .= "</table>";
      print $table;
    }

    function deleteTitle($user, $nr, $klasse, $lh) {
      if (! self::$link) return null;
      if (! $user) return null;
      if (! $nr) return null;
      if (! $klasse) return null;
      $q = "DELETE FROM " . strtolower($klasse) . " WHERE Nr=$nr AND Lehrer='$user'";
      self::delete($q);
      if ($lh) {
        $letztesjahr = ACTYEAR - 2000 - 1;
        $q = "DELETE FROM lehrerhand WHERE Nr=$nr AND Lehrer='$user' AND Jahr=$letztesjahr";
        $res1 = mysqli_query(self::$link, $q);
      }
    }

    function insertTitle($user, $nr, $titel, $preis, $fach, $klasse, $lh, $uew) {
      if (! self::$link) return null;
      if (! $user) return null;
      if (! $nr) return null;
      if (! $titel) return null;
      if (! $preis) return null;
      if (! $fach && ! $lh) return null;
      if (! $klasse && ! $lh) return null;
      // Klasse -> lowercase
      if ($klasse) {
        $klasse = strtolower($klasse);
      }
      // Titel ändern, wenn UeW und wenn nicht in Schulbuchliste
      $res = null;
      if ($uew) {
        $res = mysqli_query(self::$link, "SELECT * FROM liste WHERE Nr=$nr");
        if (! $res || mysqli_num_rows($res) === 0) {
          $titel = "UeW " . $titel;
        }
      }
      // alle Felder ausgefüllt -> einfügen in 1a .. 8d
      if ($fach && $klasse) {
        // bereits eingefügt? Wenn nicht, dann weiter
        $q = "SELECT * FROM $klasse WHERE Nr=$nr AND Lehrer='$user'";
        $res = mysqli_query(self::$link, $q);
        if (! $res || mysqli_num_rows($res) === 0) {
          $lastIndex = self::getLastID($klasse) + 1;
          $q = "INSERT INTO $klasse VALUES(" .
            "$lastIndex, $nr, '$titel', $preis, '$fach', 0, '$user', 0, 0)";
          self::insert($q);
        }
      }
      // einfügen als Lehrerhandexemplar
      if ($lh && $fach != "---") {
        // prüfen, ob LH im letzten Schuljahr erhalten
        $letztesjahr = ACTYEAR - 2000 - 1;
        $q = "SELECT * FROM lehrerhand WHERE Nr=$nr AND Lehrer='$user' AND Jahr=$letztesjahr";
        $res = mysqli_query(self::$link, $q);
        if (! $res || mysqli_num_rows($res) === 0) {
          $lastIndex = self::getLastID("lehrerhand") + 1;
          // prüfen, ob in Anhangliste -> kein Lehrerhandexemplar möglich
          $q = "SELECT * FROM liste WHERE Nr=$nr AND Anhang='ja'";
          $res = mysqli_query(self::$link, $q);
          if (! $res || mysqli_num_rows($res) === 0) {
            $q = "INSERT INTO lehrerhand VALUES(" .
              "$lastIndex, $nr, '$titel', '$fach', $letztesjahr, '$user')";
            self::insert($q);
          }
        }
      }
    }
  }
?>
