<?php
/**
 * @author Jan Mach
 * @version v1
 */

/**
 * Class for creating HTML elements
 */
class Page
{

  function __construct()
  {
  }

  // Prints HTML head
  public function head($title='Contacts')
  {
    $head = "<!DOCTYPE html>\n";
    $head .= "<html>\n";
      $head .= "\t<head>\n";
        $head .= "\t\t<meta charset=\"utf-8\">\n";
        $head .= "\t\t<title>" . htmlspecialchars($title, ENT_IGNORE) . "</title>\n";
      $head .= "\t</head>\n";
      $head .= "\t<body>\n";
    echo $head;
  }

  // prints menu
  public function menu()
  {
    $menu = "<a href=\"./index.php\">Domů</a>";
    echo $menu;
  }

  // Prints HTML footer / end
  public function footer()
  {
    $footer = "\t</body>\n</html>";
    echo $footer;
  }
}
?>
