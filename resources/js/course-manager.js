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
export const initCourseEdit = async () => {
    const container = document.getElementById('edit-course-container');
    const form = document.getElementById('edit-course-form');
    if (!container || !form) return;

    const courseId = container.dataset.id;

    try {
        // 1. Charger les catégories et les détails du cours en parallèle
        const [catRes, courseRes] = await Promise.all([
            apiFetch('/categories'),
            apiFetch(`/courses/${courseId}`)
        ]);

        const categories = await catRes.json();
        const course = await courseRes.json();

        // 2. Remplir les catégories
        const select = document.getElementById('category_id');
        select.innerHTML = categories.map(c => 
            `<option value="${c.id}" ${c.id === course.category_id ? 'selected' : ''}>${c.name}</option>`
        ).join('');

        // 3. Remplir les champs du formulaire
        document.getElementById('title').value = course.title;
        document.getElementById('price').value = course.price;
        document.getElementById('description').value = course.description;

        // 4. Afficher le formulaire et masquer le spinner
        document.getElementById('loading-spinner').classList.add('hidden');
        form.classList.remove('hidden');

    } catch (e) {
        container.innerHTML = `<p class="text-red-500">Erreur lors du chargement des données.</p>`;
    }

    // 5. Gestion de la mise à jour (PUT)
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('submit-btn');
        btn.innerText = "Mise à jour...";
        btn.disabled = true;

        const updatedData = {
            title: document.getElementById('title').value,
            category_id: document.getElementById('category_id').value,
            price: document.getElementById('price').value,
            description: document.getElementById('description').value
        };

        try {
            const response = await apiFetch(`/courses/${courseId}`, {
                method: 'PUT',
                body: JSON.stringify(updatedData)
            });

            if (response.ok) {
                window.location.href = '/dashboard';
            } else {
                alert("Erreur lors de la modification");
            }
        } catch (err) {
            console.error(err);
        } finally {
            btn.disabled = false;
            btn.innerText = "Enregistrer les modifications";
        }
    });
};