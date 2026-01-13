<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<div class="nav-header">
    <span class="turniri">Košarkaški turniri</span>
    {{-- url()->previous() te vraća na stranicu turnira ako si odatle došao --}}
    <a href="{{ url()->previous() }}" class="back-link">← Nazad</a>
</div>

<div class="registration-container" style="background-color: #1a1a1a; padding: 40px; border-radius: 10px; color: white; max-width: 700px; margin: 50px auto; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
    <h2 class="cyan-title" style="text-align: center; margin-bottom: 30px;">Unos rezultata utakmice</h2>

    <form action="{{ route('utakmicas.update_score', $utakmica->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div style="display: flex; justify-content: space-around; align-items: center; background: #2a2a2a; padding: 30px; border-radius: 8px;">
            {{-- Domaći tim --}}
            <div style="text-align: center; flex: 1;">
                <label style="display: block; font-size: 20px; font-weight: bold; margin-bottom: 15px; color: var(--cyan);">
                    {{ $utakmica->domaciTim->naziv }}
                </label>
                <input type="number" name="home_points"
                       value="{{ explode(':', $utakmica->rezultat)[0] ?? 0 }}"
                       style="width: 100px;  border-radius: 5px; font-size: 24px; text-align: center; border: none; background: #eee; padding-left:6%">
            </div>

            <div style="font-size: 40px; font-weight: bold; color: var(--cyan); margin: 0 20px;">:</div>

            {{-- Gosti --}}
            <div style="text-align: center; flex: 1;">
                <label style="display: block; font-size: 20px; font-weight: bold; margin-bottom: 15px; color: var(--cyan);">
                    {{ $utakmica->straniTim->naziv }}
                </label>
                <input type="number" name="away_points"
                       value="{{ explode(':', $utakmica->rezultat)[1] ?? 0 }}"
                       style="width: 100px; border-radius: 5px; font-size: 24px; text-align: center; border: none; background: #eee; padding-left:7%">
            </div>
        </div>

        {{-- Akciona dugmad --}}
        <div style="margin-top: 40px; display: flex; flex-direction: column; gap: 15px;">

            {{-- Glavni red sa dva velika simetrična dugmeta --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <button type="submit" name="action" value="save"
                        class="btn-cyan-flat"
                        style="height: 60px; font-size: 16px; font-weight: bold; cursor: pointer; border-radius: 5px; border: 2px solid var(--cyan); background: transparent; color: var(--cyan); transition: 0.3s;">
                    Sačuvaj trenutno
                </button>

                <button type="submit" name="action" value="finish"
                        class="btn-cyan-flat"
                        style="height: 60px; font-size: 16px; font-weight: bold; cursor: pointer; border-radius: 5px; border: none; background: var(--cyan); color: black; transition: 0.3s;">
                    Završi i dodeli bodove
                </button>
            </div>

            {{-- Dugme za otkazivanje - diskretnije ali jasno --}}
            <button type="submit" name="action" value="cancel"
                    style="height: 45px; background: rgba(255, 68, 68, 0.1); border: 1px solid #ff4444; color: #ff4444; border-radius: 5px; cursor: pointer; font-weight: bold; transition: 0.3s;"
                    onmouseover="this.style.background='#ff4444'; this.style.color='white';"
                    onmouseout="this.style.background='rgba(255, 68, 68, 0.1)'; this.style.color='#ff4444';">
                Otkaži utakmicu
            </button>
        </div>

        <div style="text-align: center; margin-top: 25px;">
            <a href="{{ route('tournaments.show', $utakmica->tournament_id) }}"
            style="color: #888; text-decoration: none; font-size: 14px; border-bottom: 1px solid transparent; transition: 0.3s;"
            onmouseover="this.style.color='white'; this.style.borderBottom='1px solid white';"
            onmouseout="this.style.color='#888'; this.style.borderBottom='1px solid transparent';">
            ← Odustani i vrati se na turnir
            </a>
        </div>
    </form>
</div>
