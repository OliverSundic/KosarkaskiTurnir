<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="nav-header">
    <span class="turniri">Košarkaški turniri</span>
    <a href="/dashboard" class="back-link">← Nazad</a>
</div>
<div class="registration-container">
    {{-- LOGIKA ZA PROVERU DATUMA --}}
    @if(\Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($tournament->datum_pocetka)))
        <div class="error-box">
            <h2 class="cyan-title">Prijave za ovaj turnir su zatvorene.</h2>
            <a href="{{ route('tournaments.show', $tournament->id) }}" class="back-link">← Nazad na detalje</a>
        </div>
    @else


        <h2 class="cyan-title-large">{{ $tournament->naziv }}</h2>

        <form action="{{ route('teams.store', $tournament->id) }}" method="POST" id="team-form">
            @csrf
            <div class="form-group-row">
                <label class="cyan-label">Naziv tima</label>
                <input type="text" name="naziv" class="rounded-input" required>
            </div>
            <div class="form-group-row">
                <label class="cyan-label">Grad</label>
                <input type="text" name="grad" class="rounded-input" required>
            </div>

            <div class="table-header-flex">
                <span class="cyan-title">Lista igrača</span>
                <span class="player-count" id="count-text">0/12 igrača</span>
            </div>

            <table id="players-table" class="player-entry-table">
                <thead>
                    <tr>
                        <th>Ime</th>
                        <th>Prezime</th>
                        <th>Broj dresa</th>
                        <th>Pozicija</th>
                        <th>Akcija</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- JS će ovde ubaciti redove --}}
                </tbody>
            </table>

            <div class="form-buttons-flex">
                <button type="button" class="btn-cyan-flat" onclick="addRow()">Dodaj igrača</button>
                <button type="submit" class="btn-cyan-flat" id="submit-btn" disabled>Pošalji prijavu</button>
            </div>
        </form>
    @endif
</div>

<script>
    let playerCount = 0;

    // AUTOMATSKO DODAVANJE PRVOG REDA ČIM SE STRANICA UČITA
    window.onload = function() {
        addRow();
    };

    function addRow() {
        if (playerCount >= 12) return;

        const tbody = document.querySelector('#players-table tbody');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" name="players[${playerCount}][ime]" required></td>
            <td><input type="text" name="players[${playerCount}][prezime]" required></td>
            <td><input type="number" name="players[${playerCount}][broj_dresa]" required></td>
            <td>
                <select name="players[${playerCount}][pozicija]">
                    <option value="Plejmejker">Plejmejker</option>
                    <option value="Bek">Bek</option>
                    <option value="Krilo">Krilo</option>
                    <option value="Krilni centar">Krilni centar</option>
                    <option value="Centar">Centar</option>
                </select>
            </td>
            <td><button type="button" onclick="removeRow(this)" class="btn-delete-row">Obriši</button></td>
        `;
        tbody.appendChild(row);
        playerCount++;
        updateUI();
    }

    function removeRow(btn) {
        if (playerCount > 1) { // Ne dozvoljavamo brisanje ako je ostao samo jedan red
            btn.closest('tr').remove();
            playerCount--;
            updateUI();
        }
    }

    function updateUI() {
        document.getElementById('count-text').innerText = `${playerCount}/12 igrača`;
        document.getElementById('submit-btn').disabled = (playerCount !== 12);
        // Vizuelni feedback za dugme
        document.getElementById('submit-btn').style.opacity = (playerCount === 12) ? "1" : "0.5";
    }
</script>
