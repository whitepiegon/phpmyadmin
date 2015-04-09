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

/**
 * Provides a common interface that will have to be implemented by all of the
 * export type. Some of the types will also implement other publicmethods, but
 * those are not declared here, because they are not implemented by all export
 * plugins.
 *
 * @package PhpMyAdmin
 */

abstract class ExportPage
{
    /**
     * Export type
     *
     * @var string
     */
    private $_exportType;

    /* ~~~~~~~~~~~~~~~~~~~~ Getters and Setters ~~~~~~~~~~~~~~~~~~~~ */

    /**
     * Gets the export type
     *
     * @return string
     */
    public function exportType()
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
    public function exportType($exportType)
    {
        $this->_text = $exportType;
    }
}
?>