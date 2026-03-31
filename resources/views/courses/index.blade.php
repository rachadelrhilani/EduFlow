@extends('layouts.dashboard-layout')

@section('title', 'Catalogue des cours')

@section('dashboard-content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="relative w-full md:w-96">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
            🔍
        </span>
        <input type="text" id="courseSearch" placeholder="Rechercher un cours, une catégorie..." 
               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#2563EB] outline-none transition shadow-sm">
    </div>
</div>

<div id="courses-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @for($i=0; $i<6; $i++)
        <div class="bg-white rounded-2xl h-64 animate-pulse border border-gray-100"></div>
    @endfor
</div>

<div id="courseModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl transform transition-all scale-95 opacity-0" id="modalContent">
        <div class="h-40 bg-[#1E3A8A] p-6 text-white relative">
            <button onclick="closeCourseModal()" class="absolute top-4 right-4 text-white/80 hover:text-white">✕</button>
            <span id="modalCategory" class="text-xs font-bold uppercase tracking-widest bg-[#2563EB] px-2 py-1 rounded"></span>
            <h3 id="modalTitle" class="text-2xl font-poppins font-bold mt-2"></h3>
        </div>
        <div class="p-8 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">Enseignant : <span id="modalTeacher" class="font-bold text-gray-800"></span></p>
                <p id="modalPrice" class="text-2xl font-poppins font-bold text-[#2563EB]"></p>
            </div>
            <p id="modalDescription" class="text-gray-600 leading-relaxed font-roboto"></p>
            <button class="w-full bg-[#2563EB] text-white font-bold py-3 rounded-xl hover:bg-[#1E3A8A] transition shadow-lg mt-4">
                S'inscrire maintenant
            </button>
        </div>
    </div>
</div>
@endsection