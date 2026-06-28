@extends('layouts.public')

@section('title', 'Contact Us | Qitchen')

@section('content')

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        
        <!-- ================= 1. HEADER ================= -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight">Get in touch</h1>
            <p class="mt-4 text-gray-500 text-lg">Questions, feedback or partnership ideas we'd love to hear from you.</p>
        </div>

        <!-- ================= 2. KONTEN UTAMA (GRID KIRI & KANAN) ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Kolom Kiri: Form Pesan  -->
            <div class="lg:col-span-2 bg-white rounded-[2rem] border border-gray-100 shadow-lg shadow-gray-200/40 p-8 md:p-12">
                <h3 class="text-3xl font-extrabold text-gray-900 mb-2">Send us a message</h3>
                <p class="text-gray-500 mb-8">Have a question about our menu, reservations, or anything else? Fill out the form below and we'll get back to you shortly.</p>

                <!-- Notifikasi Sukses Terkirim -->
                @if(session('success'))
                <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl flex items-center gap-3">
                    <svg class="w-6 h-6 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                @endif

                <!-- Form -->
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf <!-- WAJIB ADA di Laravel -->

                    <!-- Baris 1: First Name & Last Name (Grid 2 Kolom) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-bold text-gray-900 mb-2">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3.5 text-gray-700 focus:ring-2 focus:ring-orange-500 transition shadow-sm">
                            @error('first_name')
                                <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-bold text-gray-900 mb-2">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3.5 text-gray-700 focus:ring-2 focus:ring-orange-500 transition shadow-sm">
                            @error('last_name')
                                <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Baris 2: Email & Phone (Grid 2 Kolom) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-900 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3.5 text-gray-700 focus:ring-2 focus:ring-orange-500 transition shadow-sm">
                            @error('email')
                                <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone (Opsional) -->
                        <div>
                            <label for="phone" class="block text-sm font-bold text-gray-900 mb-2">Phone Number (Optional)</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3.5 text-gray-700 focus:ring-2 focus:ring-orange-500 transition shadow-sm">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dropdown Subject (Full Width - Di luar Grid) -->
                    <div>
                        <label for="subject" class="block text-sm font-bold text-gray-900 mb-2">Subject / Topic</label>
                        <div class="relative">
                            <select id="subject" name="subject" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3.5 text-gray-700 focus:ring-2 focus:ring-orange-500 transition shadow-sm appearance-none">
                                <option value="" disabled {{ old('subject') ? '' : 'selected' }}>Select a topic...</option>
                                <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry (Pertanyaan Umum)</option>
                                <option value="Feedback & Complaint" {{ old('subject') == 'Feedback & Complaint' ? 'selected' : '' }}>Feedback & Complaint (Keluhan)</option>
                                <option value="Partnership & Business" {{ old('subject') == 'Partnership & Business' ? 'selected' : '' }}>Partnership & Business (Kerja Sama)</option>
                                <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other (Lainnya)</option>
                            </select>

                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('subject')
                            <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-bold text-gray-900 mb-2">Your Message</label>
                        <textarea id="message" name="message" rows="5" required class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3.5 text-gray-700 focus:ring-2 focus:ring-orange-500 transition shadow-sm resize-none">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full md:w-auto bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-10 rounded-full transition shadow-lg shadow-orange-200">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>

            <!-- Kolom Kanan: Info Kontak & Peta -->
            <div class="lg:col-span-1 space-y-8">
                
                <!-- Card Info Kontak -->
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-lg shadow-gray-200/40 p-8 flex flex-col gap-8">
                    
                    <!-- Item 1: Visit Us -->
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-gray-900">Visit us</h4>
                            <p class="text-sm text-gray-500 mt-1 leading-relaxed">Jl. Tenggilis Timur II No. 10, Surabaya 60292</p>
                        </div>
                    </div>

                    <!-- Item 2: Call Us -->
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-gray-900">Call us</h4>
                            <p class="text-sm text-gray-500 mt-1">+62 1 6767 1234</p>
                        </div>
                    </div>

                    <!-- Item 3: Email -->
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-gray-900">Email</h4>
                            <p class="text-sm text-gray-500 mt-1">hello@qitchen.id</p>
                        </div>
                    </div>

                    <!-- Item 4: Hours -->
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-gray-900">Hours</h4>
                            <p class="text-sm text-gray-500 mt-1">Mon - Sun &middot; Open 24 Hours</p>
                        </div>
                    </div>

                </div>

                <!-- Card Gambar Peta -->
                <a href="https://maps.google.com/?q=Jl.+Tenggilis+Timur+II+No+10,+Surabaya" target="_blank" rel="noopener noreferrer" class="relative h-64 rounded-[2rem] overflow-hidden group cursor-pointer shadow-lg shadow-gray-200/40 block">
                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&q=80&w=800&h=600" alt="Map Location" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    
                    <!-- Overlay kabut putih -->
                    <div class="absolute inset-0 bg-white/40 backdrop-blur-[1px] group-hover:bg-white/20 transition duration-300 flex flex-col items-center justify-center">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-orange-500 shadow-md mb-2">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                        </div>
                        <span class="font-extrabold text-gray-900 bg-white/90 px-3 py-1 rounded-full text-xs shadow-sm">View on map</span>
                    </div>
                </a>

            </div>

        </div>
    </section>

@endsection