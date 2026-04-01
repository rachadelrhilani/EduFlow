@extends('layouts.dashboard-layout')

@section('title', 'Mes Favoris')

@section('dashboard-content')
<div class="mb-6">
    <h1 class="text-2xl font-poppins font-bold text-gray-800">Mes cours sauvegardés 💖</h1>
    <p class="text-gray-500">Retrouvez ici tous les cours que vous avez mis de côté.</p>
</div>

<div id="favorites-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="col-span-full py-20 text-center animate-pulse text-gray-400">
        Chargement de vos favoris...
    </div>
</div>

@include('components.course-modal') 
@endsection