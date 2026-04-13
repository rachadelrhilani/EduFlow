export const checkAuth = () => {
    const token = localStorage.getItem('eduflow_token');

    // On laisse le backend Laravel (Middlewares: jwt.web, role:enseignant, role:étudiant) 
    // gérer les redirections (302) de façon transparente et sécurisée en amont. 
    // Cela évite l'effet de flash à l'écran (page affichée brièvement puis redirigée par le JS).

    if (!token) {
        // Optionnel : on pourrait forcer un retour au login si on n'a plus de token d'API, 
        // mais le cookie assure la session web.
        return false;
    }

    return true;
};

export const logout = async () => {
    const token = localStorage.getItem('eduflow_token');
    
    // Déconnexion propre côté serveur pour invalider le JWT
    if (token) {
        try {
            await fetch('/api/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
        } catch (e) {
            console.error("Erreur serveur lors de la déconnexion", e);
        }
    }

    // Nettoyage absolu du frontend
    localStorage.removeItem('eduflow_token');
    localStorage.removeItem('user_role');
    localStorage.removeItem('user_name');
    
    // Expiration forcée du cookie
    document.cookie = "jwt_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    
    window.location.href = '/login';
};
window.logout = logout;