import './app.js';

let token = cookie.get('userToken');

if (window.location.pathname === '/login' || window.location.pathname === '/register') {
    if (token) {
        window.open('/profile', '_self');
    }
} else {
    if (!token) {
        window.open('/login', '_self');
    }
}
