<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 09.02.2019
 * Time: 23:07
 */

namespace Controllers;

use App\Core\Controller;

class MainController extends Controller
{
    public function index()
    {
        return view('index', ['welcome'=> app()->name]);
    }
}