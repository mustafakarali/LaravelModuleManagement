<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class MainController extends ViewTemplateController
{
    public function index()
    {
    	return view("masters.master");
    }
}
