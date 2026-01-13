<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="nav-header">
        <span class="turniri">Košarkaški turniri</span>
        <a href="/dashboard" class="back-link">← Nazad</a>
    </div>

<div class="registration-container">

    <h2 class="cyan-title-large">Kreiranje turnira</h2>

    <form action="{{ route('tournaments.store') }}" method="POST">
        @csrf
        <div class="form-group-row">
            <label class="cyan-label">Naziv turnira</label>
            <input type="text" name="naziv" class="rounded-input" required>
        </div>
        <div class="form-group-row">
            <label class="cyan-label">Lokacija</label>
            <input type="text" name="lokacija" class="rounded-input" required>
        </div>
        <div class="form-group-row">
            <label class="cyan-label">Datum početka</label>
            <input type="date" name="datum_pocetka" class="rounded-input" required>
        </div>
        <div class="form-group-row">
            <label class="cyan-label">Datum završetka</label>
            <input type="date" name="datum_zavrsetka" class="rounded-input" required>
        </div>

        <div style="text-align: center; margin-top: 50px;">
            <button type="submit" class="btn-cyan-flat" style="padding: 15px 60px;">Kreiraj turnir</button>
        </div>
    </form>
</div>
