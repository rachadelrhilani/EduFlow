@extends('layouts.dashboard-layout')

@section('title', 'Mes Étudiants')

@section('dashboard-content')
<div class="mb-8">
    <h3 class="text-2xl font-poppins font-bold text-[#1E3A8A]">Gestion des Étudiants par Groupe</h3>
    <p class="text-gray-500 mt-1">Consultez la liste de vos élèves répartis dans vos différents cours.</p>
</div>

<div id="students-groups-container">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-pulse">
        <div class="h-64 bg-white rounded-2xl shadow-sm border border-gray-100"></div>
        <div class="h-64 bg-white rounded-2xl shadow-sm border border-gray-100"></div>
    </div>
</div>

<script>
    if (!localStorage.getItem('eduflow_token')) {
        window.location.href = '/login';
    }
</script>
@endsection
