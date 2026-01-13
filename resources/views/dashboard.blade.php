<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<div class="nav-header">
    <span class="turniri">Košarkaški turniri</span>
    <form action="{{ route('logout') }}" method="POST" style="margin:0">
        @csrf
        <button type="submit" class="logout-link">Odjavi se</button>
    </form>
</div>

@if(session('success'))
    <div style="background-color: var(--cyan); color: black; padding: 15px; text-align: center; font-weight: bold; border-radius: 5px; margin: 20px 10%; box-shadow: 0px 4px 10px rgba(0,0,0,0.3);">
        {{ session('success') }}
    </div>
@endif
<div class="dashboard-container">
    @if(auth()->user()->role == 'organizer')
    <div style="text-align: right; margin-bottom: 20px;">
            <a href="{{ route('tournaments.create') }}" class="btn-cyan-flat">
                + Kreiraj novi turnir
            </a>
        </div>
    @endif
    <h2 class="section-title">Aktivni turniri</h2>
    <div class="tournament-grid">
        @foreach($aktivni as $turnir)
            <div class="tournament-card">
                <h3>{{ $turnir->naziv }}</h3>
                <p class="date">
                    {{ \Carbon\Carbon::parse($turnir->datum_pocetka)->format('d.1.Y.') }} -
                    {{ \Carbon\Carbon::parse($turnir->datum_zavrsetka)->format('d.1.Y.') }}
                </p>
                <p class="info">{{ $turnir->teams_count }} timova</p>
                <a href="{{ route('tournaments.show', $turnir->id) }}" class="card-link">Pogledaj više</a>
            </div>
        @endforeach
    </div>

    <h2 class="section-title">Završeni turniri</h2>
    <div class="tournament-grid">
        @foreach($zavrseni as $turnir)
            <div class="tournament-card">
                <h3>{{ $turnir->naziv }}</h3>
                <p class="date">
                    {{ \Carbon\Carbon::parse($turnir->datum_pocetka)->format('d.1.Y.') }} -
                    {{ \Carbon\Carbon::parse($turnir->datum_zavrsetka)->format('d.1.Y.') }}
                </p>
                <p class="info">Pobednik: Barcelona</p> <a href="{{ route('tournaments.show', $turnir->id) }}" class="card-link">Pogledaj više</a>
            </div>
        @endforeach
    </div>
</div>
