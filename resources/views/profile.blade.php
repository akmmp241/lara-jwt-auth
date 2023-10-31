@include('header')

<h1>Hi, <span class="greet"></span></h1>

<button class="logout">Logout</button>

<div class="email_verify">
    <p><b>Email:- <span class="email"></span> &nbsp; <span class="verify"></span></b></p>
</div>

<form id="profile-update">
    <label for="name">
        <span class="error name_err"></span> <br>
        <input type="text" name="name" id="name" placeholder="Enter name" required>
    </label>
    <br><br>
    <label for="email">
        <span class="error email_err"></span> <br>
        <input type="email" name="email" id="email" placeholder="Enter email" required>
    </label>
    <br><br>
    <input type="submit" value="Update profile">
</form>

<p class="message"></p>


<script>

    let userId;

    axios.get('/api/user', {
        headers: {
            Authorization: `Bearer ${Cookies.get('user_token')}`
        }
    }).then(res => {
        if (res.data.success) {
            document.querySelector('.greet').innerHTML = res.data.user.name;
            document.querySelector('.email').innerHTML = res.data.user.email;
            document.querySelector('#name').value = res.data.user.name;
            document.querySelector('#email').value = res.data.user.email;
            userId = res.data.user.id;

            if (!res.data.user.emailVerfiedAt) {
                document.querySelector('.verify').innerHTML = '<a href="#">Verify email</a>';
            } else {
                document.querySelector('.verify').innerHTML = 'Email verified';
            }
        }
    }).catch(err => {
        alert(err.response.data.message);
    });

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
                Authorization: `Bearer ${Cookies.get('user_token')}`
            }
        }).then(res => {
            if (res.data.success) {
                setTimeout(() => {
                    document.querySelector('.message').innerHTML = res.data.message;
                }, 1000)
                document.querySelector('.greet').innerHTML = res.data.user.name;
                document.querySelector('.email').innerHTML = res.data.user.email;
                document.querySelector('#name').value = res.data.user.name;
                document.querySelector('#email').value = res.data.user.email;
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

    document.querySelector('.logout').addEventListener('click', () => {
        axios.delete('/api/logout', {
            headers: {
                Authorization: `Bearer ${Cookies.get('user_token')}`
            }
        }).then(res => {
            if (res.data.success) {
                Cookies.remove('user_token')
                window.open('/login', '_self');
            }
        }).catch(err => {
            if (!err.response.data.success) {
                document.querySelector('.message').innerHTML = err.response.data.message;
            }
        });
    })
</script>
