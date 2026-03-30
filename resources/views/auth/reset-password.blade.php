@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-gray-100 p-10">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-poppins font-bold text-[#1E3A8A]">Nouveau mot de passe</h2>
            <p class="text-sm text-gray-500 font-roboto mt-2">Sécurisez à nouveau votre compte EduFlow</p>
        </div>

        <form id="resetPasswordForm" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse Email</label>
                <input type="email" name="email" id="email" value="{{ request()->email }}" required readonly
                    class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 outline-none font-roboto cursor-not-allowed">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                <input type="password" name="password" id="password" required 
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#2563EB] focus:border-transparent outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required 
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#2563EB] focus:border-transparent outline-none transition">
            </div>

            <div id="error-message" class="hidden p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs"></div>

            <button type="submit" id="resetBtn"
                class="w-full bg-[#2563EB] text-white font-poppins font-bold py-3 rounded-lg hover:bg-[#1E3A8A] transition-all shadow-md">
                Réinitialiser le mot de passe
            </button>
        </form>
    </div>
</div>
@endsection