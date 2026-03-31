import './bootstrap';
import { initRegister,initLogin,initForgotPassword } from './auth-handler';

// On initialise seulement si on est sur la bonne page
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('registerForm')) {
        initRegister();
    }
    if (document.getElementById('loginForm')) {
        initLogin();
    }
    if (document.getElementById('forgotPasswordForm')) {
        initForgotPassword();
    }
});