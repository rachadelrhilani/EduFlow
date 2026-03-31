// resources/js/guards.js

export const checkAuth = () => {
    const token = localStorage.getItem('eduflow_token');
    const protectedPaths = ['/dashboard', '/courses', '/profile'];
    const currentPath = window.location.pathname;

    // protection pour les pages
    if (protectedPaths.some(path => currentPath.startsWith(path)) && !token) {
        window.location.href = '/login?error=unauthorized';
        return false;
    }
    return true;
};

export const logout = () => {
    localStorage.removeItem('eduflow_token');
    localStorage.removeItem('user_role');
    localStorage.removeItem('user_name');
    window.location.href = '/login';
};
window.logout = logout;