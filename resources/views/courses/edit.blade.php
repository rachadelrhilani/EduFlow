@extends('layouts.dashboard-layout')

@section('title', 'Modifier le cours')

@section('dashboard-content')
<div class="max-w-3xl mx-auto" id="edit-course-container" data-id="{{ $id }}">
    <div class="mb-8">
        <a href="/dashboard" class="text-sm text-gray-500 hover:text-[#2563EB] transition">← Retour</a>
        <h1 class="text-3xl font-poppins font-bold text-gray-900 mt-2">Modifier le cours</h1>
    </div>

    <div id="loading-spinner" class="text-center py-10">
        <p class="text-gray-400 animate-pulse">Chargement des données du cours...</p>
    </div>

    <form id="edit-course-form" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-6 hidden">
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Titre du cours</label>
            <input type="text" id="title" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#2563EB] outline-none transition">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Catégorie</label>
            <select id="category_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#2563EB] outline-none transition bg-white">
                </select>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Prix (€)</label>
            <input type="number" id="price" step="0.01" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#2563EB] outline-none transition">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Description</label>
            <textarea id="description" rows="5" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#2563EB] outline-none transition"></textarea>
        </div>

        <div class="pt-4 flex gap-4">
            <button type="submit" id="submit-btn" class="flex-1 bg-[#2563EB] hover:bg-[#1E3A8A] text-white font-bold py-4 rounded-xl shadow-lg transition">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection