<?php
/**
 * File with Contact class
 * @author Jan Mach
 * @version v1
 */

/**
 * Class for handling contacts
 */
class Contact
{

  function __construct()
  {

  }

  /**
   * Creates form or adds contact to the list
   *
   * @param  string $name     Full name of contact
   * @param  string $phone    Telephone number
   * @param  string $email    e-mail
   * @param  string $note     Note for the contact
   * @param  string $filename Wich contact list add the contact to
   * @return void
   */
  public function formCreateContact($name = "", $phone = "", $email = "", $note = "", $filename = "./contacts.json")
  {
    if ($name != "" && ($phone != "" || $email != "")) {
      $this->add($name, $phone, $email, $note, $filename);
    }
    $form = "<form action=\"index.php\" method=\"post\" id=\"form\">";
      $form .= "<h3>Přidat kontakt</h3>\n";
      $form .= "<table>";
      $form .= "<tr><th> Jméno </th>";
      $form .= "<td><input type=\"text\" name=\"name\" id=\"name\" required></td></tr>\n";
      $form .= "<tr><th> Tel. číslo</th>";
      $form .= "<td><input type=\"tel\" name=\"phone\" id=\"phone\"></td><tr>\n";
      $form .= "<tr><th> email</th>";
      $form .= "<td><input type=\"email\" name=\"email\" id=\"email\"></td><tr>\n";
      $form .= "<tr><th> Poznámka</th>";
      $form .= "<td><textarea rows=\"1\" name=\"note\" id=\"note\" form=\"form\"></textarea></td></tr>\n";
      $form .= "<tr><td><p>Povinné:<br>jméno + telefon <br>nebo<br>jméno + email</p></td>";
      $form .= "<td><input type=\"submit\"></td></tr>";
    $form .= "</table></form>";
    echo $form;
  }

