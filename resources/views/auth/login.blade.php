@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-gray-100 p-10">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-poppins font-bold text-[#1E3A8A]">Bon retour !</h2>
            <p class="text-sm text-gray-500 font-roboto mt-2">Connectez-vous à votre espace EduFlow</p>
        </div>

        <div id="success-message" class="hidden mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-medium">
            Inscription réussie ! Vous pouvez maintenant vous connecter.
        </div>

        <form id="loginForm" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse Email</label>
                <input type="email" name="email" id="email" required 
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#2563EB] focus:border-transparent outline-none transition">
            </div>

            <div>
                <div class="flex justify-between mb-1">
                    <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <a href="/forgot-password" class="text-xs text-[#2563EB] hover:underline">Oublié ?</a>
                </div>
                <input type="password" name="password" id="password" required 
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#2563EB] focus:border-transparent outline-none transition">
            </div>

            <div id="error-message" class="hidden p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs"></div>

            <button type="submit" id="loginBtn"
                class="w-full bg-[#2563EB] text-white font-poppins font-bold py-3 rounded-lg hover:bg-[#1E3A8A] transition-all shadow-md">
                Se connecter
            </button>

            <p class="text-center text-sm text-gray-500 mt-6">
                Pas encore de compte ? <a href="/register" class="text-[#2563EB] font-bold hover:underline">S'inscrire</a>
            </p>
        </form>
    </div>
</div>
@endsection