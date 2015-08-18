<?php
namespace PMVC\PlugIn\zip;


${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\zip';

class zip extends \PMVC\PlugIn
{

    private $tmpPrefix = 'zip_';
    /**
     * @see http://www.phpconcept.net/pclzip/user-guide/18
     */
    public function open($zip_file)
    {
        if (!class_exists('PclZip')) {
            \PMVC\l(__DIR__.'/src/pclzip.lib.php');
        }
        $zip = new \PclZip($zip_file);
        $this['current'] = $zip; 
        return $zip;
    }

    public function addFromString($zipPath, $content, $zip=null)
    {
       $tmp = \PMVC\plug('tmp')->file($this->tmpPrefix);
       file_put_contents($tmp, $content);
       $this->addFile($tmp, $zipPath, $zip);
    }

    public function addFile($file,$zipPath,$zip=null)
    {
       if (is_null($zip)) {
        $zip = $this['current'];
       }
       $tmp_dir =\PMVC\plug('tmp')->dir($this->tmpPrefix);
       $wholdPath = $tmp_dir.$zipPath;
       $zipDir = dirname($wholdPath);
       if (!is_dir($zipDir)) {
        mkdir($zipDir,-1,true);
       }
       copy($file, $wholdPath);
       $zip->add($tmp_dir, PCLZIP_OPT_REMOVE_PATH, $tmp_dir);
    }
}
