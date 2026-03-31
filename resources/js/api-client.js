// resources/js/api-client.js

export const apiFetch = async (endpoint, options = {}) => {
    const token = localStorage.getItem('eduflow_token');

    const defaultHeaders = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': token ? `Bearer ${token}` : ''
    };

    const response = await fetch(`/api${endpoint}`, {
        ...options,
        headers: { ...defaultHeaders, ...options.headers }
    });

    // 2. Sécurité CRITIQUE : Si le token est expiré (401)
    if (response.status === 401) {
        localStorage.removeItem('eduflow_token');
        window.location.href = '/login?session=expired';
    }

    return response;
};