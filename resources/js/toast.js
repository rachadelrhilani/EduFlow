export function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed bottom-5 right-5 z-[9999] flex flex-col gap-3';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const colorClass = type === 'success' ? 'bg-green-500' : (type === 'error' ? 'bg-red-500' : 'bg-[#2563EB]');
    toast.className = `px-6 py-4 rounded-xl shadow-2xl text-white font-medium flex items-center gap-3 transform transition-all duration-300 translate-y-10 opacity-0 ${colorClass}`;
    
    const icon = type === 'success' ? '✅' : (type === 'error' ? '❌' : '🔔');
    
    toast.innerHTML = `<span class="text-xl">${icon}</span> <span>${message}</span>`;
    container.appendChild(toast);

    // Animate in
    requestAnimationFrame(() => {
        setTimeout(() => {
            toast.classList.remove('translate-y-10', 'opacity-0');
        }, 10);
    });

    // Animate out
    setTimeout(() => {
        toast.classList.add('translate-y-10', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
