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

@vite('resources/js/login.js')
