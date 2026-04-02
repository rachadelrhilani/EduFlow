@extends('layouts.dashboard-layout')

@section('title', 'Créer un nouveau cours')

@section('dashboard-content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <a href="/dashboard" class="text-sm text-gray-500 hover:text-[#2563EB] transition">← Retour au tableau de bord</a>
        <h1 class="text-3xl font-poppins font-bold text-gray-900 mt-2">Publier un nouveau cours</h1>
    </div>

    <form id="create-course-form" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-6">
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Titre du cours</label>
            <input type="text" id="title" required
                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#2563EB] focus:border-transparent outline-none transition"
                placeholder="Ex: Maîtriser Laravel 11 par la pratique">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Catégorie</label>
            <select id="category_id" required
                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#2563EB] outline-none transition bg-white">
                <option value="">Sélectionnez une catégorie</option>
                </select>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Prix (€)</label>
            <input type="number" id="price" step="0.01" required
                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#2563EB] outline-none transition"
                placeholder="0.00">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Description détaillée</label>
            <textarea id="description" rows="5" required
                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#2563EB] outline-none transition"
                placeholder="Décrivez ce que les étudiants vont apprendre..."></textarea>
        </div>

        <div class="pt-4">
            <button type="submit" id="submit-btn"
                class="w-full bg-[#2563EB] hover:bg-[#1E3A8A] text-white font-bold py-4 rounded-xl shadow-lg transition transform hover:-translate-y-1">
                Créer le cours
            </button>
        </div>
    </form>
</div>
@endsection