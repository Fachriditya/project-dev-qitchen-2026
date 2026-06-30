<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        
        $vouchers = Voucher::when($search, function ($query, $search) {
                return $query->where('voucher_name', 'like', "%{$search}%")
                             ->orWhere('voucher_code', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                if ($status === 'active') {
                    return $query->where('is_active', 1);
                } elseif ($status === 'nonactive') {
                    return $query->where('is_active', 0);
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_name' => 'required|string|max:255',
            'voucher_code' => 'required|string|max:50|unique:vouchers,voucher_code',
            'voucher_description' => 'nullable|string',
            'voucher_type' => 'required|in:percentage,fixed,free_delivery',
            'voucher_value' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
            'expiration_date' => 'nullable|date',
            'is_active' => 'required|boolean',
        ]);

        // Default issued_count adalah 0 untuk voucher baru
        $data = $request->all();
        $data['issued_count'] = 0; 
        
        // Memastikan kode voucher selalu huruf besar tanpa spasi
        $data['voucher_code'] = strtoupper(str_replace(' ', '', $request->voucher_code));

        Voucher::create($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher created successfully!');
    }

    public function show(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.show', compact('voucher'));
    }

    public function edit(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, string $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->validate([
            'voucher_name' => 'required|string|max:255',
            'voucher_code' => 'required|string|max:50|unique:vouchers,voucher_code,' . $voucher->voucher_id . ',voucher_id',
            'voucher_description' => 'nullable|string',
            'voucher_type' => 'required|in:percentage,fixed,free_delivery',
            'voucher_value' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
            'expiration_date' => 'nullable|date',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->all();
        $data['voucher_code'] = strtoupper(str_replace(' ', '', $request->voucher_code));

        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher updated successfully!');
    }

    public function destroy(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher deleted successfully!');
    }
}