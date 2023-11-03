import './app.js';

const forgetPassword = document.querySelector('#forget-password');

forgetPassword.addEventListener('submit', (e) => {
    e.preventDefault()

    const formData = new FormData(forgetPassword);

    axios.post('/api/forget-password', formData).then(res => {
        forgetPassword.reset();
        document.querySelector('.message').innerHTML = res.data.message;
    }).catch(err => {
        console.log(err);
    })
});
