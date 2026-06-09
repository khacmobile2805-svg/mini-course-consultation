<?php
// app/Controllers/HomeController.php
namespace App\Controllers;

class HomeController
{
    public function index(): void
    {
        view('home', ['view' => 'home']);
    }
}