import { apiFetch } from './api-client';

let allCourses = []; // Stockage local pour le filtrage rapide

export const initCourses = async () => {
    const grid = document.getElementById('courses-grid');
    const searchInput = document.getElementById('courseSearch');

    try {
        const response = await apiFetch('/courses');
        allCourses = await response.json();
        
        renderCourses(allCourses);

        // Filtre en temps réel
        searchInput?.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            const filtered = allCourses.filter(c => 
                c.title.toLowerCase().includes(term) || 
                c.category.name.toLowerCase().includes(term)
            );
            renderCourses(filtered);
        });

    } catch (e) {
        grid.innerHTML = `<p class="text-red-500">Erreur lors du chargement des cours.</p>`;
    }
};

function renderCourses(courses) {
    const grid = document.getElementById('courses-grid');
    if (!grid) return;

    if (courses.length === 0) {
        grid.innerHTML = `<p class="col-span-full text-center text-gray-400 py-10">Aucun cours trouvé.</p>`;
        return;
    }

    grid.innerHTML = courses.map(course => {
    // On vérifie si le cours est déjà liké (suppose que ton objet course a un booléen 'is_favorite')
    const heartColor = course.is_favorite ? 'text-red-500 fill-current' : 'text-gray-400 hover:text-red-500';

    return `
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group relative">
            
            <button onclick="toggleFavorite(${course.id}, this)" 
                    class="absolute top-3 right-3 z-10 p-2 bg-white/80 backdrop-blur-sm rounded-full shadow-sm transition transform hover:scale-110 ${heartColor}">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>

            <div class="h-32 bg-gray-50 flex items-center justify-center text-4xl group-hover:bg-blue-50 transition">
                ${getEmoji(course.category)}
            </div>
            
            <div class="p-5">
                <span class="text-[10px] font-bold text-[#2563EB] uppercase">${course.category.name}</span>
                <h3 class="font-poppins font-bold text-gray-800 mb-2 truncate">${course.title}</h3>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-lg font-bold text-gray-900">${course.price}€</span>
                    <button onclick="showCourseDetails(${course.id})" class="text-sm font-medium text-[#2563EB] hover:underline">
                        Voir détails
                    </button>
                </div>
            </div>
        </div>
    `;
}).join('');
}

// Logique de la Modal
window.showCourseDetails = (courseId) => {
    const course = allCourses.find(c => c.id === courseId);
    if (!course) return;

    document.getElementById('modalTitle').innerText = course.title;
    document.getElementById('modalDescription').innerText = course.description;
    document.getElementById('modalPrice').innerText = `${course.price}€`;
    document.getElementById('modalTeacher').innerText = course.teacher.name || 'Enseignant EduFlow';
    document.getElementById('modalCategory').innerText = course.category.name;

    const modal = document.getElementById('courseModal');
    const content = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
    }, 10);
};

window.closeCourseModal = () => {
    const modal = document.getElementById('courseModal');
    const content = document.getElementById('modalContent');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 200);
};

// resources/js/courses.js

// 1. Déclarer la fonction
const toggleFavorite = async (courseId, btnElement) => {
    console.log("Tentative de toggle pour le cours:", courseId); // Pour vérifier le clic
    
    try {
        const response = await apiFetch(`/courses/${courseId}/favorite`, {
            method: 'POST'
        });

        if (response.ok) {
            const data = await response.json();
            
            // On bascule les classes CSS selon le retour du serveur
            if (data.attached) { 
                btnElement.classList.replace('text-gray-400', 'text-red-500');
                btnElement.classList.add('fill-current');
            } else {
                btnElement.classList.replace('text-red-500', 'text-gray-400');
                btnElement.classList.remove('fill-current');
            }
        }
    } catch (error) {
        console.error("Erreur Favoris:", error);
    }
};

// 2. L'EXPOSER AU WINDOW (C'est cette ligne qui débloque tout)
window.toggleFavorite = toggleFavorite;

function getEmoji(category) {
    const map = { 'Web': '💻', 'Design': '🎨', 'Marketing': '📈', 'Business': '💼' };
    return map[category] || '📚';
}