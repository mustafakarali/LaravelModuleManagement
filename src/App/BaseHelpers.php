<?php 
namespace App;

use Carbon;

class BaseHelpers {


	public static function readModule($modulename)
	{
	    $path = include(app_path().'/Modules/'.$modulename.'/details.php');

	    return $path;
	}


}
