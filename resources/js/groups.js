import { apiFetch } from './api-client';

export const initGroups = async () => {
    const container = document.getElementById('groups-container');
    if (!container || !window.currentCourseIdForGroups) return;

    try {
        const response = await apiFetch(`/courses/${window.currentCourseIdForGroups}/groups`);
        const result = await response.json();

        if (response.ok && result.status === 'success') {
            renderGroups(result.data, container);
        } else {
            container.innerHTML = `<p class="text-red-500">Erreur : ${result.message || 'Impossible de charger les groupes.'}</p>`;
        }
    } catch (error) {
        container.innerHTML = `<p class="text-red-500">Une erreur s'est produite lors du chargement des groupes.</p>`;
    }
};

function renderGroups(groups, container) {
    if (!groups || groups.length === 0) {
        container.innerHTML = `<p class="text-gray-400 italic text-center py-6">Aucun étudiant inscrit ou aucun groupe formé pour ce cours.</p>`;
        return;
    }

    container.innerHTML = `
        <h3 class="text-xl font-poppins font-bold text-[#1E3A8A] mb-8">Structure des groupes</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            ${groups.map(group => `
                <div class="border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-blue-50 border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                        <h4 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                            <span>👥</span> ${group.name}
                        </h4>
                        <span class="text-xs font-bold bg-[#2563EB] text-white px-3 py-1 rounded-full uppercase tracking-widest">
                            ${group.students_count} / 25
                        </span>
                    </div>
                    <div class="p-0">
                        ${group.students && group.students.length > 0 ? `
                            <ul class="divide-y divide-gray-100">
                                ${group.students.map(student => `
                                    <li class="px-6 py-4 hover:bg-gray-50 transition flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold uppercase">
                                            ${student.name.charAt(0)}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">${student.name}</p>
                                            <p class="text-xs text-gray-500">${student.email}</p>
                                        </div>
                                    </li>
                                `).join('')}
                            </ul>
                        ` : `
                            <p class="text-gray-400 italic text-sm text-center py-6">Groupe vide</p>
                        `}
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}
