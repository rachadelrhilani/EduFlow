import { apiFetch } from './api-client';

let stripe, cardElement;

export const initEnrollment = () => {
    const cardContainer = document.getElementById('card-element');
    if (!cardContainer) return;

    // Récupérer la clé publique Stripe depuis les variables d'environnement
    const stripeKey = import.meta.env.VITE_STRIPE_PUBLIC_KEY;
    
    if (stripeKey && window.Stripe) {
        stripe = window.Stripe(stripeKey);
        const elements = stripe.elements();
        cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a',
                },
            }
        });
        cardElement.mount('#card-element');

        // Gérer l'affichage des erreurs en temps réel
        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
                displayError.classList.remove('hidden');
            } else {
                displayError.textContent = '';
                displayError.classList.add('hidden');
            }
        });
    }

    // On expose la fonction au window pour le bouton de la modal
    window.enrollInCourse = async (courseId) => {
        const btn = document.getElementById('enroll-submit-btn');
        const displayError = document.getElementById('card-errors');
        const originalText = btn.innerText;

        btn.disabled = true;
        btn.innerText = "Traitement du paiement...";
        displayError.classList.add('hidden');

        try {
            let stripeToken = 'tok_visa'; // Valeur par défaut / fallback si pas de clé

            // Si Stripe est correctement initialisé, on crée le token
            if (stripe && cardElement) {
                const {token, error} = await stripe.createToken(cardElement);
                
                if (error) {
                    displayError.textContent = error.message;
                    displayError.classList.remove('hidden');
                    // On stoppe l'inscription
                    btn.disabled = false;
                    btn.innerText = originalText;
                    return;
                }
                
                stripeToken = token.id;
            } else if (!stripeKey) {
                console.warn("Clé Stripe non définie, utilisation du fallback.");
            }

            const response = await apiFetch(`/courses/${courseId}/enroll`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ stripeToken: stripeToken })
            });

            if (response.ok) {
                window.showToast("Inscription validée et paiement réussi !", "success");
                setTimeout(() => {
                    window.location.href = '/dashboard'; // Redirection vers "Mes cours"
                }, 1500);
            } else {
                const error = await response.json();
                window.showToast(error.message || error.error || "Erreur lors du paiement.", "error");
            }
        } catch (e) {
            console.error(e);
            window.showToast("Erreur réseau.", "error");
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        }
    };
};