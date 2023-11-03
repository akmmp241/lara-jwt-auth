import './app.js';

const resetPassword = document.querySelector('#reset-password');

resetPassword.addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(resetPassword);

    axios.patch('/api/reset-password', {
        id: formData.get('id'),
        password: formData.get('password'),
        password_confirmation: formData.get('password_confirmation')
    })
        .then(res => {
            resetPassword.reset();
            document.querySelector('.message').innerHTML = res.data.message;
            setTimeout((e) => {
                window.open('/login', '_self')
            }, 3000);
        })
        .catch(err => {
            console.log(err)
        });
});

const printErrorMsg = (msg) => {
    const passwordErr = document.querySelector('.password_err');
    const passwordConfirmErr = document.querySelector('.password_confirmation_err');

    passwordErr.innerHTML = '';
    passwordConfirmErr.innerHTML = '';

    if (msg.password) {
        passwordErr.innerHTML = msg.password[0];
    }

    if (msg.password_confirmation) {
        passwordConfirmErr.innerHTML = msg.password_confirmation[0];
    }
}
