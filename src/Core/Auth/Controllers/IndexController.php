<?php
/**
 * Modestox Copyright (c) 2024.
 */
namespace Modestox\Ecommerce\Core\Auth\Controllers;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('Auth::index');
    }
}
