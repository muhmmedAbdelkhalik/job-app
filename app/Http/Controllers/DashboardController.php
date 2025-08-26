<?php

namespace App\Http\Controllers;

use App\Models\JobVacany;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $jobs = JobVacany::query()
            ->with('company')
            ->when($request->filter, function ($query) use ($request) {
                $validTypes = ['full-time', 'part-time', 'hybrid', 'remote'];
                if (in_array($request->filter, $validTypes)) {
                    $query->where('type', $request->filter);
                }
            })
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('title', 'like', '%' . $request->search . '%')
                        ->orWhereHas('company', function ($companyQuery) use ($request) {
                            $companyQuery->where('name', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('dashboard', compact('jobs'));
    }
}
