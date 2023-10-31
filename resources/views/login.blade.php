@include('header')
<h1>User Login</h1>

<form id="login-form">
    <label>
        <span class="error email_err"></span> <br>
        <input type="email" name="email" placeholder="email anda">
    </label>
    <br><br>
    <label>
        <span class="error password_err"></span> <br>
        <input type="password" name="password" placeholder="password anda">
    </label>
    <br><br>
    <input type="submit">
</form>

<p class="result"></p>

<script>
    const registerForm = document.getElementById('login-form');

    registerForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        axios.post('/api/login', formData)
            .then(function (response) {
                if (response.status === 200 && response.data.success) {
                    Cookies.set('user_token', response.data.token_type + ' ' + response.data.access_token, {expires: 1});
                    window.open('/profile', '_self')
                }
            })
            .catch(function (error) {
                console.log(error);
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
</script>
