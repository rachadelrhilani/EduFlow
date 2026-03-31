@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center">
    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden flex flex-col md:flex-row">

        <div class="md:w-1/3 bg-[#1E3A8A] p-8 text-white flex flex-col justify-center">
            <h2 class="text-2xl font-poppins font-bold mb-4">Rejoignez EduFlow</h2>
            <p class="text-sm font-roboto opacity-80">
                Créez votre compte pour accéder aux formations ou partager votre savoir avec nos étudiants.
            </p>
            <div class="mt-8 space-y-4">
                <div class="flex items-center text-xs">
                    <span class="w-6 h-6 rounded-full bg-[#2563EB] flex items-center justify-center mr-3">✓</span>
                    Accès illimité aux cours
                </div>
                <div class="flex items-center text-xs">
                    <span class="w-6 h-6 rounded-full bg-[#2563EB] flex items-center justify-center mr-3">✓</span>
                    Paiement sécurisé via Stripe
                </div>
            </div>
        </div>

        <div class="md:w-2/3 p-10">
            <form id="registerForm" class="space-y-5">
                @csrf
                <div>
                    <h3 class="text-xl font-poppins font-bold text-gray-800">Inscription</h3>
                    <p class="text-sm text-gray-500 mb-6">Choisissez votre profil pour commencer.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer group">
                        <input type="radio" name="role" value="étudiant" class="peer sr-only" checked>
                        <div class="p-4 border-2 border-gray-100 rounded-xl transition-all group-hover:border-blue-100 peer-checked:border-[#2563EB] peer-checked:bg-blue-50">
                            <p class="text-center font-poppins font-bold text-gray-600 peer-checked:text-[#1E3A8A]">Étudiant</p>
                        </div>
                    </label>
                    <label class="cursor-pointer group">
                        <input type="radio" name="role" value="enseignant" class="peer sr-only">
                        <div class="p-4 border-2 border-gray-100 rounded-xl transition-all group-hover:border-blue-100 peer-checked:border-[#2563EB] peer-checked:bg-blue-50">
                            <p class="text-center font-poppins font-bold text-gray-600 peer-checked:text-[#1E3A8A]">Enseignant</p>
                        </div>
                    </label>
                </div>

                <div class="space-y-4">
                    <input type="text" id="name" name="name" placeholder="Nom complet" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#2563EB] focus:border-transparent outline-none transition font-roboto">

                    <input type="email" id="email" name="email" placeholder="Adresse Email" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#2563EB] focus:border-transparent outline-none transition font-roboto">

                    <div class="grid grid-cols-2 gap-4">
                        <input type="password" id="password" name="password" placeholder="Mot de passe" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#2563EB] focus:border-transparent outline-none transition font-roboto">

                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirmer" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#2563EB] focus:border-transparent outline-none transition font-roboto">
                    </div>
                </div>
                <div id="interests-section" class="space-y-3">
                    <p class="text-sm font-poppins font-bold text-gray-700">Vos centres d'intérêt :</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Développement Web', 'Design UI/UX', 'Marketing', 'Data Science', 'Business', 'Langues'] as $interest)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="interests[]" value="{{ $interest }}" class="peer sr-only">
                            <span class="px-3 py-1.5 border border-gray-200 rounded-full text-xs font-medium text-gray-500 transition-all peer-checked:bg-[#2563EB] peer-checked:text-white peer-checked:border-[#2563EB] hover:bg-gray-50">
                                {{ $interest }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div id="error-message" class="hidden text-red-500 text-xs font-roboto"></div>

                <button type="submit" id="submitBtn"
                    class="w-full bg-[#2563EB] text-white font-poppins font-bold py-3 rounded-lg hover:bg-[#1E3A8A] transition-all transform hover:scale-[1.02] active:scale-95 shadow-md">
                    Créer mon compte
                </button>

                <p class="text-center text-xs text-gray-400 mt-4 font-roboto">
                    Déjà inscrit ? <a href="/login" class="text-[#2563EB] font-bold hover:underline">Connectez-vous</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection