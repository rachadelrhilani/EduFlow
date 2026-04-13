@extends('layouts.dashboard-layout')

@section('title', 'Gestion des Groupes')

@section('dashboard-content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="/dashboard" class="text-[#2563EB] hover:underline flex items-center text-sm font-medium">
            ← Retour au tableau de bord
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8" id="groups-container">
    <div class="animate-pulse space-y-4">
        <div class="h-6 bg-gray-200 rounded w-1/4"></div>
        <div class="h-32 bg-gray-50 rounded"></div>
        <div class="h-32 bg-gray-50 rounded"></div>
    </div>
</div>

<!-- On passe l'ID du cours au JS via une variable globale ou un attribut data -->
<script>
    window.currentCourseIdForGroups = {{ $courseId }};
</script>
@endsection
