import './bootstrap';
import { initRegister,initLogin } from './auth-handler';

// On initialise seulement si on est sur la bonne page
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('registerForm')) {
        initRegister();
    }
    if (document.getElementById('loginForm')) {
        initLogin();
    }
});