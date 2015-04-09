<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * HTML-Word export code
 *
 * @package    PhpMyAdmin-Export
 * @subpackage HTML-Word
 */
if (! defined('PHPMYADMIN')) {
    exit;
}

/* Get the export interface */
require_once 'libraries/plugins/ExportPlugin.class.php';

/**
 * Handles the export for the HTML-Word format
 *
 * @package    PhpMyAdmin-Export
 * @subpackage HTML-Word
 */
class ExportServer extends ExportType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setProperties();
    }

    /**
     * Sets the export HTML-Word properties
     *
     * @return void
     */
    protected function setProperties()
    {
        $props = 'libraries/properties/';
        include_once "$props/plugins/ExportPluginProperties.class.php";
        include_once "$props/options/groups/OptionsPropertyRootGroup.class.php";
        include_once "$props/options/groups/OptionsPropertyMainGroup.class.php";
        include_once "$props/options/items/RadioPropertyItem.class.php";
        include_once "$props/options/items/TextPropertyItem.class.php";
        include_once "$props/options/items/BoolPropertyItem.class.php";

        $exportPluginProperties = new ExportPluginProperties();
        $exportPluginProperties->setText('Microsoft Word 2000');
        $exportPluginProperties->setExtension('doc');
        $exportPluginProperties->setMimeType('application/vnd.ms-word');
        $exportPluginProperties->setForceFile(true);
        $exportPluginProperties->setOptionsText(__('Options'));

        // create the root group that will be the options field for
        // $exportPluginProperties
        // this will be shown as "Format specific options"
        $exportSpecificOptions = new OptionsPropertyRootGroup();
        $exportSpecificOptions->setName("Format Specific Options");

        // what to dump (structure/data/both)
        $dumpWhat = new OptionsPropertyMainGroup();
        $dumpWhat->setName("dump_what");
        $dumpWhat->setText(__('Dump table'));
        // create primary items and add them to the group
        $leaf = new RadioPropertyItem();
        $leaf->setName("structure_or_data");
        $leaf->setValues(
            array(
                'structure' => __('structure'),
                'data' => __('data'),
                'structure_and_data' => __('structure and data')
            )
        );
        $dumpWhat->addProperty($leaf);
        // add the main group to the root group
        $exportSpecificOptions->addProperty($dumpWhat);

        // data options main group
        $dataOptions = new OptionsPropertyMainGroup();
        $dataOptions->setName("dump_what");
        $dataOptions->setText(__('Data dump options'));
        $dataOptions->setForce('structure');
        // create primary items and add them to the group
        $leaf = new TextPropertyItem();
        $leaf->setName("null");
        $leaf->setText(__('Replace NULL with:'));
        $dataOptions->addProperty($leaf);
        $leaf = new BoolPropertyItem();
        $leaf->setName("columns");
        $leaf->setText(__('Put columns names in the first row'));
        $dataOptions->addProperty($leaf);
        // add the main group to the root group
        $exportSpecificOptions->addProperty($dataOptions);

        // set the options for the export plugin property item
        $exportPluginProperties->setOptions($exportSpecificOptions);
        $this->properties = $exportPluginProperties;
    }
}
?>
