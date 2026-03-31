@extends('layouts.dashboard-layout')

@section('title', 'Aperçu général')

@section('dashboard-content')
    <div id="stats-container" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @for ($i = 0; $i < 3; $i++)
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 animate-pulse">
            <div class="h-3 bg-gray-200 rounded w-1/2 mb-3"></div>
            <div class="h-8 bg-gray-100 rounded w-3/4"></div>
        </div>
        @endfor
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8" id="main-content">
        <div class="animate-pulse flex space-y-4 flex-col">
            <div class="h-4 bg-gray-200 rounded w-1/4"></div>
            <div class="h-24 bg-gray-50 rounded"></div>
            <div class="h-24 bg-gray-50 rounded"></div>
        </div>
    </div>
@endsection