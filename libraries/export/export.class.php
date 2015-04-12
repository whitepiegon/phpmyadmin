<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Abstract class for the export page
 *
 * @package PhpMyAdmin
 */
if (! defined('PHPMYADMIN')) {
    exit;
}

abstract class ExportPage
{
    protected $properties;//not yet used

    /**
     * Export type
     *
     * @var string
     */
    private $_exportType;

    /**
     * Gets the export type
     *
     * @return string
     */
    public function getExportType()
    {
        return $this->_exportType;
    }

    /**
     * Sets the export type
     *
     * @param string
     *
     * @return void
     */
    public function setExportType($exportType)
    {
        $this->_exportType = $exportType;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    //abstract protected function setProperties();

    public function compressExport($dump_buffer, $compression, $filename)
    {
        if ($compression == 'zip' && @function_exists('gzcompress')) {
            $zipfile = new ZipFile();
            $zipfile->addFile(
                $dump_buffer,
                substr($filename, 0, -4)
            );
            $dump_buffer = $zipfile->file();
        } elseif ($compression == 'gzip' && PMA_gzencodeNeeded()) {
            // without the optional parameter level because it bugs
            $dump_buffer = gzencode($dump_buffer);
        }
        return $dump_buffer;
    }
    /**
     * Returns HTML containing the header for a displayed export
     *
     * @param string $export_type the export type
     * @param string $db          the database name
     * @param string $table       the table name
     *
     * @return array the generated HTML and back button
     */
    public function getHtmlForDisplayedExportHeader($db, $table)
    {
        $html = '<div style="text-align: ' . $GLOBALS['cell_align_left'] . '">';
        /**
         * Displays a back button with all the $_REQUEST data in the URL
         * (store in a variable to also display after the textarea)
         */
        $back_button = '<p>[ <a href="';
        $back_button .= $this->getBackButtonURL($db, $table);
        $export_type = $this->_exportType;
        // Convert the multiple select elements from an array to a string
        if ($export_type !=='table') $this->setRequestImplode();

        foreach ($_REQUEST as $name => $value) {
            if (!is_array($value)) {
                $back_button .= '&amp;' . urlencode($name) . '=' . urlencode($value);
            }
        }
        $back_button .= '&amp;repopulate=1">' . __('Back') . '</a> ]</p>';

        $html .= $back_button
            . '<form name="nofunction">'
            . '<textarea name="sqldump" cols="50" rows="30" '
            . 'id="textSQLDUMP" wrap="OFF">';

        return array($html, $back_button);
    }

    /**
     * Return the filename and MIME type for export file
     *
     * @param string $remember_template whether to remember template
     * @param object $export_plugin     the export plugin
     * @param string $compression       compression asked
     * @param string $filename_template the filename template
     *
     * @return array the filename template and mime type
     */

    public function getExportFilenameAndMimetype(
        $remember_template, $export_plugin, $compression,
        $filename_template
    ) {
        if (! empty($remember_template)) {
            $GLOBALS['PMA_Config']->setUserValue(
                $this->getTemplate(),
                $this->getFileTemplate(),
                $filename_template
            );
        }

        $filename = PMA_Util::expandUserString($filename_template);
        // remove dots in filename (coming from either the template or already
        // part of the filename) to avoid a remote code execution vulnerability
        $filename = PMA_sanitizeFilename($filename, $replaceDots = true);

        // Grab basic dump extension and mime type
        // Check if the user already added extension;
        // get the substring where the extension would be if it was included
        $extension_start_pos = /*overload*/mb_strlen($filename) - /*overload*/mb_strlen(
            $export_plugin->getProperties()->getExtension()
        ) - 1;
        $user_extension = /*overload*/mb_substr(
        $filename, $extension_start_pos, /*overload*/mb_strlen($filename)
        );
        $required_extension = "." . $export_plugin->getProperties()->getExtension();
        if (/*overload*/mb_strtolower($user_extension) != $required_extension) {
        $filename  .= $required_extension;
        }
        $mime_type = $export_plugin->getProperties()->getMimeType();

        // If dump is going to be compressed, set correct mime_type and add
        // compression to extension
        if ($compression == 'gzip') {
            $filename  .= '.gz';
            $mime_type = 'application/x-gzip';
        } elseif ($compression == 'zip') {
            $filename  .= '.zip';
            $mime_type = 'application/zip';
        }
        return array($filename, $mime_type);
    }

    protected function setConfigUserValue()
    {
        $exportType = $this->getExportType();
        $GLOBALS['PMA_Config']->setUserValue(
            fixText('pma_', $exportType, '_filename_template'),
            fixText('Export/file_template_', $exportType, ''),
            $filename_template
        );
    }

    protected function fixText ($text1, $text2, $text3)
    {
        return $text1 . $text2 . $text3;
    }

    public function getFileTemplate()
    {
        return $this->fixText ('Export/file_template_',$this->_exportType,'');
    }
}
?>