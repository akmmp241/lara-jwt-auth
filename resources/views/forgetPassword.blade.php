<h1>Forget Password</h1>

<form id="forget-password" method="post" action="">
    <label>
        <span class="error email_err"></span> <br>
        <input type="email" name="email" placeholder="email anda" required>
    </label>
    <br><br>
    <input type="submit">
</form>

<p class="message"></p>

@vite('resources/js/forgetPassword.js')
