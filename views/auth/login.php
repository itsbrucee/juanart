<?php $pageTitle = 'Login'; ob_start(); ?>
<div class="container auth-page">
    <h1>Login</h1>
    <form method="post" class="form">
        <input type="hidden" name="action" value="login">
        <label>Email <input type="email" name="email" required class="input" autocomplete="email"></label>
        <label>Password <input type="password" name="password" required class="input" autocomplete="current-password"></label>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p><a href="?page=register">Register</a> · <a href="?page=dashboard">Back to home</a></p>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
