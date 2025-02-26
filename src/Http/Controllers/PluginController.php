<?php

namespace Exxtensio\EcommerceDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Inertia\Response;

class PluginController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Plugin/Index');
    }

//    public function install(Request $request)
//    {
//        Artisan::call('composer:run', ['action' => 'require', 'name' => $request->get('name')]);
//
////        $process = new Process(['composer', 'require', $request->get('name')]);
////        $process->run();
////
////        if (!$process->isSuccessful()) {
////            throw new ProcessFailedException($process);
////        }
//
//        return response()->json(['message' => "Package {$request->get('name')} installed successfully"]);
//    }
}
