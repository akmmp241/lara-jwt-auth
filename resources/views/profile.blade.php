@include('header')

<h1>Profile</h1>

<button class="logout">Logout</button>
<p class="message"></p>

<script>
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
