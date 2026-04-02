@extends('layouts.dashboard-layout')

@section('title', 'Mes Favoris')

@section('dashboard-content')
<div class="mb-6">
    <h1 class="text-2xl font-poppins font-bold text-gray-800">Mes cours sauvegardés 💖</h1>
</div>

<div id="favorites-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Rempli par JS -->
</div>

<!-- Structure de la Modal (Copie conforme de ta page catalogue) -->
<div id="courseModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity">
    <div id="modalContent" class="bg-white w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl transform scale-95 opacity-0 transition-all duration-300">
        <div class="p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <span id="modalCategory" class="text-xs font-bold text-[#2563EB] uppercase tracking-wider"></span>
                    <h2 id="modalTitle" class="text-3xl font-poppins font-bold text-gray-900 mt-2"></h2>
                </div>
                <button onclick="closeCourseModal()" class="p-2 hover:bg-gray-100 rounded-full transition">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <p id="modalDescription" class="text-gray-600 leading-relaxed mb-8 text-lg"></p>
            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold" id="modalTeacherInitial">E</div>
                    <span id="modalTeacher" class="font-medium text-gray-700"></span>
                </div>
                <span id="modalPrice" class="text-3xl font-bold text-gray-900"></span>
            </div>
        </div>
    </div>
</div>
@endsection