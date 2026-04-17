import { apiFetch } from './api-client';

export const initMyStudents = async () => {
    const container = document.getElementById('students-groups-container');
    if (!container) return;

    try {
        const response = await apiFetch('/teacher/students');
        const result = await response.json();

        if (response.ok && result.status === 'success') {
            renderTeacherStudents(result.data, container);
        } else {
            container.innerHTML = `<p class="text-red-500">Erreur : ${result.message || 'Impossible de charger les étudiants.'}</p>`;
        }
    } catch (error) {
        console.error("Fetch Error:", error);
        container.innerHTML = `<p class="text-red-500">Une erreur s'est produite lors du chargement des groupes.</p>`;
    }
};

function renderTeacherStudents(groups, container) {
    if (!groups || groups.length === 0) {
        container.innerHTML = `
            <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
                <div class="text-6xl mb-4">👥</div>
                <h4 class="text-xl font-bold text-gray-800">Aucun étudiant pour le moment</h4>
                <p class="text-gray-500 mt-2">Dès que des étudiants s'inscriront à vos cours, ils apparaîtront ici par groupe.</p>
                <a href="/courses/create" class="inline-block mt-6 px-6 py-3 bg-[#2563EB] text-white rounded-xl font-bold hover:bg-blue-700 transition">
                    Créer un nouveau cours
                </a>
            </div>
        `;
        return;
    }

    // Grouper par cours pour un meilleur affichage
    const coursesMap = {};
    groups.forEach(group => {
        if (!coursesMap[group.course.id]) {
            coursesMap[group.course.id] = {
                title: group.course.title,
                groups: []
            };
        }
        coursesMap[group.course.id].groups.push(group);
    });

    container.innerHTML = Object.values(coursesMap).map(course => `
        <div class="mb-12">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-blue-100 text-[#2563EB] rounded-lg flex items-center justify-center text-xl">
                    📚
                </div>
                <h4 class="text-xl font-bold text-gray-800">${course.title}</h4>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                ${course.groups.map(group => `
                    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition duration-300">
                        <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                            <h5 class="font-bold text-gray-700 flex items-center gap-2">
                                <span class="text-blue-500">👥</span> ${group.name}
                            </h5>
                            <span class="text-xs font-bold bg-blue-600 text-white px-3 py-1 rounded-full uppercase tracking-wider">
                                ${group.students_count} élèves
                            </span>
                        </div>
                        <div>
                            ${group.students && group.students.length > 0 ? `
                                <ul class="divide-y divide-gray-50">
                                    ${group.students.map(student => `
                                        <li class="px-6 py-4 flex items-center gap-4 hover:bg-blue-50/30 transition">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-sm">
                                                ${student.name.split(' ').map(n => n[0]).join('').toUpperCase()}
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-gray-800">${student.name}</p>
                                                <p class="text-xs text-gray-400">${student.email}</p>
                                            </div>
                                            <button class="text-gray-300 hover:text-blue-500 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </button>
                                        </li>
                                    `).join('')}
                                </ul>
                            ` : `
                                <div class="px-6 py-8 text-center text-gray-400 italic text-sm">
                                    Aucun étudiant dans ce groupe
                                </div>
                            `}
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
    `).join('');
}
