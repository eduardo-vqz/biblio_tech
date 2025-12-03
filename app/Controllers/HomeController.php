<?php
namespace App\Controllers;

class HomeController
{
    public function index(): void
    {
        $viewFile = __DIR__ . '/../../view/home/index.php';
        include_once __DIR__ . '/../../view/layout/main_layout.php';
    }
}
