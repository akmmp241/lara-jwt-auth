
<h1>Reset Password</h1>

<form id="reset-password">
    <input type="hidden" name="id" value="{{ $user->id }}">
    <label for="password">
        <span class="error password_err"></span> <br>
        <input type="password" name="password" id="password" placeholder="New password">
    </label>
    <br><br>
    <label for="password_confirmation">
        <span class="error password_confirmation_err"></span> <br>
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm password">
    </label>
    <br><br>
    <input type="submit" value="submit">
</form>

<p class="message"></p>

@vite('resources/js/resetPassword.js')
