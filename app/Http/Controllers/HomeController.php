<?php

namespace App\Http\Controllers;

use App\Console\Commands\Send;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

use App\Http\Requests;

class HomeController extends Controller
{
    use DispatchesJobs;
    
    public function index () {
        $this->dispatch(
            new Send()
        );
    }
}
