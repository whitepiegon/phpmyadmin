<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * object the database export page
 *
 * @package    PhpMyAdmin
 */
if (! defined('PHPMYADMIN')) {
    exit;
}

/* Get the export class */
require_once 'libraries/export/export.class.php';

class ExportDatabase extends ExportPage
{
    public function getTemplate() {
        return ('pma_db_filename_template');
    }

    public function getBackButtonURL($db, $table) {
        return 'db_export.php' . PMA_URL_getCommon(array('db' => $db));
    }

    public function setRequestImplode() {
        if (isset($_REQUEST['table_select'])) {
            $_REQUEST['table_select'] = implode(",", $_REQUEST['table_select']);
        }
    }

    public function getExportPage($db, $table) {
        global $cfg;
        $active_page = 'db_export.php';
        include_once 'db_export.php';
        exit();
    }
}
?>
