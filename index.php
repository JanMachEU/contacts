<?php
require './Page.php';
require './Contact.php';

$page = new Page();
$contact = new Contact();

$page->head();
$contact->formCreateContact($_POST["name"], $_POST["phone"], $_POST["email"], $_POST["note"]);
$contact->contactList();

$page->footer();
?>
