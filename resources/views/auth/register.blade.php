<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="nav-header">
    <span class="turniri">Košarkaški turniri</span>
    <a href="/" style="text-decoration:none; color:black"><span class="razmak">←</span>Nazad</a>
</div>
<div class="auth-container">
    <div class="cyan-title">Registracija</div>
    <form action="/register" method="POST">
        @csrf
        <div class="form-group"><label>Ime</label><input class="input" type="text" name="first_name"></div>
        <div class="form-group"><label>Prezime</label><input class="input" type="text" name="last_name"></div>
        <div class="form-group">
            <label>Pozicija</label>
            <select class="input" name="role">
                <option value="fan">Navijač</option>
                <option value="organizer">Organizator</option>
                <option value="referee">Sudija</option>
            </select>
        </div>
        <div class="form-group"><label>Email</label><input class="input" type="email" name="email"></div>
        <div class="form-group"><label>Lozinka</label><input class="input" type="password" name="password"></div>
        <button class="btn-submit">Registruj se</button>
    </form>
    @if ($errors->any())
        <div style="color: #FF4444; margin-bottom: 20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
