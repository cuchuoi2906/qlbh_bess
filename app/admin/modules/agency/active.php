<?
require_once("../../bootstrap.php");
require_once 'inc_security.php';

use App\Models\Agency;

checkAddEdit("edit");

$id = getValue('id', 'int', 'POST', 0);

if ($id && $agency = Agency::findByID($id)) {
   $agency->agc_show = ($agency->agc_show == 1) ? 0 : 1;
   $agency->update();
}
return;
