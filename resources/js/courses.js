import { apiFetch } from './api-client';

let allCourses = []; // Stockage local pour le filtrage rapide

export const initCourses = async () => {
    const grid = document.getElementById('courses-grid');
    const searchInput = document.getElementById('courseSearch');

    try {
        const response = await apiFetch('/courses');
        allCourses = await response.json();
        
        renderCourses(allCourses);

        // Filtrage en temps réel
        searchInput?.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            const filtered = allCourses.filter(c => 
                c.title.toLowerCase().includes(term) || 
                c.category.toLowerCase().includes(term)
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

    grid.innerHTML = courses.map(course => `
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
            <div class="h-32 bg-gray-50 flex items-center justify-center text-4xl group-hover:bg-blue-50 transition">
                ${getEmoji(course.category)}
            </div>
            <div class="p-5">
                <span class="text-[10px] font-bold text-[#2563EB] uppercase">${course.category}</span>
                <h3 class="font-poppins font-bold text-gray-800 mb-2 truncate">${course.title}</h3>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-lg font-bold text-gray-900">${course.price}€</span>
                    <button onclick="showCourseDetails(${course.id})" class="text-sm font-medium text-[#2563EB] hover:underline">
                        Voir détails
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Logique de la Modal
window.showCourseDetails = (courseId) => {
    const course = allCourses.find(c => c.id === courseId);
    if (!course) return;

    document.getElementById('modalTitle').innerText = course.title;
    document.getElementById('modalDescription').innerText = course.description;
    document.getElementById('modalPrice').innerText = `${course.price}€`;
    document.getElementById('modalTeacher').innerText = course.teacher_name || 'Enseignant EduFlow';
    document.getElementById('modalCategory').innerText = course.category;

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

function getEmoji(category) {
    const map = { 'Web': '💻', 'Design': '🎨', 'Marketing': '📈', 'Business': '💼' };
    return map[category] || '📚';
}