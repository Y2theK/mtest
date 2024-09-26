<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $data['companyCount'] = Company::count('id');
        $data['employeeCount'] = Employee::count('id');
        $data['recentCompanies'] = Company::orderBy('id','desc')->limit(5)->get();

        return view('dashboard',$data);
    }
}
