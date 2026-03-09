<?php $pageTitle = 'Register'; ob_start(); ?>
<div class="container auth-page">
    <h1>Register</h1>
    <form method="post" class="form">
        <input type="hidden" name="action" value="register">
        <label>Name <input type="text" name="name" required class="input" autocomplete="name"></label>
        <label>Email <input type="email" name="email" required class="input" autocomplete="email"></label>
        <label>Phone <input type="tel" name="phone" class="input" autocomplete="tel"></label>
        <label>Password <input type="password" name="password" required minlength="6" class="input" autocomplete="new-password"></label>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <p><a href="?page=login">Login</a> · <a href="?page=dashboard">Back to home</a></p>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
