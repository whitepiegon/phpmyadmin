<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * object the table export page
 *
 * @package    PhpMyAdmin-Export
 */
if (! defined('PHPMYADMIN')) {
    exit;
}

/* Get the export class*/
require_once 'libraries/export/export.class.php';

class ExportTable extends ExportPage
{
    public function getTemplate() {
        return ('pma_table_filename_template');
    }

    public function getBackButtonURL($db, $table) {
        return 'tbl_export.php' . PMA_URL_getCommon(
                array(
                    'db' => $db, 'table' => $table
                )
            );
    }

    public function getExportPage($db, $table) {
        global $cfg;
        $active_page = 'tbl_export.php';
        include_once 'tbl_export.php';
        exit();
    }
}
?>
