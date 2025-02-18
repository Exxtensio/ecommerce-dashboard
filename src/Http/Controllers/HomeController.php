<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Exxtensio\EcommerceDashboard\Models\ProductCategory;

class HomeController extends Controller
{
    public function index(): Response
    {

//        dd(ProductCategory::all());


        return Inertia::render('Dashboard/Index');
    }
}
