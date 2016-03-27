<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use File;
use Auth;

use App\BaseHelpers;

abstract class AdminTemplateController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



     public function __construct()
	{	
   		    $path = app_path().'/Modules';
		   	$directories = "";
		    $modules = [];
		    if(File::exists($path)) 
		    {
		     	$directories = array_map('basename', File::directories($path));
		     	foreach($directories as $directory)
		     	{
					$modules[$directory] = BaseHelpers::readModule($directory);
		     	}
		    }
			view()->share('modules', $modules);
	}
}
