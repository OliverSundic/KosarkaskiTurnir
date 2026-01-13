<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<div class="nav-header">
    <span class="turniri">Rang lista: {{ $tournament->naziv }}</span>
    <a href="{{ route('tournaments.show', $tournament->id) }}" class="back-link">← Nazad na turnir</a>
</div>

<div style="max-width: 900px; margin: 50px auto; padding: 20px;">
    <h1 class="cyan-title" style="text-align: center; margin-bottom: 40px; font-size: 36px;">
        Trenutni poredak
    </h1>

    <table style="width: 100%; border-collapse: separate; border-spacing: 0 10px; color: white;">
        <thead>
            <tr style="background-color: var(--cyan); color: black;">
                <th style="padding: 15px; border-radius: 5px 0 0 5px; text-align: center; width: 80px;">Pozicija</th>
                <th style="padding: 15px; text-align: left;">Naziv tima</th>
                <th style="padding: 15px; border-radius: 0 5px 5px 0; text-align: center; width: 120px;">Bodovi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($timovi as $index => $tim)
            <tr style="background-color: #2a2a2a;">
                <td style="padding: 20px; text-align: center; font-weight: bold; font-size: 20px;">
                    {{ $loop->iteration }}.
                </td>
                <td style="padding: 20px; font-size: 18px;">
                    {{ $tim->naziv }}
                </td>
                <td style="padding: 20px; text-align: center; font-weight: bold; font-size: 22px; color: var(--cyan);">
                    {{ $tim->bodovi }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($timovi->isEmpty())
        <p style="text-align: center; color: #888; margin-top: 30px;">Još uvek nema prijavljenih timova za ovaj turnir.</p>
    @endif
</div>
