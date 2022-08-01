<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IntakeformController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.setting.intake_form');
    }
}
