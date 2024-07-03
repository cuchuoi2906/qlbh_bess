<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/13/18
 * Time: 13:15
 */

use App\Models\Contact;

require_once 'inc_security.php';

checkAddEdit("edit");

$id = getValue('id', 'int', 'POST', 0);
$contact = Contact::findByID($id);
if ($contact) {
    $contact->read = 1;
    $contact->update();
}