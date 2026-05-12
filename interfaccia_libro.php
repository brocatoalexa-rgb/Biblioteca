<?php
// interfaccia_libro.php — Dettaglio libro + recensioni
session_start();
$idLibro = $_GET['id'] ?? '';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettaglio Libro - Libreria Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Lato',sans-serif;background-size:cover;min-height:100vh;padding:20px}
        .container{max-width:1200px;margin:0 auto}
        .header{background:rgba(250,246,241,.95);border-radius:16px;padding:20px 30px;margin-bottom:30px;box-shadow:0 10px 40px rgba(0,0,0,.3);border:1px solid #e8dcc8;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:20px;position:relative}
        .header::before{content:'';position:absolute;top:0;left:0;right:0;height:6px;background:linear-gradient(90deg,#8b5e3c,#a67c52,#c49a6c,#8b5e3c);border-radius:16px 16px 0 0}
        .header a{color:#8b5e3c;text-decoration:none;font-weight:600;font-size:16px;transition:color .3s}
        .header a:hover{color:#5d3a1a}
        .btn-logout{padding:10px 20px;background:linear-gradient(135deg,#8b5e3c 0%,#5d3a1a 100%);color:#faf6f1;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;text-decoration:none;transition:all .3s;box-shadow:0 4px 15px rgba(139,94,60,.3)}
        .btn-logout:hover{transform:translateY(-2px)}

        .book-detail{background:rgba(250,246,241,.95);border-radius:16px;padding:40px;margin-bottom:30px;box-shadow:0 10px 40px rgba(0,0,0,.3);border:1px solid #e8dcc8;display:grid;grid-template-columns:280px 1fr;gap:40px;position:relative}
        .book-detail::before{content:'';position:absolute;top:0;left:0;right:0;height:6px;background:linear-gradient(90deg,#8b5e3c,#a67c52,#c49a6c,#8b5e3c);border-radius:16px 16px 0 0}
        .book-cover-large{width:100%;max-width:280px;border-radius:12px;box-shadow:0 8px 25px rgba(0,0,0,.3);transition:transform .3s}
        .book-cover-large:hover{transform:scale(1.02)}
        .cover-placeholder{width:100%;max-width:280px;height:400px;background:linear-gradient(135deg,#d4c4a8,#e8dcc8);border-radius:12px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:5rem;color:#8b5e3c;opacity:.5}
        .book-info-detail{display:flex;flex-direction:column;gap:20px}
        .book-title-detail{font-family:'Playfair Display',serif;color:#2c1810;font-size:36px;font-weight:700;line-height:1.2}
        .book-author-detail{color:#8b7355;font-size:18px;font-style:italic}
        .book-meta{display:flex;flex-wrap:wrap;gap:15px}
        .book-meta span{display:inline-block;padding:6px 14px;background:linear-gradient(135deg,#8b5e3c,#a67c52);color:#faf6f1;border-radius:20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px}
        .stars{color:#f39c12;font-size:24px;letter-spacing:2px}
        .book-description-detail{color:#5d3a1a;font-size:16px;line-height:1.8}
        .book-price-detail{font-family:'Playfair Display',serif;color:#8b5e3c;font-size:32px;font-weight:700}

        .reviews-section{background:rgba(250,246,241,.95);border-radius:16px;padding:40px;box-shadow:0 10px 40px rgba(0,0,0,.3);border:1px solid #e8dcc8;position:relative}
        .reviews-section::before{content:'';position:absolute;top:0;left:0;right:0;height:6px;background:linear-gradient(90deg,#8b5e3c,#a67c52,#c49a6c,#8b5e3c);border-radius:16px 16px 0 0}
        .reviews-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;flex-wrap:wrap;gap:15px}
        .reviews-header h2{font-family:'Playfair Display',serif;color:#2c1810;font-size:28px;font-weight:700}
        .average-rating{display:flex;align-items:center;gap:10px}
        .average-rating .stars{font-size:28px}
        .average-rating span{color:#5d3a1a;font-size:16px;font-weight:600}

        .review-form{background:#fffdf9;border-radius:12px;padding:25px;margin-bottom:30px;border:1px solid #e8dcc8}
        .review-form h3{font-family:'Playfair Display',serif;color:#2c1810;font-size:20px;margin-bottom:20px}
        .form-group{margin-bottom:20px}
        .form-group label{display:block;color:#5d3a1a;font-size:13px;font-weight:600;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px}
        .form-group textarea{width:100%;padding:12px 16px;border:2px solid #d4c4a8;border-radius:10px;font-size:14px;font-family:'Lato',sans-serif;transition:all .3s;background:#fff;color:#2c1810;resize:vertical;min-height:100px}
        .form-group textarea:focus{outline:none;border-color:#8b5e3c;box-shadow:0 0 0 4px rgba(139,94,60,.12)}

        .rating-select{display:flex;gap:5px;direction:rtl;width:fit-content}
        .rating-select input[type="radio"]{display:none}
        .rating-select label{font-size:32px;color:#d4c4a8;cursor:pointer;transition:color .2s}
        .rating-select input[type="radio"]:checked ~ label,
        .rating-select label:hover,
        .rating-select label:hover ~ label{color:#f39c12}

        .btn-submit-review{padding:14px 30px;background:linear-gradient(135deg,#8b5e3c 0%,#5d3a1a 100%);color:#faf6f1;border:none;border-radius:10px;font-size:15px;font-weight:600;cursor:pointer;transition:all .3s;box-shadow:0 4px 15px rgba(139,94,60,.3)}
        .btn-submit-review:hover{transform:translateY(-2px)}
        .btn-submit-review:disabled{opacity:.65;cursor:not-allowed;transform:none}

        .review-card{background:#fffdf9;border-radius:12px;padding:25px;margin-bottom:20px;border:1px solid #e8dcc8}
        .review-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:15px}
        .review-author{font-weight:600;color:#2c1810;font-size:16px}
        .review-date{color:#8b7355;font-size:13px;margin-top:4px}
        .review-stars{color:#f39c12;font-size:18px}
        .review-comment{color:#5d3a1a;font-size:14px;line-height:1.7}
        .no-reviews{text-align:center;padding:40px;color:#8b7355;font-style:italic}

        .msg{padding:14px 16px;border-radius:10px;margin-bottom:20px;font-size:14px;display:none;align-items:center;gap:10px;animation:slideIn .3s ease}
        .msg.success{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
        .msg.error{background:#fff3f3;border:1px solid #ffd4d4;color:#8b2929}
        .msg.visible{display:flex}
        @keyframes slideIn{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:translateY(0)}}

        .loading-box{text-align:center;padding:60px;color:#8b7355}
        .spinner{width:40px;height:40px;border:4px solid #d4c4a8;border-top-color:#8b5e3c;border-radius:50%;animation:spin .7s linear infinite;margin:0 auto 15px}
        @keyframes spin{to{transform:rotate(360deg)}}

        @media(max-width:768px){.book-detail{grid-template-columns:1fr;gap:30px;padding:25px}.book-cover-large{max-width:200px;margin:0 auto;display:block}.book-title-detail{font-size:28px}.reviews-section{padding:25px}}
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <a href="libri.php">← Torna al catalogo</a>
        <button class="btn-logout" onclick="logout()">Logout</button>
    </div>

    <!-- dettaglio libro (riempito da XHR) -->
    <div id="dettaglioLibro">
        <div class="loading-box"><div class="spinner"></div><p>Caricamento libro...</p></div>
    </div>

    <!-- recensioni (riempito da XHR) -->
    <div class="reviews-section" id="sezioneRecensioni" style="display:none">
        <div class="reviews-header">
            <h2>Recensioni</h2>
            <div class="average-rating">
                <div class="stars" id="mediaStelle">☆☆☆☆☆</div>
                <span id="mediaVotoTesto">0/5</span>
            </div>
        </div>

        <div class="msg success" id="msgSuccesso">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            <span id="testoSuccesso"></span>
        </div>
        <div class="msg error" id="msgErrore">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            <span id="testoErrore"></span>
        </div>

        <!-- form recensione -->
        <div class="review-form">
            <h3>Scrivi una recensione</h3>
            <form id="formRecensione" novalidate>
                <div class="form-group">
                    <label>La tua recensione</label>
                    <textarea id="commento" placeholder="Scrivi la tua opinione su questo libro..." required></textarea>
                </div>
                <div class="form-group">
                    <label>Valutazione</label>
                    <div class="rating-select">
                        <input type="radio" name="voto" id="v5" value="5"><label for="v5">★</label>
                        <input type="radio" name="voto" id="v4" value="4"><label for="v4">★</label>
                        <input type="radio" name="voto" id="v3" value="3"><label for="v3">★</label>
                        <input type="radio" name="voto" id="v2" value="2"><label for="v2">★</label>
                        <input type="radio" name="voto" id="v1" value="1"><label for="v1">★</label>
                    </div>
                </div>
                <button type="submit" class="btn-submit-review" id="btnInviaRecensione">Invia Recensione</button>
            </form>
        </div>

        <!-- lista recensioni -->
        <div id="listaRecensioni"></div>
    </div>
</div>

<script>
const API_BASE = 'http://185.6.242.121/~inb5/Rosso-Brocato-Scoccia/biblioteca/api';
const token    = localStorage.getItem('bs_token');
const user     = JSON.parse(localStorage.getItem('bs_user') || 'null');
const idLibro  = '<?php echo htmlspecialchars($idLibro); ?>';

if (!token) window.location.href = 'index.php';
if (!idLibro) window.location.href = 'libri.php';

// ── Logout ────────────────────────────────────────────────────────────────
function logout() {
    const req = new XMLHttpRequest();
    req.open('POST', API_BASE + '/auth.php?action=logout', true);
    req.setRequestHeader('Authorization', 'Bearer ' + token);
    req.onreadystatechange = function() {
        if (req.readyState !== 4) return;
        localStorage.removeItem('bs_token');
        localStorage.removeItem('bs_user');
        window.location.href = 'index.php';
    };
    req.send();
}

// ── Carica dettaglio libro ────────────────────────────────────────────────
function caricaLibro() {
    const req = new XMLHttpRequest();
    req.open('GET', `${API_BASE}/libri.php?id=${idLibro}`, true);
    req.setRequestHeader('Authorization', 'Bearer ' + token);
    req.timeout = 10000;

    req.onreadystatechange = function() {
        if (req.readyState !== 4) return;
        if (req.status === 401) { window.location.href = 'index.php'; return; }

        let libro = {};
        try { libro = JSON.parse(req.responseText); } catch {}

        if (req.status === 200) {
            renderLibro(libro);
            caricaRecensioni();
        } else {
            document.getElementById('dettaglioLibro').innerHTML =
                `<div class="loading-box"><p>Libro non trovato. <a href="libri.php" style="color:#8b5e3c">Torna al catalogo</a></p></div>`;
        }
    };
    req.send();
}

// ── Render dettaglio ──────────────────────────────────────────────────────
function renderLibro(libro) {
    document.title = `${libro.titolo} - Libreria Online`;
    document.getElementById('dettaglioLibro').innerHTML = `
        <div class="book-detail">
            ${libro.copertina
                ? `<img src="${esc(libro.copertina)}" alt="${esc(libro.titolo)}" class="book-cover-large"
                     onerror="this.src='';this.style.display='none'">`
                : `<div class="cover-placeholder">${esc(libro.titolo.charAt(0))}</div>`}
            <div class="book-info-detail">
                <h1 class="book-title-detail">${esc(libro.titolo)}</h1>
                <p class="book-author-detail">di ${esc(libro.autore)}</p>
                <div class="book-meta">
                    <span>${esc(libro.categoria || 'Varie')}</span>
                </div>
                <p class="book-description-detail"><strong>Descrizione:</strong><br>${esc(libro.descrizione || 'Non disponibile')}</p>
                <div class="book-price-detail">€${parseFloat(libro.prezzo || 0).toFixed(2)}</div>
            </div>
        </div>`;
    document.getElementById('sezioneRecensioni').style.display = 'block';
}

// ── Carica recensioni ─────────────────────────────────────────────────────
function caricaRecensioni() {
    const req = new XMLHttpRequest();
    req.open('GET', `${API_BASE}/recensioni.php?idLibro=${idLibro}`, true);
    req.setRequestHeader('Authorization', 'Bearer ' + token);
    req.timeout = 10000;

    req.onreadystatechange = function() {
        if (req.readyState !== 4) return;

        let risposta = { recensioni: [], mediaVoto: 0 };
        try { risposta = JSON.parse(req.responseText); } catch {}

        if (req.status === 200) renderRecensioni(risposta);
    };
    req.send();
}

function renderRecensioni(risposta) {
    const { recensioni, mediaVoto } = risposta;

    // aggiorna media stelle
    const stellePiene  = Math.round(mediaVoto);
    document.getElementById('mediaStelle').textContent   = '★'.repeat(stellePiene) + '☆'.repeat(5 - stellePiene);
    document.getElementById('mediaVotoTesto').textContent = `${mediaVoto}/5`;

    const lista = document.getElementById('listaRecensioni');
    if (!recensioni.length) {
        lista.innerHTML = '<div class="no-reviews">Nessuna recensione ancora. Sii il primo!</div>';
        return;
    }

    lista.innerHTML = recensioni.map(r => `
        <div class="review-card">
            <div class="review-header">
                <div>
                    <div class="review-author">${esc(r.utente)}</div>
                    <div class="review-date">${esc(r.data || '')}</div>
                </div>
                <div class="review-stars">${'★'.repeat(r.voto)}${'☆'.repeat(5 - r.voto)}</div>
            </div>
            <p class="review-comment">${esc(r.commento)}</p>
        </div>`).join('');
}

// ── Invia recensione via XHR ──────────────────────────────────────────────
document.getElementById('formRecensione').addEventListener('submit', function(e) {
    e.preventDefault();

    const commento = document.getElementById('commento').value.trim();
    const votoEl   = document.querySelector('input[name="voto"]:checked');
    const btn      = document.getElementById('btnInviaRecensione');

    document.getElementById('msgSuccesso').classList.remove('visible');
    document.getElementById('msgErrore').classList.remove('visible');

    if (!commento)  { mostraMsg('error', 'Scrivi una recensione prima di inviare'); return; }
    if (!votoEl)    { mostraMsg('error', 'Seleziona un voto'); return; }

    btn.disabled    = true;
    btn.textContent = 'Invio in corso…';

    const req = new XMLHttpRequest();
    req.open('POST', `${API_BASE}/recensioni.php`, true);
    req.setRequestHeader('Content-Type', 'application/json');
    req.setRequestHeader('Authorization', 'Bearer ' + token);
    req.timeout = 10000;

    req.onreadystatechange = function() {
        if (req.readyState !== 4) return;

        btn.disabled    = false;
        btn.textContent = 'Invia Recensione';

        let risposta = {};
        try { risposta = JSON.parse(req.responseText); } catch {}

        if (req.status === 201) {
            mostraMsg('success', 'Recensione aggiunta con successo!');
            document.getElementById('formRecensione').reset();
            caricaRecensioni(); // ricarica la lista
        } else {
            mostraMsg('error', risposta.error || 'Errore durante l\'invio');
        }
    };

    req.onerror   = () => { btn.disabled = false; btn.textContent = 'Invia Recensione'; mostraMsg('error', 'Errore di rete'); };
    req.ontimeout = () => { btn.disabled = false; btn.textContent = 'Invia Recensione'; mostraMsg('error', 'Timeout'); };

    req.send(JSON.stringify({ idLibro, commento, voto: parseInt(votoEl.value) }));
});

function mostraMsg(tipo, testo) {
    document.getElementById(tipo === 'success' ? 'testoSuccesso' : 'testoErrore').textContent = testo;
    document.getElementById(tipo === 'success' ? 'msgSuccesso'   : 'msgErrore').classList.add('visible');
}

function esc(str) {
    return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// avvio
caricaLibro();
</script>
</body>
</html>