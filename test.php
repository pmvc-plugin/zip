<?php
PMVC\Load::plug();
PMVC\addPlugInFolder('../');
class ZipTest extends PHPUnit_Framework_TestCase
{
    private $_plug = 'zip';
    function testPlugin()
    {
        ob_start();
        print_r(PMVC\plug($this->_plug));
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains($this->_plug,$output);
    }

    function testGetZip()
    {
       $zip = PMVC\plug($this->_plug);
       $zipfile = $zip->get('xxx.zip');
       $zip->addFromString('123');
    }
}
