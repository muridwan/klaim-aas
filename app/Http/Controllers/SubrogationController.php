<?php

namespace App\Http\Controllers;

use App\Models\Subrogation;
use App\Models\Claim;
use Illuminate\Http\Request;

class SubrogationController extends Controller
{
    public function index()
    {
        $subrogations = Subrogation::with('claim')->latest()->paginate(10);
        $data  = [        
            'menu'          =>  'Subrogations' ,
            'title'         =>  'Subrogations',  
            'subrogations'  => $subrogations,      
        ];
        return view('subrogations.index', $data);
    }

    public function create()
    {
       // $claims = Claim::where('status', 'paid')->get(); // hanya klaim yang sudah dibayar
        $claims = Claim::with('office:id,name')->where('status', 3)->where('outlet_id',session('user_data')['outlet_id'])->orderBy('code')->get();
        $data  = [        
            'menu'          =>  'Create' ,
            'title'         =>  'Subrogations',  
            'claims'        =>  $claims,      
        ];
        return view('subrogations.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'claim_id' => 'required|exists:ap_claims,id',
            'third_party_name' => 'required|string|max:255',
            'third_party_type' => 'nullable|string|max:100',
            'subrogation_amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'draft';
        Subrogation::create($validated);

        return redirect()->route('subrogations.index')
            ->with('success', 'Subrogasi berhasil ditambahkan.');
    }

    public function edit(Subrogation $subrogation)
    {
        $claims = Claim::all();
        $data  = [        
            'menu'          =>  'Create' ,
            'title'         =>  'Subrogations',  
            'claims'        =>  $claims,      
            'subrogation'   =>  $subrogation,
        ];
        return view('subrogations.edit', $data);
    }

    public function update(Request $request, Subrogation $subrogation)
    {
        $validated = $request->validate([
            'claim_id' => 'required|exists:claims,id',
            'third_party_name' => 'required|string|max:255',
            'third_party_type' => 'nullable|string|max:100',
            'subrogation_amount' => 'required|numeric|min:0',
            'recovered_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $subrogation->update($validated);

        return redirect()->route('subrogations.index')
            ->with('success', 'Data subrogasi berhasil diperbarui.');
    }

    public function destroy(Subrogation $subrogation)
    {
        $subrogation->delete();
        return redirect()->route('subrogations.index')
            ->with('success', 'Subrogasi berhasil dihapus.');
    }
}
