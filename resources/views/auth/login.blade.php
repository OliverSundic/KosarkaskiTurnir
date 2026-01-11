<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="nav-header">
    <span class="turniri">Košarkaški turniri</span>
    <a href="/" style="text-decoration:none; color:black"><span class="razmak">←</span>Nazad</a>
</div>
<div class="auth-container">
    <div class="cyan-title" id="login">Login</div>
    <form action="/login" method="POST">
        @csrf
        <div class="form-group"><label>Email</label><input class="input" type="email" name="email"></div>
        <div class="form-group"><label>Lozinka</label><input class="input" type="password" name="password"></div>
        <button class="btn-submit" id="razmak-gore">Login</button>
    </form>
    <div class="footer-text">Nemate nalog? <a href="/register">Registrujte se</a></div>
</div>
