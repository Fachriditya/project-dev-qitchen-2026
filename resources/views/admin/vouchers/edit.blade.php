@extends('layouts.master')
@section('title', 'Edit Voucher')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.vouchers.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Voucher</h1>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] max-w-4xl">
        <form action="{{ route('admin.vouchers.update', $voucher->voucher_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Voucher Name <span class="text-red-500">*</span></label>
                    <input type="text" name="voucher_name" value="{{ old('voucher_name', $voucher->voucher_name) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500" required>
                </div>
                
                <!-- Code -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Voucher Code <span class="text-red-500">*</span></label>
                    <input type="text" name="voucher_code" value="{{ old('voucher_code', $voucher->voucher_code) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500 uppercase font-mono" required>
                    @error('voucher_code') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Voucher Type <span class="text-red-500">*</span></label>
                    <select name="voucher_type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500" required>
                        <option value="percentage" {{ old('voucher_type', $voucher->voucher_type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ old('voucher_type', $voucher->voucher_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (Rp)</option>
                        <option value="free_delivery" {{ old('voucher_type', $voucher->voucher_type) == 'free_delivery' ? 'selected' : '' }}>Free Delivery</option>
                    </select>
                </div>

                <!-- Value -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Discount Value <span class="text-red-500">*</span></label>
                    <input type="number" name="voucher_value" value="{{ old('voucher_value', $voucher->voucher_value) }}" min="0" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500" required>
                </div>

                <!-- Min Order -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Minimum Order (Rp)</label>
                    <input type="number" name="min_order" value="{{ old('min_order', $voucher->min_order) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Max Discount -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Maximum Discount (Rp)</label>
                    <input type="number" name="max_discount" value="{{ old('max_discount', $voucher->max_discount) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Quota -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Usage Quota</label>
                    <input type="number" name="quota" value="{{ old('quota', $voucher->quota) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Expiration Date -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Expiration Date & Time</label>
                    <input type="datetime-local" name="expiration_date" value="{{ old('expiration_date', $voucher->expiration_date ? $voucher->expiration_date->format('Y-m-d\TH:i') : '') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="is_active" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500" required>
                        <option value="1" {{ old('is_active', $voucher->is_active) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $voucher->is_active) == '0' ? 'selected' : '' }}>Nonactive</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-900 mb-2">Description</label>
                <textarea name="voucher_description" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500">{{ old('voucher_description', $voucher->voucher_description) }}</textarea>
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.vouchers.index') }}" class="px-6 py-3 rounded-full font-bold text-gray-500 hover:bg-gray-100 transition">Cancel</a>
                <button type="submit" class="bg-orange-500 text-white px-8 py-3 rounded-full font-bold hover:bg-orange-600 transition shadow-md">Update Voucher</button>
            </div>
        </form>
    </div>
</div>
@endsection