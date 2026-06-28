@extends('layouts.public')

@section('title', 'About Us | Qitchen')

@section('content')

    <!-- ================= 1. HEADER & HERO IMAGE ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 text-center">
        
        <!-- Label & Judul -->
        <span class="text-orange-500 font-bold text-xs tracking-widest uppercase bg-orange-50 px-4 py-1.5 rounded-full">
            Our story
        </span>
        <h1 class="mt-8 text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight max-w-4xl mx-auto leading-tight">
            We're rethinking how restaurants serve people
        </h1>
        <p class="mt-6 text-lg text-gray-500 max-w-3xl mx-auto leading-relaxed">
            Qitchen started in a single 20-seat diner with one belief: great food shouldn't come with a long wait. Today we power self-service ordering across dozens of kitchens.
        </p>

        <!-- Gambar Hero Besar -->
        <div class="mt-16 w-full h-[300px] md:h-[500px] relative rounded-[2.5rem] overflow-hidden shadow-2xl">
            <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&q=80&w=1600&h=900" alt="Qitchen Interior" class="w-full h-full object-cover">
        </div>

    </section>


    <!-- ================= 2. STATISTIK ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-10 text-center hover:shadow-lg transition duration-300">
                <h2 class="text-5xl font-black text-orange-500 mb-3">2019</h2>
                <p class="text-gray-500 font-medium">Founded in Surabaya</p>
            </div>
            
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-10 text-center hover:shadow-lg transition duration-300">
                <h2 class="text-5xl font-black text-orange-500 mb-3">1</h2>
                <p class="text-gray-500 font-medium">Flagship kitchen</p>
            </div>
            
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-10 text-center hover:shadow-lg transition duration-300">
                <h2 class="text-5xl font-black text-orange-500 mb-3">50+</h2>
                <p class="text-gray-500 font-medium">Signature recipes</p>
            </div>

        </div>
    </section>


    <!-- ================= 3. MISSION & VISION ================= -->
    <section class="bg-slate-50 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="bg-white p-10 md:p-12 rounded-[2.5rem] shadow-sm hover:shadow-md transition">
                <h3 class="text-xl font-extrabold text-gray-900 mb-4">Our Mission</h3>
                <p class="text-gray-500 leading-relaxed">
                    To give every diner a faster, friendlier way to order and every kitchen the tools to deliver it flawlessly.
                </p>
            </div>
            
            <div class="bg-white p-10 md:p-12 rounded-[2.5rem] shadow-sm hover:shadow-md transition">
                <h3 class="text-xl font-extrabold text-gray-900 mb-4">Our Vision</h3>
                <p class="text-gray-500 leading-relaxed">
                    A world where waiting in line for food is a thing of the past, and hospitality is powered by thoughtful technology.
                </p>
            </div>

        </div>
    </section>


    <!-- ================= 4. TEAM SECTION ================= -->    
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        
        <span class="text-orange-500 font-bold text-xs tracking-widest uppercase">The People</span>
        <h2 class="mt-4 text-4xl font-extrabold text-gray-900">Meet the team</h2>
        <p class="mt-4 text-gray-500">A small crew obsessed with food and flawless service.</p>

        <div class="mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            
            <!-- Team 1 -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 hover:shadow-xl transition duration-300 group cursor-default">
                <div class="w-20 h-20 bg-slate-900 text-white rounded-full flex items-center justify-center font-bold text-xl mx-auto mb-6 group-hover:bg-orange-500 transition">F</div>
                <h4 class="font-extrabold text-gray-900 text-lg">Fachriditya</h4>
                <p class="text-sm text-gray-500 mt-1">Founder</p>
            </div>

            <!-- Team 2 -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 hover:shadow-xl transition duration-300 group cursor-default">
                <div class="w-20 h-20 bg-slate-900 text-white rounded-full flex items-center justify-center font-bold text-xl mx-auto mb-6 group-hover:bg-orange-500 transition">AF</div>
                <h4 class="font-extrabold text-gray-900 text-lg">Ananda Farrelas</h4>
                <p class="text-sm text-gray-500 mt-1">Co-founder</p>
            </div>

            <!-- Team 3 -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 hover:shadow-xl transition duration-300 group cursor-default">
                <div class="w-20 h-20 bg-slate-900 text-white rounded-full flex items-center justify-center font-bold text-xl mx-auto mb-6 group-hover:bg-orange-500 transition">KR</div>
                <h4 class="font-extrabold text-gray-900 text-lg">Keisha Rafa</h4>
                <p class="text-sm text-gray-500 mt-1">Head of Culinary</p>
            </div>

            <!-- Team 4 -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 hover:shadow-xl transition duration-300 group cursor-default">
                <div class="w-20 h-20 bg-slate-900 text-white rounded-full flex items-center justify-center font-bold text-xl mx-auto mb-6 group-hover:bg-orange-500 transition">SM</div>
                <h4 class="font-extrabold text-gray-900 text-lg">Shima Maharani</h4>
                <p class="text-sm text-gray-500 mt-1">Head of Product</p>
            </div>

            <!-- Team 5 -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 hover:shadow-xl transition duration-300 group cursor-default">
                <div class="w-20 h-20 bg-slate-900 text-white rounded-full flex items-center justify-center font-bold text-xl mx-auto mb-6 group-hover:bg-orange-500 transition">CT</div>
                <h4 class="font-extrabold text-gray-900 text-lg">Cindy Tiara</h4>
                <p class="text-sm text-gray-500 mt-1">Operations Lead</p>
            </div>

        </div>
    </section>

@endsection