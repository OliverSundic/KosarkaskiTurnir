<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<div class="nav-header">
    <span class="turniri">Košarkaški turniri</span>
    <a href="/dashboard" class="back-link">← Nazad</a>
</div>

<div class="details-outer-container">
    <div class="tournament-details-card">
        <h1>{{ $tournament->naziv }}</h1>

        <p class="details-date">
            {{ \Carbon\Carbon::parse($tournament->datum_pocetka)->format('d.m.Y.') }} -
            {{ \Carbon\Carbon::parse($tournament->datum_zavrsetka)->format('d.m.Y.') }}
        </p>

        <p class="details-info">{{ $tournament->teams_count }} timova</p>
        <p class="details-info">{{ $tournament->lokacija }}</p>
        <p class="details-info">Nagradni fond: 15.000€</p>
        <p class="details-deadline">Prijave traju do 16.01.2026.</p>

        <div class="actions-section">
            {{-- Organizator i navijači/menadžeri sada vide dugme --}}
            @if(auth()->user()->role == 'organizer')
                 <p class="role-text">Prijavljeni ste kao Organizator</p>
                 <a href="{{ route('teams.register', $tournament->id) }}" class="btn-cyan-action">
                    Dodaj tim ručno
                </a>
            @elseif(auth()->user()->role == 'referee')
                 <p class="role-text">Prijavljeni ste kao Sudija</p>
            @else
                <a href="{{ route('teams.register', $tournament->id) }}" class="btn-cyan-action">
                    Prijavi svoj tim!
                </a>
            @endif
        </div>
    </div>
</div>
