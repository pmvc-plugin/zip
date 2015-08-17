<?php
namespace PMVC\PlugIn\zip;


${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\zip';

class zip extends \PMVC\PlugIn
{

    private $tmpdir_append_str = '_dir/';
    /**
     * @see http://www.phpconcept.net/pclzip/user-guide/18
     */
    public function get($zip_file)
    {
        if (!class_exists('PclZip')) {
            \PMVC\l(__DIR__.'/src/pclzip.lib.php');
        }
        $zip = new \PclZip($zip_file);
        $this['current'] = $zip; 
        return $zip;
    }

    public function getTempFolder()
    {
       $tmp = tempnam(sys_get_temp_dir(), 'zip_');
       $tmp_dir = $tmp.$this->tmpdir_append_str;
       mkdir($tmp_dir,-1,true);
       return $tmp_dir;
    }

    public function cleanTempFolder($tmp_dir)
    {
       unlink(substr($tmp_dir,0,strlen($tmp_dir)-strlen($this->tmpdir_append_str)));
       $fl = \PMVC\plug('file_list');
       $fl->rmdir($tmp_dir);
    }
    
    public function addFromString($str,$zipPath=null,$zip=null)
    {
       if (is_null($zip)) {
        $zip = $this['current'];
       }
       if (is_null($zipPath)) {
        $zipPath = time();
       }
       $tmp = tempnam(sys_get_temp_dir(), 'zip_');
       file_put_contents($tmp, $str);
       $this->addFile($tmp, $zipPath, $zip);
       unlink($tmp);
    }

    public function addFile($file,$zipPath,$zip=null)
    {
       $tmp_dir = $this->getTempFolder();
       $wholdPath = $tmp_dir.$zipPath;
       $zipDir = dirname($wholdPath);
       if (!is_dir($zipDir)) {
        mkdir($zipDir,-1,true);
       }
       copy($file, $wholdPath);
       $zip->add($tmp_dir, PCLZIP_OPT_REMOVE_PATH, $tmp_dir);
       $this->cleanTempFolder($tmp_dir);
    }
}
