<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Http\Requests\CompanyRequest;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companies = Company::query()->when($request->search,function($q) use($request){

            $q->whereAny([
                'name',
                'email',
                'website'
            ],'LIKE',"%$request->search%");

        })->orderBy('id','desc')->paginate(5)->appends($request->query());

        return view('company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('company.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequest $request)
    {
        $data = $request->validated();

        if($request->hasFile('logo')) {
            $path = $this->imageUploadService->uploadPublic($request->file('logo'),'logos');
            $data['logo'] = $path;
        }


        Company::create($data);

        return redirect()->route('companies.index')->with(['success' => 'Company successfully created.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('company.edit',compact('company'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request, Company $company)
    {
        $data = $request->validated();

        if($request->hasFile('logo')) {

            $company->logo && Storage::disk('public')->delete($company->logo); // delete company logo if exists

            $path = $this->imageUploadService->uploadPublic($request->file('logo'),'logos');

            $data['logo'] = $path;
        }


        $company->update($data);

        return redirect()->route('companies.index')->with(['success' => 'Company successfully created.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->logo && Storage::disk('public')->delete($company->logo); // delete company logo if exists

        $company->delete();

        return redirect()->route('companies.index')->with(['success' => 'Company successfully updated.']);
    }
}
