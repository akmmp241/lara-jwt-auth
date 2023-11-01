import "./app.js";

let userId;
const message = document.querySelector('.message');
const verify = document.querySelector('.verify');

axios.get('/api/user', {
    headers: {
        Authorization: `Bearer ${cookie.get('userToken')}`
    }
}).then(async res => {
    if (res.data.success) {
        await printResponse(res);
        userId = res.data.user.id;

        if (!res.data.user.emailVerifiedAt) {
            verify.innerHTML = "verify email";
            verify.style.cursor = 'pointer';
            verify.style.color = 'blue';
        } else {
            verify.innerHTML = 'Email verified';
            verify.style.color = 'black';
        }
    }
}).catch(err => {
    alert(err.response.data.message);
});

if (!verify.innerHTML.includes('verified')) {
    verify.addEventListener('click', () => {
        axios.get('/api/user/send-verify-email', {
            headers: {
                Authorization: `Bearer ${cookie.get('userToken')}`
            }
        }).then(res => {
            if (res.data.success) {
                message.innerHTML = "we sent you a verification email, please check your email";
            }
        }).catch(err => {
            if (!err.response.data.success) {
                message.innerHTML = err.response.data.message;
            }
        });
    });
}

const updateForm = document.getElementById('profile-update');

updateForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    axios.put("/api/user", {
        name: formData.get('name'),
        email: formData.get('email'),
        user_id: userId
    }, {
        headers: {
            Authorization: `Bearer ${cookie.get('userToken')}`
        }
    }).then(res => {
        if (res.data.success) {
            setTimeout(() => {
                message.innerHTML = res.data.message;
            }, 1000)
            printResponse(res);
            printErrorMsg(false, null);
        }
    }).catch(err => {
        if (!err.response.data.success) {
            printErrorMsg(true, err.response.data.errors)
        }
    })
});

const printErrorMsg = (option, msg) => {
    const emailErr = document.querySelector('.email_err');
    const nameErr = document.querySelector('.name_err');

    emailErr.innerHTML = '';
    nameErr.innerHTML = '';

    if (option) {
        if (msg.email) {
            emailErr.innerHTML = msg.email[0];
        }

        if (msg.name) {
            nameErr.innerHTML = msg.name[0];
        }
    }
}

const printResponse = (res) => {
    document.querySelector('.greet').innerHTML = res.data.user.name;
    document.querySelector('.email').innerHTML = res.data.user.email;
    document.querySelector('#name').value = res.data.user.name;
    document.querySelector('#email').value = res.data.user.email;
}

document.querySelector('.logout').addEventListener('click', () => {
    axios.delete('/api/logout', {
        headers: {
            Authorization: `Bearer ${cookie.get('userToken')}`
        }
    }).then(res => {
        if (res.data.success) {
            cookie.remove('userToken')
            window.open('/login', '_self');
        }
    }).catch(err => {
        if (!err.response.data.success) {
            document.querySelector('.message').innerHTML = err.response.data.message;
        }
    });
})
