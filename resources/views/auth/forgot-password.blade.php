@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-10 border border-gray-100">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-poppins font-bold text-[#1E3A8A]">Mot de passe oublié ?</h2>
            <p class="text-sm text-gray-500 mt-2">Entrez votre email pour recevoir un lien de récupération.</p>
        </div>

        <form id="forgotPasswordForm" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Votre Email professionnel</label>
                <input type="email" name="email" id="forgot_email" required 
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#2563EB] outline-none transition">
            </div>

            <div id="status-message" class="hidden p-3 rounded-lg text-xs"></div>

            <button type="submit" id="forgotBtn"
                class="w-full bg-[#2563EB] text-white font-poppins font-bold py-3 rounded-lg hover:bg-[#1E3A8A] transition-all shadow-md">
                Envoyer le lien
            </button>
        </form>
    </div>
</div>
@endsection