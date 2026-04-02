import './bootstrap';
import { initRegister, initLogin, initForgotPassword } from './auth-handler';
import { checkAuth } from './guard'; 
import { initCourses } from './courses';
import { initDashboard } from './dashboard';
import { initFavorites } from './favorites';
import { initCourseCreate } from './course-manager';

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
    if (document.getElementById("courses-grid")){
       initCourses();
    }
    if (document.getElementById('favorites-grid')) {
        initFavorites();
    }
    if(document.getElementById("create-course-form")){
        initCourseCreate();
    }
});