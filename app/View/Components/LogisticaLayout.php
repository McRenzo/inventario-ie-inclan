<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LogisticaLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // 👇 CAMBIAMOS ESTA LÍNEA PARA APUNTAR A TU ARCHIVO EN LAYOUTS
        return view('layouts.logistica');
    }
}