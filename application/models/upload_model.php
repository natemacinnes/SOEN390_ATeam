<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Pre-processing: unzipping folder and moving it to tmp folder
	 */
	public function unzip($path, $zipFileName)
	{
		$zipFile = $path . $zipFileName;
		//Unzipping
		$zip = new ZipArchive;
		if ($zip->open($zipFile) === TRUE)
		{
			$zip->extractTo($path);
			$zip->close();
		}
		else
		{
			$data['error'] = 1;
			$data['error_message'] = 'Unzipping failed. Please attempt the upload again.';
			return $data;
		}

		//Getting path of the new narrative - Handling mismatch of name of zip and containing folder
		$nar_path = $path . substr($zipFileName, 0, -4);
		if(is_dir($nar_path))
		{
			$data['narrative_path'] = $nar_path;
		}
		else
		{
			$file_scan = scandir($path);
			foreach($file_scan as $file)
			{
				$file_extension = pathinfo($file, PATHINFO_EXTENSION);

				//System was being fooled by having the __MACOSX folder in it, substr is used to keep it open for
				//possible future modification by Apple
				if($file_extension != 'zip' && $file != '.' && $file != '..' && $file != substr('__MACOSX', 0, 5))
					$data['narrative_path'] = $path.$file;
			}
		}

		//If folder empty
		if(!isset($data['narrative_path']))
		{
			$data['error'] = 1;
			return $data;
		}

		//Return for processing
		$data['error'] = 0;
		return $data;
	}
}
?>
