import { apiFetch } from './api-client';

export const initCourseCreate = async () => {
    const form = document.getElementById('create-course-form');
    const categorySelect = document.getElementById('category_id');

    if (!form) return;

    // Charger les catégories pour le menu déroulant
    try {
        const response = await apiFetch('/categories'); // Assume que tu as cette route
        const categories = await response.json();
        console.log("ceci est create")
        
        categorySelect.innerHTML += categories.map(cat => 
            `<option value="${cat.id}">${cat.name}</option>`
        ).join('');
    } catch (e) {
        console.error("Erreur chargement catégories", e);
    }

    // Gérer la soumission du formulaire
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-btn');
        const originalText = submitBtn.innerText;
        submitBtn.innerText = "Création en cours...";
        submitBtn.disabled = true;

        const courseData = {
            title: document.getElementById('title').value,
            category_id: document.getElementById('category_id').value,
            price: document.getElementById('price').value,
            description: document.getElementById('description').value
        };

        try {
            const response = await apiFetch('/courses', {
                method: 'POST',
                body: JSON.stringify(courseData)
            });

            if (response.ok) {
                alert("Cours créé avec succès !");
                window.location.href = '/dashboard';
            } else {
                const error = await response.json();
                alert("Erreur : " + (error.message || "Impossible de créer le cours"));
            }
        } catch (error) {
            console.error("Erreur réseau", error);
        } finally {
            submitBtn.innerText = originalText;
            submitBtn.disabled = false;
        }
    });
};