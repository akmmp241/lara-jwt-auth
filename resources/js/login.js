import './app.js';

const registerForm = document.getElementById('login-form');

registerForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    axios.post('/api/login', formData)
        .then(function (response) {
            if (response.data.success) {
                cookie.set('userToken', response.data.token_type + ' ' + response.data.access_token, {
                    expires: 1,
                    SameSite: 'none',
                    Secure: true
                });
                window.open('/profile', '_self')
            }
        })
        .catch(function (error) {
            if (!error.response.data.success && error.response.status === 401) {
                document.querySelector('.result').innerHTML = error.response.data.message;
            } else {
                printErrorMsg(error.response.data.errors);
            }
        });
});

const printErrorMsg = (msg) => {
    const emailErr = document.querySelector('.email_err');
    const passwordErr = document.querySelector('.password_err');

    emailErr.innerHTML = '';
    passwordErr.innerHTML = '';

    if (msg.email) {
        emailErr.innerHTML = msg.email[0];
    }

    if (msg.password) {
        passwordErr.innerHTML = msg.password[0];
    }
}
