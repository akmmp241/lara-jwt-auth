<h1>User Registration</h1>

<form id="register-form">
    <label>
        <span class="error name_err"></span> <br>
        <input type="text" name="name" placeholder="Nama anda">
    </label>
    <br><br>
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
    <label>
        <span class="error password_confirmation_err"></span> <br>
        <input type="password" name="password_confirmation" placeholder="konfirmasi password">
    </label>
    <br><br>
    <input type="submit">
</form>

<p class="result"></p>

<script>
    const registerForm = document.getElementById('register-form');

    registerForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        axios.post('/api/register', formData)
            .then(function (response) {
                console.log(response)
                if (response.data.message) {
                    registerForm.reset();
                    document.querySelector('.result').innerHTML = response.data.message;
                }
            })
            .catch(function (error) {
                console.log(error)
                if (error.response.status === 500) {
                    document.querySelector('.result').innerHTML = error.response.data.message;
                }
                // printErrorMsg(error.response.data.errors);
            });
    });

    const printErrorMsg = (msg) => {
        const nameErr = document.querySelector('.name_err');
        const emailErr = document.querySelector('.email_err');
        const passwordErr = document.querySelector('.password_err');
        const passwordConfirmationErr = document.querySelector('.password_confirmation_err');

        nameErr.innerHTML = '';
        emailErr.innerHTML = '';
        passwordErr.innerHTML = '';
        passwordConfirmationErr.innerHTML = '';

        if (msg.name) {
            nameErr.innerHTML = msg.name[0];
        }

        if (msg.email) {
            emailErr.innerHTML = msg.email[0];
        }

        if (msg.password) {
            if (msg.password.length > 1) {
                passwordErr.innerHTML = msg.password[0];
                passwordConfirmationErr.innerHTML = msg.password[1];
            } else {
                if (msg.password[0].includes('confirmation')) {
                    passwordConfirmationErr.innerHTML = msg.password[0];
                } else {
                    passwordErr.innerHTML = msg.password[0];
                }
            }
        }
    }
</script>
