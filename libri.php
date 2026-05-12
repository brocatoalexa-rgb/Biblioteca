<?php
// libri.php — Catalogo libri per utenti normali
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libreria Online - Catalogo Libri</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Lato',sans-serif;background-size:cover;min-height:100vh;padding:20px}
        .container{max-width:1200px;margin:0 auto}
        .header{background:rgba(250,246,241,.95);border-radius:16px;padding:30px 40px;margin-bottom:30px;box-shadow:0 10px 40px rgba(0,0,0,.3);border:1px solid #e8dcc8;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:20px;position:relative}
        .header::before{content:'';position:absolute;top:0;left:0;right:0;height:6px;background:linear-gradient(90deg,#8b5e3c,#a67c52,#c49a6c,#8b5e3c);border-radius:16px 16px 0 0}
        .header h1{font-family:'Playfair Display',serif;color:#2c1810;font-size:32px;font-weight:700}
        .header .user-info{display:flex;align-items:center;gap:15px}
        .header .welcome{color:#5d3a1a;font-size:14px}
        .header .welcome strong{color:#8b5e3c}
        .btn-logout{padding:10px 20px;background:linear-gradient(135deg,#8b5e3c 0%,#5d3a1a 100%);color:#faf6f1;border:none;border-radius:8px;font-size:14px;font-weight:600;font-family:'Lato',sans-serif;cursor:pointer;text-decoration:none;transition:all .3s ease;box-shadow:0 4px 15px rgba(139,94,60,.3)}
        .btn-logout:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(139,94,60,.4)}

        /* ricerca */
        .search-bar{background:rgba(250,246,241,.95);border-radius:12px;padding:20px 30px;margin-bottom:25px;box-shadow:0 5px 20px rgba(0,0,0,.2);border:1px solid #e8dcc8;display:flex;gap:15px;flex-wrap:wrap;align-items:center}
        .search-bar input,.search-bar select{padding:10px 16px;border:2px solid #d4c4a8;border-radius:8px;font-size:14px;font-family:'Lato',sans-serif;background:#fffdf9;color:#2c1810;transition:border-color .3s}
        .search-bar input{flex:1;min-width:200px}
        .search-bar input:focus,.search-bar select:focus{outline:none;border-color:#8b5e3c}
        .search-bar button{padding:10px 20px;background:linear-gradient(135deg,#8b5e3c 0%,#5d3a1a 100%);color:#faf6f1;border:none;border-radius:8px;font-weight:600;cursor:pointer;transition:all .3s}
        .search-bar button:hover{background:linear-gradient(135deg,#a67c52 0%,#8b5e3c 100%)}

        .books-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:25px;margin-bottom:30px}
        .book-card{background:rgba(250,246,241,.95);border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,.3);transition:all .3s ease;border:1px solid #e8dcc8;cursor:pointer}
        .book-card:hover{transform:translateY(-5px);box-shadow:0 15px 40px rgba(0,0,0,.4)}
        .book-cover{width:100%;height:300px;object-fit:cover;background:#d4c4a8;display:block}
        .book-cover-placeholder{width:100%;height:300px;background:linear-gradient(135deg,#d4c4a8,#e8dcc8);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:3rem;color:#8b5e3c;opacity:.5}
        .book-info{padding:20px}
        .book-category{display:inline-block;padding:4px 12px;background:linear-gradient(135deg,#8b5e3c,#a67c52);color:#faf6f1;border-radius:20px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px}
        .book-title{font-family:'Playfair Display',serif;color:#2c1810;font-size:20px;font-weight:700;margin-bottom:5px;line-height:1.3}
        .book-author{color:#8b7355;font-size:14px;font-style:italic;margin-bottom:10px}
        .book-description{color:#5d3a1a;font-size:13px;line-height:1.6;margin-bottom:15px;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
        .book-footer{display:flex;justify-content:space-between;align-items:center;padding-top:15px;border-top:1px solid #e8dcc8}
        .book-price{font-family:'Playfair Display',serif;color:#8b5e3c;font-size:22px;font-weight:700}
        .btn-dettagli{padding:8px 20px;background:linear-gradient(135deg,#8b5e3c 0%,#5d3a1a 100%);color:#faf6f1;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:all .3s}
        .btn-dettagli:hover{transform:translateY(-2px);box-shadow:0 4px 15px rgba(139,94,60,.4)}

        /* stato caricamento / vuoto */
        .loading-state{grid-column:1/-1;text-align:center;padding:60px 20px;color:#8b7355}
        .spinner{width:40px;height:40px;border:4px solid #d4c4a8;border-top-color:#8b5e3c;border-radius:50%;animation:spin .7s linear infinite;margin:0 auto 15px}
        @keyframes spin{to{transform:rotate(360deg)}}
        .empty-state{grid-column:1/-1;text-align:center;padding:60px 20px;color:#8b7355;font-style:italic;font-size:16px}

        /* paginazione */
        .paginazione{display:flex;justify-content:center;gap:8px;margin-top:20px}
        .paginazione button{padding:8px 14px;border:2px solid #d4c4a8;border-radius:8px;background:#faf6f1;color:#5d3a1a;font-family:'Lato',sans-serif;cursor:pointer;transition:all .3s}
        .paginazione button:hover,.paginazione button.active{background:#8b5e3c;color:#faf6f1;border-color:#8b5e3c}
        .paginazione button:disabled{opacity:.4;cursor:default}

        @media(max-width:768px){.header{flex-direction:column;text-align:center}.books-grid{grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:20px}}
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <h1>Catalogo Libri</h1>
        <div class="user-info">
            <span class="welcome">Benvenuto, <strong id="nomeUtente">...</strong></span>
            <button class="btn-logout" onclick="logout()">Logout</button>
        </div>
    </div>

    <!-- Barra di ricerca -->
    <div class="search-bar">
        <input type="text" id="inputRicerca" placeholder="Cerca per titolo, autore..."/>
        <select id="selectCategoria">
            <option value="">Tutte le categorie</option>
            <option>Narrativa</option><option>Saggistica</option>
            <option>Romanzo</option><option>Giallo</option>
            <option>Fantascienza</option><option>Fantasy</option>
            <option>Biografia</option><option>Storia</option>
            <option>Poesia</option><option>Varie</option>
        </select>
        <button onclick="caricaLibri(1)">Cerca</button>
    </div>

    <!-- Griglia libri -->
    <div class="books-grid" id="booksGrid">
        <div class="loading-state">
            <div class="spinner"></div>
            <p>Caricamento catalogo...</p>
        </div>
    </div>

    <!-- Paginazione -->
    <div class="paginazione" id="paginazione"></div>

</div>

<script>
const API_BASE   = 'http://185.6.242.121/~inb5/Rosso-Brocato-Scoccia/biblioteca/api';
let   paginaCorrente = 1;

// ── Controlla se loggato ──────────────────────────────────────────────────
const token = localStorage.getItem('bs_token');
const user  = JSON.parse(localStorage.getItem('bs_user') || 'null');

if (!token) {
    window.location.href = 'index.php';
}

if (user) {
    document.getElementById('nomeUtente').textContent = user.username;
}

// ── Logout ────────────────────────────────────────────────────────────────
function logout() {
    const req = new XMLHttpRequest();
    req.open('POST', API_BASE + '/auth.php?action=logout', true);
    req.setRequestHeader('Content-Type', 'application/json');
    req.setRequestHeader('Authorization', 'Bearer ' + token);
    req.onreadystatechange = function() {
        if (req.readyState !== 4) return;
        localStorage.removeItem('bs_token');
        localStorage.removeItem('bs_user');
        window.location.href = 'index.php';
    };
    req.send();
}

// ── Carica libri via XHR ──────────────────────────────────────────────────
function caricaLibri(pagina) {
    paginaCorrente = pagina || 1;

    const ricerca   = document.getElementById('inputRicerca').value.trim();
    const categoria = document.getElementById('selectCategoria').value;

    let url = `${API_BASE}/libri.php?page=${paginaCorrente}&limit=12`;
    if (ricerca)   url += `&ricerca=${encodeURIComponent(ricerca)}`;
    if (categoria) url += `&categoria=${encodeURIComponent(categoria)}`;

    document.getElementById('booksGrid').innerHTML =
        '<div class="loading-state"><div class="spinner"></div><p>Caricamento...</p></div>';

    // ── XHR ──────────────────────────────────────────────────────────────
    const req = new XMLHttpRequest();

    req.open("GET", url, true);
    req.setRequestHeader('Content-Type', 'application/json');
    req.setRequestHeader('Authorization', 'Bearer ' + token);
    req.timeout = 10000;

    req.onreadystatechange = function() {
        if (req.readyState !== 4) return;

        if (req.status === 401) { window.location.href = 'index.php'; return; }

        let risposta = {};
        try { risposta = JSON.parse(req.responseText); } catch {}

        if (req.status === 200) {
            renderLibri(risposta.libri || []);
            renderPaginazione(risposta.pagination || {});
        } else {
            document.getElementById('booksGrid').innerHTML =
                '<div class="empty-state">Errore nel caricamento dei libri.</div>';
        }
    };

    req.onerror = () => {
        document.getElementById('booksGrid').innerHTML =
            '<div class="empty-state">Errore di connessione al server.</div>';
    };

    req.send();
}

// ── Render griglia libri ──────────────────────────────────────────────────
function renderLibri(libri) {
    const grid = document.getElementById('booksGrid');

    if (!libri.length) {
        grid.innerHTML = '<div class="empty-state">Nessun libro trovato.</div>';
        return;
    }

    grid.innerHTML = libri.map(libro => `
        <div class="book-card" onclick="vaiADettaglio('${libro._id}')">
            ${libro.copertina
                ? `<img src="${libro.copertina}" alt="${esc(libro.titolo)}" class="book-cover"
                     onerror="this.style.display='none'">`
                : ``
            }
            <div class="book-info">
                <span class="book-category">${esc(libro.categoria || 'Varie')}</span>
                <h3 class="book-title">${esc(libro.titolo)}</h3>
                <p class="book-author">di ${esc(libro.autore)}</p>
                <p class="book-description">${esc(libro.descrizione || '')}</p>
                <div class="book-footer">
                    <span class="book-price">€${parseFloat(libro.prezzo || 0).toFixed(2)}</span>
                    <button class="btn-dettagli" onclick="event.stopPropagation();vaiADettaglio('${libro._id}')">Dettagli</button>
                </div>
            </div>
        </div>
    `).join('');
}

// ── Paginazione ───────────────────────────────────────────────────────────
function renderPaginazione(pagination) {
    const el = document.getElementById('paginazione');
    if (!pagination.totalPages || pagination.totalPages <= 1) { el.innerHTML = ''; return; }

    let html = `<button onclick="caricaLibri(${pagination.page - 1})" ${pagination.page === 1 ? 'disabled' : ''}>←</button>`;
    for (let i = 1; i <= pagination.totalPages; i++) {
        html += `<button class="${i === pagination.page ? 'active' : ''}" onclick="caricaLibri(${i})">${i}</button>`;
    }
    html += `<button onclick="caricaLibri(${pagination.page + 1})" ${pagination.page === pagination.totalPages ? 'disabled' : ''}>→</button>`;
    el.innerHTML = html;
}

function vaiADettaglio(id) {
    window.location.href = `interfaccia_libro.php?id=${id}`;
}

function esc(str) {
    return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ricerca al premere invio
document.getElementById('inputRicerca').addEventListener('keydown', e => {
    if (e.key === 'Enter') caricaLibri(1);
});

// avvio
caricaLibri(1);
</script>
</body>
</html>