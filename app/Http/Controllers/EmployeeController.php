<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Services\ImageUploadService;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService){}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employees = Employee::with('company:id,name')
                        ->when($request->search,function($q) use ($request){
                            $q->whereAny([
                                'name',
                                'email',
                                'phone'
                            ],'LIKE',"%$request->search%");
                        })
                        ->orderBy('id','desc')->paginate(5);

        return view('employee.index',compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::select(['id','name'])->get();
        return view('employee.create',compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
        $data = $request->validated();

        if($request->hasFile('profile')) {
            $path = $this->imageUploadService->uploadPublic($request->file('profile'),'profiles');
            $data['profile'] = $path;
        }

        Employee::create($data);

        return redirect()->route('employees.index')->with(['success' => 'Employees successfully created.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $companies = Company::select(['id','name'])->get();
        return view('employee.edit',compact('employee','companies'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();

        if($request->hasFile('profile')) {

            $employee->profile && Storage::disk('public')->delete($employee->profile); // delete profile if exists

            $path = $this->imageUploadService->uploadPublic($request->file('profile'),'profiles');

            $data['profile'] = $path;
        }


        $employee->update($data);

        return redirect()->route('employees.index')->with(['success' => 'Employee successfully updated.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->profile && Storage::disk('public')->delete($employee->profile); // delete profile if exists
        
        $employee->delete();

        return redirect()->route('employees.index')->with(['success' => 'Employee successfully deleted.']);
    }
}
