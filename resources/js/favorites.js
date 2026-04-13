import { apiFetch } from './api-client';

let favoriteCourses = [];

export const initFavorites = async () => {
    const grid = document.getElementById('favorites-grid');
    if (!grid) return;

    try {
        const response = await apiFetch('/favorites');
        favoriteCourses = await response.json();
        renderFavorites(favoriteCourses);
    } catch (e) {
        grid.innerHTML = `<p class="text-red-500">Erreur lors du chargement des favoris.</p>`;
    }
};

function renderFavorites(courses) {
    const grid = document.getElementById('favorites-grid');
    
    if (courses.length === 0) {
        grid.innerHTML = `<div class="col-span-full py-20 text-center text-gray-400 italic bg-white rounded-2xl border-2 border-dashed">Aucun favori pour le moment.</div>`;
        return;
    }

    grid.innerHTML = courses.map(course => `
        <div id="fav-card-${course.id}" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition relative">
            
            <!-- Bouton Retirer -->
            <button onclick="removeFromFavorites(${course.id}, this)" 
                    class="absolute top-3 right-3 z-10 p-2 bg-white/80 backdrop-blur-sm rounded-full shadow-sm text-red-500 transition transform hover:scale-110">
                <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>

            <div class="h-32 bg-gray-50 flex items-center justify-center text-4xl">
                ${getEmoji(course.category.name)}
            </div>
            
            <div class="p-5">
                <span class="text-[10px] font-bold text-[#2563EB] uppercase">${course.category.name}</span>
                <h3 class="font-poppins font-bold text-gray-800 mb-2 truncate">${course.title}</h3>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-lg font-bold text-gray-900">${course.price}€</span>
                    <button onclick="showCourseDetailsFav(${course.id})" class="text-sm font-medium text-[#2563EB] hover:underline">
                        Voir détails
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Retrait instantané
window.removeFromFavorites = async (courseId, btn) => {
    try {
        const response = await apiFetch(`/courses/${courseId}/favorite`, { method: 'POST' });
        if (response.ok) {
            window.showToast("Retiré des favoris", "info");
            const card = document.getElementById(`fav-card-${courseId}`);
            card.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                card.remove();
                if (document.querySelectorAll('[id^="fav-card-"]').length === 0) renderFavorites([]);
            }, 200);
        }
    } catch (e) { console.error(e); }
};

// Modal adaptée aux favoris
window.showCourseDetailsFav = (courseId) => {
    const course = favoriteCourses.find(c => c.id === courseId);
    if (!course) return;

    document.getElementById('modalTitle').innerText = course.title;
    document.getElementById('modalDescription').innerText = course.description;
    document.getElementById('modalPrice').innerText = `${course.price}€`;
    document.getElementById('modalTeacher').innerText = course.teacher?.name || 'Enseignant EduFlow';
    document.getElementById('modalCategory').innerText = course.category.name;

    const modal = document.getElementById('courseModal');
    const content = document.getElementById('modalContent');
    modal.classList.remove('hidden');
    setTimeout(() => content.classList.remove('scale-95', 'opacity-0'), 10);
};

window.closeCourseModal = () => {
    const modal = document.getElementById('courseModal');
    const content = document.getElementById('modalContent');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 200);
};

function getEmoji(name) {
    const map = { 'Web': '💻', 'Design': '🎨', 'Marketing': '📈', 'Business': '💼' };
    return map[name] || '📚';
}