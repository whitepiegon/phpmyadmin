<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * object the server export page
 *
 * @package    PhpMyAdmin-Export
 */
if (! defined('PHPMYADMIN')) {
    exit;
}

/* Get the export class*/
require_once 'libraries/export/export.class.php';

class ExportServer extends ExportPage
{
    public function getTemplate() {
        return ('pma_server_filename_template');
    }

    public function getBackButtonURL($db, $table) {
        return 'server_export.php' . PMA_URL_getCommon();
    }

    public function setRequestImplode() {
        if (isset($_REQUEST['db_select'])) {
            $_REQUEST['db_select'] = implode(",", $_REQUEST['db_select']);
        }
    }

    public function getExportPage($db, $table) {
        global $cfg;
        $active_page = 'server_export.php';
        include_once 'server_export.php';
        exit();
    }
}
?>
