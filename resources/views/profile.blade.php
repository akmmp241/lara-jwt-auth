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

@vite('resources/js/profile.js')
