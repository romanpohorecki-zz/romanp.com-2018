<?php
/** 
 * 
 * Update : Apr 24 09
 * 
 * Forum Thread: http://codeigniter.com/forums/viewthread/111977
 * 
 * 
 * Extension to fix zip file's Folder structure
 * 
 * Problem faced:
 * When zipping up a folder outside my root like 
 * eg:  

		$path = '/path/to/your/directory/';
		$this->zip->read_dir($path);
		$this->zip->download('my_backup.zip'); 
	
	The resulting zip file had the same structure
 	eg: my_backup.zip/path/to/your/directory/
 
	This extention allows me to get files from a deep
	folder and rename the structure in the Zip.

	Usage:
		$path = '/path/to/your/directory/';
		$folder_in_zip = "source-code";

		$this->zip->add_dir($folder_in_zip);  // Create folder in zip 
		$this->zip->get_files_from_folder($path, $folder_in_zip);
		
		$this->zip->download('my_backup.zip'); 

	Resulting Zip:
	mybackup.zip/source-code/(contents and inner of $path)


 */
class MY_Zip extends CI_Zip 
{
		
	function get_files_from_folder($directory, $put_into) {
            if ($handle = opendir($directory)) {
                while (false !== ($file = readdir($handle))) {
                    if (is_file($directory.$file)) {
                        $fileContents = file_get_contents($directory.$file);
                        
                        $this->add_data($put_into.$file, $fileContents);
                        
                    } elseif ($file != '.' and $file != '..' and is_dir($directory.$file)) {
                        
                        $this->add_dir($put_into.$file.'/');
                        
                        $this->get_files_from_folder($directory.$file.'/', $put_into.$file.'/');
                    }
                }
            }
            closedir($handle);
    }
}
?>