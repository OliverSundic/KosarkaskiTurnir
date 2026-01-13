<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<div class="nav-header">
    <span class="turniri">Košarkaški turniri</span>
    <a href="/tournament/{{$tournament->id}}" class="back-link">← Nazad</a>
</div>

<div class="registration-container" style="text-align: center;">
    <h2 class="cyan-title" style="margin-top: 40px;">Lista timova</h2>

    {{-- Lista svih timova --}}
    <div style="display: flex; flex-direction: column; align-items: center; gap: 10px; margin-top: 30px;">
        @foreach($tournament->teams as $team)
            <div style="background-color: #D9D9D9; color: black; width: 300px; padding: 15px; font-size: 20px; border-radius: 4px;">
                {{ $team->naziv }}
            </div>
        @endforeach
    </div>

    <hr style="border: 0; border-top: 1px solid #444; margin: 50px 0;">

    <h2 class="cyan-title">Generisani raspored mečeva</h2>

    {{-- ISPIS RASPOREDA PO KOLIMA --}}
    <div class="schedule-output" style="margin-top: 30px; display: flex; flex-direction: column; align-items: center; gap: 20px;">
        @foreach($schedule as $roundIndex => $matches)
            <div class="round-box" style="width: 100%; max-width: 500px;">
                <h3 style="color: var(--cyan); margin-bottom: 10px;">Kolo {{ $roundIndex + 1 }}</h3>

                @foreach($matches as $match)
                    <div style="background-color: #D9D9D9; color: black; padding: 15px; margin: 5px 0; border-radius: 4px; display: flex; justify-content: center; align-items: center; font-weight: bold;">
                        {{-- Pronalazimo nazive timova preko ID-jeva koji su u $schedule --}}
                        @php
                            $homeTeam = $tournament->teams->firstWhere('id', $match['home']);
                            $awayTeam = $tournament->teams->firstWhere('id', $match['away']);
                        @endphp

                        {{ $homeTeam->naziv }}
                        <span style="color: #12848B; margin: 0 15px;"> VS </span>
                        {{ $awayTeam->naziv }}
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    {{-- Dugme za snimanje u bazu (opciono) --}}
    <form action="{{ route('tournaments.storeSchedule', $tournament->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn-cyan-flat" style="margin-top: 50px;">
            Potvrdi i sačuvaj raspored
        </button>
    </form>
</div>
