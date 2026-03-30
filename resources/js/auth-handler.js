export const initRegister = () => {
    const form = document.getElementById('registerForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const btn = form.querySelector('button[type="submit"]');
        const errorDiv = document.getElementById('error-message');
        
        // Nettoyage avant envoi
        errorDiv.classList.add('hidden');
        errorDiv.innerHTML = '';

        // Construction manuelle du payload pour être certain des clés envoyées
        const payload = {
            name: form.querySelector('input[name="name"]').value,
            email: form.querySelector('input[name="email"]').value,
            password: form.querySelector('input[name="password"]').value,
            password_confirmation: form.querySelector('input[name="password_confirmation"]').value,
            role: form.querySelector('input[name="role"]:checked')?.value,
            interests: [] // Optionnel selon ton controller
        };

        // Debug visuel pour toi
        console.log("Payload envoyé :", payload);

        btn.disabled = true;
        btn.innerHTML = '<span class="animate-spin inline-block mr-2">↻</span>Traitement...';

        try {
            const response = await fetch('/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok) {
                // Succès : redirection
                window.location.href = '/login?registered=true';
            } else if (response.status === 422) {
                // Erreurs de validation (Nom déjà pris, mdp trop court, etc.)
                displayValidationErrors(data.errors);
            } else {
                // Autres erreurs (500, etc.)
                errorDiv.innerText = data.message || "Une erreur est survenue.";
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            console.error("Erreur réseau :", error);
            errorDiv.innerText = "Impossible de joindre le serveur EduFlow.";
            errorDiv.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerText = "Créer mon compte";
        }
    });
};

function displayValidationErrors(errors) {
    const errorDiv = document.getElementById('error-message');
    errorDiv.classList.remove('hidden');
    
    // On transforme l'objet d'erreurs en liste lisible
    const messages = Object.values(errors).flat().join('<br>');
    errorDiv.innerHTML = `<div class="bg-red-50 text-red-700 p-3 rounded-lg border border-red-200">${messages}</div>`;
}