// resources/js/dashboard.js
import { apiFetch } from './api-client';

export const initDashboard = async () => {
    const role = localStorage.getItem('user_role');
    const name = localStorage.getItem('user_name');
    
    // Mise à jour de l'identité dans le layout
    const nameDisplay = document.getElementById('user-display-name');
    if (nameDisplay) nameDisplay.innerText = name || 'Utilisateur';
    
    // Dispatch selon le rôle
    if (role === 'enseignant') {
        console.log("Exécution Dashboard Enseignant...");
        await renderTeacherDashboard();
    } else {
        console.log("Exécution Dashboard Enseignant...");
        await renderStudentDashboard();
    }
};

/**
 * Fonctions Utilitaires pour le rendu
 */
const updateElement = (id, html) => {
    const el = document.getElementById(id);
    if (el) el.innerHTML = html;
};

const updateTitle = (text) => {
    const el = document.getElementById('current-page-title');
    if (el) el.innerText = text;
};

// --- RENDU ENSEIGNANT ---
async function renderTeacherDashboard() {
    updateTitle("Espace Enseignant");

    updateElement('sidebar-menu', `
        <a href="/dashboard" class="flex items-center p-3 bg-[#2563EB] text-white rounded-lg transition shadow-md">
            <span class="mr-3">📊</span> Stats Générales
        </a>
        <a href="/courses/create" class="flex items-center p-3 text-blue-100 hover:bg-[#2563EB] hover:text-white rounded-lg transition">
            <span class="mr-3">➕</span> Créer un Cours
        </a>
        <a href="/my-students" class="flex items-center p-3 text-blue-100 hover:bg-[#2563EB] hover:text-white rounded-lg transition">
            <span class="mr-3">👥</span> Mes Étudiants
        </a>
    `);

    try {
        // Chargement simultané des stats et des cours via tes nouvelles méthodes Service/Repo
        const [statsRes, coursesRes] = await Promise.all([
            apiFetch('/my-stats'),
            apiFetch('/my-courses')
        ]);
        
        const stats = await statsRes.json();
        const myCourses = await coursesRes.json();
        
        // 1. Rendu des Stats
        updateElement('stats-container', `
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-[#2563EB]">
                <p class="text-gray-500 text-xs font-bold uppercase">Revenus</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">${stats.total_revenue || 0}€</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
                <p class="text-gray-500 text-xs font-bold uppercase">Inscriptions</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">${stats.total_enrollments || 0}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-purple-500">
                <p class="text-gray-500 text-xs font-bold uppercase">Note</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">4.8/5</h3>
            </div>
        `);
        
        // 2. Préparation du HTML de la liste des cours
        const coursesListHtml = myCourses.length > 0 
            ? myCourses.map(course => `
                <div class="flex items-center justify-between p-4 mb-3 bg-white border border-gray-100 rounded-xl hover:shadow-sm transition">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-2xl">
                            ${getEmoji(course.category?.name)}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">${course.title}</h4>
                            <p class="text-xs text-gray-500">${course.category?.name || 'Général'} • ${course.price}€</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="location.href='/courses/edit/${course.id}'" class="p-2 text-gray-400 hover:text-blue-600 transition">
                            ✏️
                        </button>
                        <button onclick="deleteCourse(${course.id}, this)" class="p-2 text-gray-400 hover:text-red-600 transition" title="Supprimer">
                            🗑️
                        </button>
                    </div>
                </div>
            `).join('')
            : `<p class="text-center py-10 text-gray-400">Aucun cours publié pour le moment.</p>`;

        // 3. Rendu final dans main-content
        updateElement('main-content', `
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-[#1E3A8A]">Mes derniers cours publiés</h3>
                <button class="text-sm text-[#2563EB] hover:underline font-medium">Voir tout</button>
            </div>
            <div id="teacher-courses-container">
                ${coursesListHtml}
            </div>
        `);

    } catch (e) { 
        console.error("Stats/Courses Error:", e);
        updateElement('main-content', `<p class="text-red-500">Erreur lors du chargement des données.</p>`);
    }
}

// --- RENDU ÉTUDIANT ---
async function renderStudentDashboard() {
    updateTitle("Mon Apprentissage");

    updateElement('sidebar-menu', `
        <a href="/dashboard" class="flex items-center p-3 bg-[#2563EB] text-white rounded-lg transition shadow-md">
            <span class="mr-3">📖</span> Mes Cours
        </a>
        <a href="/courses" class="flex items-center p-3 text-blue-100 hover:bg-[#2563EB] hover:text-white rounded-lg transition">
            <span class="mr-3">🔍</span> Catalogue
        </a>
        <a href="/favorites" class="flex items-center p-3 text-blue-100 hover:bg-[#2563EB] hover:text-white rounded-lg transition">
            <span class="mr-3">❤️</span> Favoris
        </a>
    `);

    try {
        const [favRes, recRes] = await Promise.all([
            apiFetch('/favorites'),
            apiFetch('/recommendations')
        ]);
        
        const favorites = await favRes.json();
        const recommendations = await recRes.json();

        updateElement('stats-container', `
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase">Cours en cours</p>
                <h3 class="text-3xl font-bold text-[#1E3A8A] mt-1">${favorites.length}</h3>
            </div>
        `);

        updateElement('main-content', `
            <h3 class="text-lg font-bold text-[#1E3A8A] mb-6">Recommandations pour vous</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                ${recommendations.map(c => `
                    <div class="p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition cursor-pointer flex justify-between items-center">
                        <div>
                            <p class="font-bold text-gray-800">${c.title}</p>
                            <p class="text-xs text-gray-500">${c.category || 'Formation'}</p>
                        </div>
                        <span class="text-blue-500">→</span>
                    </div>
                `).join('')}
            </div>
        `);
    } catch (e) { console.error("Student Dashboard Error:", e); }
}
function getEmoji(categoryName) {
    const map = { 'Web': '💻', 'Design': '🎨', 'Marketing': '📈', 'Business': '💼' };
    return map[categoryName] || '📚';
}
// Exposer la fonction au window pour qu'elle soit accessible via l'attribut onclick
window.deleteCourse = async (courseId, btnElement) => {
    // Confirmation de sécurité
    if (!confirm("Êtes-vous sûr de vouloir supprimer ce cours ? Cette action est irréversible.")) {
        return;
    }

    try {
        const response = await apiFetch(`/courses/${courseId}`, {
            method: 'DELETE'
        });

        if (response.ok) {
            // Trouver la ligne du cours (le parent le plus proche avec la classe de bordure)
            const courseRow = btnElement.closest('.flex.items-center.justify-between');
            
            // Animation de sortie
            courseRow.classList.add('opacity-0', 'scale-95', 'transition-all', 'duration-300');
            
            setTimeout(() => {
                courseRow.remove();
                
                // Si la liste est vide après suppression, on peut ré-afficher un message
                const container = document.getElementById('teacher-courses-container');
                if (container && container.children.length === 0) {
                    container.innerHTML = `<p class="text-center py-10 text-gray-400">Aucun cours publié pour le moment.</p>`;
                }
            }, 300);

        } else {
            const error = await response.json();
            alert("Erreur : " + (error.error || "Impossible de supprimer le cours."));
        }
    } catch (e) {
        console.error("Delete Error:", e);
        alert("Une erreur réseau est survenue.");
    }
};