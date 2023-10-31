@include('header')

<h1>Hi, <span class="greet"></span></h1>

<button class="logout">Logout</button>
<p class="message"></p>

<div class="email_verify">
    <p><b>Email:- <span class="email"></span> &nbsp; <span class="verify"></span></b></p>
</div>

<form class="update-form">
    <label for="name">
        <input type="text" name="name" id="name" placeholder="Enter name" required>
    </label>
    <br><br>
    <label for="email">
        <input type="email" name="email" id="email" placeholder="Enter email" required>
    </label>
    <br><br>
    <input type="submit" value="Update profile">
</form>

<script>

    axios.get('/api/user', {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('user_token')}`
        }
    }).then(res => {
        console.log(res);
        if (res.data.success) {
            document.querySelector('.greet').innerHTML = res.data.user.name;
            document.querySelector('.email').innerHTML = res.data.user.email;
            document.querySelector('#name').value = res.data.user.name;
            document.querySelector('#email').value = res.data.user.email;

            if (!res.data.user.emailVerfiedAt) {
                document.querySelector('.verify').innerHTML = '<a href="#">Verify email</a>';
            } else {
                document.querySelector('.verify').innerHTML = 'Email verified';
            }

            }
    }).catch(err => {
        alert(err.response.data.message);
    });

    document.querySelector('.logout').addEventListener('click', () => {
        axios.get('/api/logout', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('user_token')}`
            }
        }).then(res => {
            if (res.data.success) {
                localStorage.removeItem('user_token');
                window.open('/login', '_self');
            }
        }).catch(err => {
            if (!err.response.data.success) {
                document.querySelector('.message').innerHTML = err.response.data.message;
            }
        });
    })
</script>
