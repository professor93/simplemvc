<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 09.02.2019
 * Time: 23:07
 */

namespace App\Controllers;


use App\Core\Controller;

class MainController extends Controller
{
    public function index()
    {
        return view('index', ['welcome'=> app()->name]);
    }

    public function signin()
    {
        return view('signin');
    }

    public function start()
    {
        return view('home');
    }
}