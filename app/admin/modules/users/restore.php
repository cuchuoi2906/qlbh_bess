<?
require_once("../../bootstrap.php");
require_once 'inc_security.php';

//check quyền them sua xoa
checkAddEdit("delete");
try {
    $recordId = getValue("record_id", "int", "GET", 0);
    if ($recordId) {
        $user = \App\Models\Users\Users::withTrash()->findByID($recordId);
        if ($user) {
            $user->restore();
            \VatGia\Queue\Facade\Queue::pushOn(\App\Workers\CountChildUserWorker::$name, \App\Workers\CountChildUserWorker::class, [
                'id' => $user->id
            ]);
        }
    }
} catch (Exception $e) {

}

redirect(url_back());
?>