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

		//Return for processing
		$data['error'] = 0;
		$data['narrative_path'] = $path.substr($zipFileName, 0, -4);
		return $data;
	}
}
?>
