<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<div class="nav-header">
    <span class="turniri">Košarkaški turniri</span>
    <a href="/dashboard" class="back-link">← Nazad</a>
</div>

{{-- Glavni kontejner - postavljen na block da bi sve išlo dole --}}
<div style="display: block; width: 90%; max-width: 1000px; margin: 0 auto; padding-top: 30px;">

    @if(isset($pobednik) && $pobednik)
        <div style="background: linear-gradient(135deg, #12848B 0%, #1a1a1a 100%); padding: 30px; border-radius: 12px; text-align: center; margin-bottom: 30px; border: 2px solid var(--cyan); box-shadow: 0 0 15px rgba(0, 255, 255, 0.2);">
            <h2 style="color: white; margin: 0; font-size: 18px; text-transform: uppercase; letter-spacing: 3px;">🏆 Pobednik Turnira 🏆</h2>
            <h1 style="color: var(--cyan); font-size: 42px; margin: 10px 0;">{{ $pobednik->naziv }}</h1>
            <p style="color: #ccc; margin: 0;">Krajnji rezultat: {{ $pobednik->bodovi }} bodova</p>
        </div>
    @endif
    {{-- Ovo dugme vide svi: menadžeri, sudije i navijači --}}
    <div style="display: flex; align-items:center; justify-content:center; margin-bottom: 2%">
        <a href="{{ route('tournaments.leaderboard', $tournament->id) }}"
    class="btn-cyan-action"
    style="background: transparent; border: 2px solid var(--cyan); color: var(--cyan); text-decoration: none;">
        🏆 Pogledaj rang listu
        </a>
    </div>

    {{-- 1. KARTICA TURNIRA (VRH) --}}
    <div class="tournament-details-card" style="width: 100% !important; margin-bottom: 30px; display: block; clear: both;">
        <h1 style="font-size: 32px; margin-bottom: 10px;">{{ $tournament->naziv }}</h1>
        <p class="details-date" style="font-size: 20px;">
            {{ \Carbon\Carbon::parse($tournament->datum_pocetka)->format('d.m.Y.') }} -
            {{ \Carbon\Carbon::parse($tournament->datum_zavrsetka)->format('d.m.Y.') }}
        </p>
        <p class="details-info" style="margin: 5px 0;">{{ $tournament->teams_count }} timova</p>
        <p class="details-info" style="margin: 5px 0;">{{ $tournament->lokacija }}</p>

        <div class="actions-section" style="margin-top: 20px; display: flex; gap: 10px; align-items:center; justify-content:center">

            @if(auth()->user()->role == 'organizer')
                <a href="{{ route('teams.register', $tournament->id) }}" class="btn-cyan-action">Dodaj tim ručno</a>
                <a href="{{ route('tournaments.generate', $tournament->id) }}" class="btn-cyan-action">Generiši raspored</a>

            @elseif(auth()->user()->role == 'referee' || auth()->user()->role == 'sudija')

            @else
                <p class="role-text">Pregledate turnir kao Navijač</p>
            @endif

        </div>
    </div>

    {{-- NASLOV ZA RASPORED --}}
    <h2 class="cyan-title" style="margin: 40px 0 20px 0; font-size: 28px; border-bottom: 2px solid var(--cyan); padding-bottom: 10px;">
        Raspored utakmica
    </h2>

    {{-- 2. LISTA UTAKMICA (ISPOD) --}}
    <div class="schedule-container" style="display: block; width: 100%;">
        @foreach($utakmice as $kolo => $mecevi)
            <div class="round-box" style="margin-bottom: 40px;">
                <h3 class="cyan-title" style="font-size: 22px; margin-bottom: 15px;">Kolo {{ $kolo }}</h3>

                <div style="display: flex; flex-direction: column; gap: 15px;">
                    @foreach($mecevi as $utakmica)
                        <div class="match-box" style="background: #D9D9D9; color: black; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; border-radius: 8px; width: 100%; box-sizing: border-box; margin-bottom: 12px; min-height: 80px;">

                            {{-- LEVA STRANA: Timovi i Rezultat --}}
                            <div style="display: flex; align-items: center; gap: 25px; flex-grow: 1;">
                                <span style="font-weight: bold; font-size: 18px; min-width: 140px; text-align: right;">{{ $utakmica->domaciTim->naziv }}</span>

                                <span style="background: #12848B; color: white; padding: 8px 16px; border-radius: 4px; font-weight: bold; font-family: monospace; font-size: 20px; min-width: 60px; text-align: center; display: inline-block;">
                                    {{ $utakmica->rezultat ?? '0:0' }}
                                </span>

                                <span style="font-weight: bold; font-size: 18px; min-width: 140px; text-align: left;">{{ $utakmica->straniTim->naziv }}</span>

                                <span style="font-size: 13px; color: #666; font-style: italic; margin-left: 10px;">
                                    (Status: {{ $utakmica->status }})
                                </span>
                            </div>

                            @php
                                $isStaff = in_array(auth()->user()->role, ['organizer', 'referee', 'sudija']);
                                $isTimeRight = \Carbon\Carbon::parse($tournament->datum_pocetka)->isPast() || \Carbon\Carbon::parse($tournament->datum_pocetka)->isToday();
                            @endphp

                            @if($isStaff && $isTimeRight && $utakmica->status !== 'otkazana')
                                <div style="display: flex; align-items: center; justify-content: center; height: 100%; padding-bottom:3%">
                                    <a href="{{ route('utakmicas.edit', $utakmica->id) }}" class="btn-cyan-action" style="text-decoration: none; padding: 10px 20px; font-size: 14px; white-space: nowrap; display: flex; align-items: center; justify-content: center; line-height: 1;">
                                        Unesi rezultat
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

</div>
