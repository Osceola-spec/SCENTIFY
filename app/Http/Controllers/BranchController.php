<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /** Display active branches to customers */
    public function index()
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        return view('branches.index', compact('branches'));
    }
}
