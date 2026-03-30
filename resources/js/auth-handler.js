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
            interests: []
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
    
    const messages = Object.values(errors).flat().join('<br>');
    errorDiv.innerHTML = `<div class="bg-red-50 text-red-700 p-3 rounded-lg border border-red-200">${messages}</div>`;
}

export const initLogin = () => {
    const loginForm = document.getElementById('loginForm');
    if (!loginForm){
      return;
    } 

    // Vérifier si on vient de s'inscrire (via l'URL)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('registered')) {
        document.getElementById('success-message')?.classList.remove('hidden');
    }

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const btn = document.getElementById('loginBtn');
        const errorDiv = document.getElementById('error-message');
        
        const payload = {
            email: loginForm.querySelector('input[name="email"]').value,
            password: loginForm.querySelector('input[name="password"]').value,
        };

        btn.disabled = true;
        btn.innerText = "Connexion...";
        errorDiv.classList.add('hidden');

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok) {
                // --- CRUCIAL : Stockage du JWT ---
                localStorage.setItem('eduflow_token', data.access_token || data.token);
                
                // On stocke aussi les infos de base de l'utilisateur si disponibles
                if (data.user) {
                    localStorage.setItem('user_role', data.user.role);
                    localStorage.setItem('user_name', data.user.name);
                }

                // Redirection vers le dashboard
                window.location.href = '/dashboard';
            } else {
                errorDiv.innerText = data.error || "Identifiants invalides.";
                errorDiv.classList.remove('hidden');
            }
        } catch (err) {
            errorDiv.innerText = "Erreur de connexion au serveur.";
            errorDiv.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerText = "Se connecter";
        }
    });
};