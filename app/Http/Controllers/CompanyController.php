<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Platoon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:campus-create')->only(['create', 'store']);
        $this->middleware('permission:campus-list')->only(['index', 'show']);
        $this->middleware('permission:campus-edit')->only(['edit', 'update']);
        $this->middleware('permission:campus-delete')->only(['destroy']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::get();
        return view('settings.companies.index',compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.campuses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'campusName' => 'required|unique:campuses,campusName',
            'description' => 'required',
        ]);

        // If validation passes, you can proceed with storing the data
        Company::create($request->all());
    
        return redirect()->route('campuses.index')
                        ->with('success','Campus added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return view('settings.campuses.show',compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $campus)
    {
        return view('settings.campuses.edit',compact('campus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        request()->validate([
            'campusName' => 'required|unique:campuses,campusName,'.$company->id,
            'description' => 'required',
        ]);
   
       $company->update($request->all());
   
       return redirect()->route('campuses.index')
                       ->with('success','Campus updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
    
        return redirect()->route('campuses.index')
                        ->with('success','Campus deleted successfully');
    }

    public function create_platoon(Request $request, $companyId){
        
        $company = Company::find($companyId);
        
        if(empty($company)){
            return redirect()->back()->with('error', 'Company is not found');
        }
        $validator = Validator::make($request->all(),[
            'name'=> ['required',
                        'string',
                Rule::unique('platoons')->where(function ($query) use ($request) {
                    return $query->where('company_id', $request->company_id);
        }),
            ],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
       Platoon::create([
            'company_id'=> $company->id,
            'name' => $request->name            
        ]);

        return redirect()->back()->with('success','Platoon created successfully');
    }
}
