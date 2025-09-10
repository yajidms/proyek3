<?php

namespace App\Controllers;

class hello extends BaseController
{
    public function index(): string
    {
        return view('hello_view');
    }
}
