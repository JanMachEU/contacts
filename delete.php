<?php
//require './Page.php';
require './Contact.php';

//$page = new Page();
$contact = new Contact();

header("Location: ./index.php");
$contact->delete($_GET["id"]);

?>
