// resources/js/api-client.js

export const apiFetch = async (endpoint, options = {}) => {
    const token = localStorage.getItem('eduflow_token');

    // 1. On prépare les headers de base
    const headers = {
        'Accept': 'application/json',
        'Authorization': token ? `Bearer ${token}` : '',
        ...options.headers
    };

    // 2. Erreur commune : Ne pas mettre Content-Type si c'est du FormData 
    // ou s'il n'y a pas de body
    if (options.body && !(options.body instanceof FormData)) {
        headers['Content-Type'] = 'application/json';
    }

    const response = await fetch(`/api${endpoint}`, {
        ...options,
        headers: headers,
        credentials: 'include'
    });

    // 3. Gestion de l'expiration du token
    if (response.status === 401) {
        localStorage.removeItem('eduflow_token');
        // On supprime aussi le cookie pour rester cohérent avec ton nouveau système
        document.cookie = "jwt_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        window.location.href = '/login?session=expired';
    }

    return response;
};