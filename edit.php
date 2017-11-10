<?php
require './Page.php';
require './Contact.php';
if (!isset($_GET["id"])) {
  header("Location: ./index.php");
}
$page = new Page();
$contact = new Contact();

$page->head();
$page->menu();

if (isset($_POST["name"]) && isset($_POST["phone"]) && isset($_POST["email"]) && isset($_POST["note"])) {
  $result = $contact->edit($_GET["id"], $_POST);
}
$contact->contactView($_GET["id"]);
if ($result) {
  echo "Kontakt upraven.";
}

$page->footer();
?>
