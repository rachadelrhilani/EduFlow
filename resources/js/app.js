import './bootstrap';
import { initRegister } from './auth-handler';

// On initialise seulement si on est sur la bonne page
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('registerForm')) {
        initRegister();
    }
});