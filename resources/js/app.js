import './bootstrap';
import { initRegister, initLogin, initForgotPassword } from './auth-handler';
import { checkAuth } from './guard'; 
import { initDashboard } from './dashboard';

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

    if (document.getElementById('dashboard-app')) {
        const isAuthorized = checkAuth();
        
        if (isAuthorized) {
            initDashboard();
        }
    }
});