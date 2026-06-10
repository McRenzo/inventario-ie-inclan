<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BienController extends Controller
{
    public function index()
    {
        // Por ahora lo dejamos retornando un texto simple para probar que levante el Dashboard
        return "Aquí se mostrará la tabla de bienes pronto";
    }
}