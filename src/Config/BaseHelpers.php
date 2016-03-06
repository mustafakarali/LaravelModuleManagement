<?php 
namespace App;

use Carbon;

class BaseHelpers {


	public static function readModule($modulename)
	{
		/*$path = app_path().'/Modules/'.$modulename.'/details.php';
	    $data = file_get_contents($path);
	    $decode = json_decode($data, true);
	    return $decode[$parametre];*/

	    $path = include(app_path().'/Modules/'.$modulename.'/details.php');

	    return $path;
	}


}
