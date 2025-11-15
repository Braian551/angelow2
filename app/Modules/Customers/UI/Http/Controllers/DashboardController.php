<?php

namespace App\Modules\Customers\UI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('modules.customers.dashboard', [
            'user' => Auth::user(),
        ]);
    }
}
