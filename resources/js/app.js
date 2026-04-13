import './bootstrap';
import { initRegister, initLogin, initForgotPassword } from './auth-handler';
import { checkAuth } from './guard'; 
import { initCourses } from './courses';
import { initDashboard } from './dashboard';
import { initFavorites } from './favorites';
import { initCourseCreate, initCourseEdit } from './course-manager';
import { initEnrollment } from './enrollment';
import { showToast } from './toast';
import { initGroups } from './groups';

window.showToast = showToast;

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
       initEnrollment();
    }
    if (document.getElementById('favorites-grid')) {
        initFavorites();
    }
    if(document.getElementById("create-course-form")){
        initCourseCreate();
    }
    if (document.getElementById('edit-course-form')) {
        initCourseEdit();
    }
    if (document.getElementById('groups-container')) {
        initGroups();
    }
});