  /**
   * Prints list of contacts
   * @param  string $filename Wich contact list add the contact to
   * @return void
   */
  public function contactList($filename = "./contacts.json")
  {
    if (file_exists($filename)) {
      // reads content of the file (saved contacts)
      $content = file_get_contents($filename);
      // add new contact to list
      $contacts = json_decode($content, true);
      // print contact list
      echo "\t\t<table>\n";
      echo "\t\t\t<tr>\n";
      echo "\t\t\t\t<th>Jméno</th><th>Telefon</th><th>Email</th><th>Poznámka</th>\n";
      echo "\t\t\t</tr>\n";
      foreach ($contacts["contacts"] as $key => $value) {
        echo "\t\t\t<tr>\n";
        echo "\t\t\t\t<td>" . htmlspecialchars($contacts["contacts"][$key]["name"], ENT_IGNORE) . "</td>\n";
        echo "\t\t\t\t<td>" . htmlspecialchars($contacts["contacts"][$key]["phone"], ENT_IGNORE) . "</td>\n";
        echo "\t\t\t\t<td>" . htmlspecialchars($contacts["contacts"][$key]["email"], ENT_IGNORE) . "</td>\n";
        echo "\t\t\t\t<td>" . htmlspecialchars($contacts["contacts"][$key]["note"], ENT_IGNORE) . "</td>\n";
        echo "\t\t\t\t<td><a href='edit.php?id=" . htmlspecialchars($contacts["contacts"][$key]["id"], ENT_IGNORE) . "'>Detail / Úprava</a></td>\n";
        echo "\t\t\t</tr>\n";
      }
      echo "\t\t</table>\n";
    }
  }
  /**
   * Add contact
   * @param  string $name     Full name of contact
   * @param  string $phone    Telephone number
   * @param  string $email    e-mail
   * @param  string $note     Note for the contact
   * @param  string $filename Wich contact list add the contact to
   * @return void
   */
  public function add($name, $phone, $email, $note, $filename = "./contacts.json")
  {
    if (file_exists($filename)) {
      // reads content of the file (saved contacts)
      $f = fopen($filename, "r");
      $content = fread($f, filesize($filename));
      fclose($f);
      // add new contact to list
      $contacts = json_decode($content, true);
    } else {
      $contacts = array('contacts' => array());
    }
    $person = array('id' => htmlspecialchars($name, ENT_IGNORE) . "_" . uniqid(), 'name' => htmlspecialchars($name, ENT_DISALLOWED), 'phone' => htmlspecialchars($phone, ENT_DISALLOWED), 'email' => htmlspecialchars($email, ENT_DISALLOWED), 'note' => htmlspecialchars($note, ENT_DISALLOWED));
    if ($contacts["contacts"] == null) $contacts["contacts"] = array(); // creates array
    array_push($contacts["contacts"], $person);
    $content_new = json_encode($contacts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    // rewrites or creates the file with updated contact list
    $f = fopen($filename, "w");
    fwrite($f, $content_new);
    fclose($f);
  }

  /**
   * Edit contact
   * @param  string $id       contact id
   * @param  string $filename Wich contact list add the contact to
   * @return bool             Was the contact edited?
   */
  public function edit($id, $post, $filename = "./contacts.json")
  {
    $ret = FALSE;
    if (file_exists($filename)) {
      // reads content of the file (saved contacts)
      $f = fopen($filename, "r");
      $content = fread($f, filesize($filename));
      fclose($f);
      // add new contact to list
      $contacts = json_decode($content, true);
    } else {
      return false;
    }
    $person = array('id' => htmlspecialchars($name, ENT_IGNORE) . "_" . uniqid(), 'name' => htmlspecialchars($name, ENT_DISALLOWED), 'phone' => htmlspecialchars($phone, ENT_DISALLOWED), 'email' => htmlspecialchars($email, ENT_DISALLOWED), 'note' => htmlspecialchars($note, ENT_DISALLOWED));

    foreach ($contacts["contacts"] as $key => $value) {
      if ($contacts["contacts"][$key]["id"] == $id) {
        $contacts["contacts"][$key]["name"] = $post["name"];
        $contacts["contacts"][$key]["phone"] = $post["phone"];
        $contacts["contacts"][$key]["email"] = $post["email"];
        $contacts["contacts"][$key]["note"] = $post["note"];
        $ret = TRUE;
      }
    }
    if ($ret) {
      $content_new = json_encode($contacts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
      // rewrites or creates the file with updated contact list
      $f = fopen($filename, "w");
      fwrite($f, $content_new);
      fclose($f);
    }
    return $ret;
  }
  /**
   * Prints contact informations
   * @param  string $id       contact id
   * @param  string $filename file with saved contacts (JSON)
   * @return void
   */
  public function contactView($id, $filename = "./contacts.json")
  {
    if (file_exists($filename)) {
      // reads content of the file (saved contacts)
      $content = file_get_contents($filename);
      // add new contact to list
      $contacts = json_decode($content, true);
      // print contact list
      foreach ($contacts["contacts"] as $key => $value) {
        if ($contacts["contacts"][$key]["id"] == $id) {
          echo "<p><a href=\"./delete.php?id=" . $contacts["contacts"][$key]["id"] . "\">Smazat kontakt</a></p>\n";
          $shown = true;
          $form = "<form action=\"edit.php?id=" . htmlspecialchars($id, ENT_IGNORE) . "\" method=\"post\" id=\"form\">";
            $form .= "<h3>Upravit kontakt</h3>";
            $form .= "<label for=\"name\">Jméno</label> ";
            $form .= "<input type=\"text\" name=\"name\" id=\"name\" value=\"" . htmlspecialchars($contacts["contacts"][$key]["name"], ENT_IGNORE) . "\" required><br>\n";
            $form .= "<label for=\"phone\">Tel. číslo</label> ";
            $form .= "<input type=\"tel\" name=\"phone\" id=\"phone\" value=\"" . htmlspecialchars($contacts["contacts"][$key]["phone"], ENT_IGNORE) . "\"><br>\n";
            $form .= "<label for=\"email\">email</label> ";
            $form .= "<input type=\"email\" name=\"email\" id=\"email\" value=\"" . htmlspecialchars($contacts["contacts"][$key]["email"], ENT_IGNORE) . "\"><br>\n";
            $form .= "<label for=\"note\">Poznámka</label> ";
            $form .= "<textarea rows=\"1\" name=\"note\" id=\"note\" form=\"form\">" . htmlspecialchars($contacts["contacts"][$key]["note"], ENT_IGNORE) . "</textarea><br>\n";
            $form .= "<input type=\"submit\">";
          $form .= "</form>";
          echo $form;
        }
      }
      if (!$shown) {
        echo "Kontakt nenalezen!";
      }
    }else {
      echo "Soubor " . $filename . " nenalezen!";
    }
  }

  /**
   * delete contact from file
   * @param  string $id       contact id
   * @param  string $filename Wich contact list add the contact to
   * @return void
   */
  public function delete($id, $filename = "./contacts.json")
  {
    if (file_exists($filename)) {
      // reads content of the file (saved contacts)
      $f = fopen($filename, "r");
      $content = fread($f, filesize($filename));
      fclose($f);
      // add new contact to list
      $contacts = json_decode($content, true);
    }
    foreach ($contacts["contacts"] as $key => $value) {
      if ($contacts["contacts"][$key]["id"] == $id) $deletekey = $key;
    }
    if (isset($deletekey)) array_splice($contacts["contacts"], $deletekey, 1);
    $content_new = json_encode($contacts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    // rewrites or creates the file with updated contact list
    $f = fopen($filename, "w");
    fwrite($f, $content_new);
    fclose($f);
  }
}
 ?>
