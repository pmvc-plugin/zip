<?php
namespace PMVC\PlugIn\zip;
use PHPUnit_Framework_TestCase;
use PMVC;

\PMVC\Load::plug();
\PMVC\addPlugInFolders(['../']);

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
       $tmpZip = \PMVC\plug('tmp')->file();
       $zip = PMVC\plug($this->_plug);
       $zipfile = $zip->open($tmpZip);
       $zip->addFromString('/456','123');
       $this->assertTrue(is_file($tmpZip));
       $this->assertTrue(0<filesize($tmpZip));
    }

    function testOpenNonZipFile()
    {
       $tmpFile = 'test.php';
       $zip = PMVC\plug($this->_plug);
       $this->assertTrue(is_file($tmpFile));
       $this->assertFalse($zip->checkFileFormat($tmpFile));
    }
}
