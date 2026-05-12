<?php
// amministratore.php — Pannello admin
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pannello Amministratore - Libreria Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Lato',sans-serif;background-size:cover;min-height:100vh;padding:20px}
        .container{max-width:1200px;margin:0 auto}
        .header{background:rgba(250,246,241,.95);border-radius:16px;padding:25px 30px;margin-bottom:30px;box-shadow:0 10px 40px rgba(0,0,0,.3);border:1px solid #e8dcc8;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:20px;position:relative}
        .header::before{content:'';position:absolute;top:0;left:0;right:0;height:6px;background:linear-gradient(90deg,#8b5e3c,#a67c52,#c49a6c,#8b5e3c);border-radius:16px 16px 0 0}
        .header h1{font-family:'Playfair Display',serif;color:#2c1810;font-size:48px;font-weight:700;cursor:pointer;transition:transform .3s}
        .header h1:hover{transform:scale(1.1)}
        .search-form{flex:1;max-width:500px;position:relative;margin:0 20px}
        .search-form input{width:100%;padding:12px 20px 12px 48px;border:2px solid #d4c4a8;border-radius:10px;font-size:14px;font-family:'Lato',sans-serif;transition:all .3s;background:#fffdf9;color:#2c1810}
        .search-form input:focus{outline:none;border-color:#8b5e3c;background:#fff;box-shadow:0 0 0 4px rgba(139,94,60,.12)}
        .search-icon{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#a67c52}
        .search-icon svg{width:20px;height:20px;fill:currentColor}
        .search-form button{position:absolute;right:8px;top:50%;transform:translateY(-50%);padding:8px 16px;background:linear-gradient(135deg,#8b5e3c 0%,#5d3a1a 100%);color:#faf6f1;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:all .3s}
        .search-form button:hover{background:linear-gradient(135deg,#a67c52 0%,#8b5e3c 100%)}
        .btn-logout{padding:10px 20px;background:linear-gradient(135deg,#8b5e3c 0%,#5d3a1a 100%);color:#faf6f1;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;text-decoration:none;transition:all .3s;box-shadow:0 4px 15px rgba(139,94,60,.3)}
        .btn-logout:hover{transform:translateY(-2px)}
        .msg{padding:14px 16px;border-radius:10px;margin-bottom:20px;font-size:14px;display:none;align-items:center;gap:10px;animation:slideIn .3s ease}
        .msg.success{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
        .msg.error{background:#fff3f3;border:1px solid #ffd4d4;color:#8b2929}
        .msg.visible{display:flex}
        @keyframes slideIn{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:translateY(0)}}
        .books-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:25px;margin-bottom:30px}
        .book-card{background:rgba(250,246,241,.95);border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,.3);transition:all .3s;border:1px solid #e8dcc8;position:relative}
        .book-card:hover{transform:translateY(-5px);box-shadow:0 15px 40px rgba(0,0,0,.4)}
        .book-cover{width:100%;height:300px;object-fit:cover;background:#d4c4a8;display:block}
        .book-cover-placeholder{width:100%;height:300px;background:linear-gradient(135deg,#d4c4a8,#e8dcc8);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:4rem;color:#8b5e3c;opacity:.5}
        .book-info{padding:20px}
        .book-category{display:inline-block;padding:4px 12px;background:linear-gradient(135deg,#8b5e3c,#a67c52);color:#faf6f1;border-radius:20px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px}
        .book-title{font-family:'Playfair Display',serif;color:#2c1810;font-size:18px;font-weight:700;margin-bottom:5px;line-height:1.3}
        .book-author{color:#8b7355;font-size:14px;font-style:italic;margin-bottom:10px}
        .book-price{font-family:'Playfair Display',serif;color:#8b5e3c;font-size:20px;font-weight:700}
        .book-actions{position:absolute;bottom:10px;left:10px;z-index:10}
        .dots-btn{width:36px;height:36px;background:rgba(250,246,241,.95);border:1px solid #e8dcc8;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .3s;box-shadow:0 2px 10px rgba(0,0,0,.2)}
        .dots-btn:hover{background:#faf6f1;box-shadow:0 4px 15px rgba(0,0,0,.3)}
        .dots-btn svg{width:18px;height:18px;fill:#5d3a1a}
        .dropdown-menu{position:absolute;bottom:45px;left:0;background:#faf6f1;border:1px solid #e8dcc8;border-radius:10px;box-shadow:0 8px 30px rgba(0,0,0,.3);min-width:140px;display:none;overflow:hidden;z-index:100}
        .dropdown-menu.show{display:block;animation:fadeIn .2s ease}
        @keyframes fadeIn{from{opacity:0;transform:translateY(-5px)}to{opacity:1;transform:translateY(0)}}
        .dropdown-menu button{width:100%;padding:12px 16px;background:none;border:none;text-align:left;font-size:14px;font-family:'Lato',sans-serif;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:10px;color:#2c1810}
        .dropdown-menu button:hover{background:#f0e8dc}
        .dropdown-menu button svg{width:16px;height:16px;fill:currentColor}
        .dropdown-menu button.delete-btn{color:#8b2929}
        .dropdown-menu button.delete-btn:hover{background:#fff3f3}
        .dropdown-menu button.edit-btn{color:#8b5e3c}
        .dropdown-menu button.edit-btn:hover{background:#f5ede3}
        .modal-overlay{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.6);display:none;align-items:center;justify-content:center;z-index:1000;padding:20px}
        .modal-overlay.show{display:flex;animation:fadeIn .3s ease}
        .modal{background:#faf6f1;border-radius:16px;padding:40px;width:100%;max-width:540px;max-height:90vh;overflow-y:auto;box-shadow:0 25px 80px rgba(0,0,0,.5);border:1px solid #e8dcc8;position:relative}
        .modal::before{content:'';position:absolute;top:0;left:0;right:0;height:6px;background:linear-gradient(90deg,#8b5e3c,#a67c52,#c49a6c,#8b5e3c);border-radius:16px 16px 0 0}
        .modal h2{font-family:'Playfair Display',serif;color:#2c1810;font-size:24px;margin-bottom:25px}
        .modal-close{position:absolute;top:15px;right:15px;width:32px;height:32px;background:#e8dcc8;border:none;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .3s;color:#5d3a1a;font-size:18px}
        .modal-close:hover{background:#d4c4a8}
        .form-group{margin-bottom:20px}
        .form-group label{display:block;color:#5d3a1a;font-size:13px;font-weight:600;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px}
        .form-group input,.form-group textarea,.form-group select{width:100%;padding:12px 16px;border:2px solid #d4c4a8;border-radius:10px;font-size:14px;font-family:'Lato',sans-serif;transition:all .3s;background:#fffdf9;color:#2c1810}
        .form-group input:focus,.form-group textarea:focus,.form-group select:focus{outline:none;border-color:#8b5e3c;background:#fff;box-shadow:0 0 0 4px rgba(139,94,60,.12)}
        .form-group textarea{resize:vertical;min-height:80px}
        .input-file{border:2px dashed #d4c4a8 !important;cursor:pointer}
        .input-file:hover{border-color:#8b5e3c !important}
        .input-hint{font-size:.75rem;color:#a67c52;margin-top:4px}
        .cover-preview{width:80px;border-radius:6px;margin-bottom:8px;display:none}
        .modal-actions{display:flex;gap:12px;margin-top:25px}
        .btn-submit{flex:1;padding:14px;background:linear-gradient(135deg,#8b5e3c 0%,#5d3a1a 100%);color:#faf6f1;border:none;border-radius:10px;font-size:15px;font-weight:600;cursor:pointer;transition:all .3s;box-shadow:0 4px 15px rgba(139,94,60,.3)}
        .btn-submit:hover{transform:translateY(-2px)}
        .btn-submit:disabled{opacity:.65;cursor:not-allowed;transform:none}
        .btn-cancel{padding:14px 24px;background:#e8dcc8;color:#5d3a1a;border:none;border-radius:10px;font-size:15px;font-weight:600;cursor:pointer;transition:all .3s}
        .btn-cancel:hover{background:#d4c4a8}
        .empty-state{text-align:center;padding:60px 20px;color:#8b7355;grid-column:1/-1}
        .empty-state p{font-size:18px;font-style:italic}
        .loading-state{grid-column:1/-1;text-align:center;padding:60px;color:#8b7355}
        .spinner{width:40px;height:40px;border:4px solid #d4c4a8;border-top-color:#8b5e3c;border-radius:50%;animation:spin .7s linear infinite;margin:0 auto 15px}
        @keyframes spin{to{transform:rotate(360deg)}}
        @media(max-width:768px){.header{flex-direction:column;text-align:center}.search-form{max-width:100%;margin:10px 0}}
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1 onclick="apriModalAggiungi()" title="Aggiungi nuovo libro">+</h1>
        <div class="search-form">
            <span class="search-icon"><svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg></span>
            <input type="text" id="inputRicerca" placeholder="Cerca per titolo, autore..."/>
            <button onclick="caricaLibri(1)">Cerca</button>
        </div>
        <button class="btn-logout" onclick="logout()">Logout</button>
    </div>
    <div class="msg success" id="msgSuccesso">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        <span id="testoSuccesso"></span>
    </div>
    <div class="msg error" id="msgErrore">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
        <span id="testoErrore"></span>
    </div>
    <div class="books-grid" id="booksGrid">
        <div class="loading-state"><div class="spinner"></div><p>Caricamento...</p></div>
    </div>
</div>

<!-- Modal Aggiungi -->
<div class="modal-overlay" id="modalAggiungi">
    <div class="modal">
        <button class="modal-close" onclick="chiudiModal('modalAggiungi')">×</button>
        <h2>Aggiungi Nuovo Libro</h2>
        <form id="formAggiungi" novalidate>
            <div class="form-group"><label>Titolo *</label><input type="text" id="a_titolo" required placeholder="Titolo del libro"/></div>
            <div class="form-group"><label>Autore *</label><input type="text" id="a_autore" required placeholder="Nome autore"/></div>
            <div class="form-group">
                <label>Categoria</label>
                <select id="a_categoria">
                    <option value="">Seleziona categoria</option>
                    <option>Narrativa</option><option>Saggistica</option><option>Romanzo</option>
                    <option>Giallo</option><option>Fantascienza</option><option>Fantasy</option>
                    <option>Biografia</option><option>Storia</option><option>Poesia</option><option>Varie</option>
                </select>
            </div>
            <div class="form-group"><label>Prezzo (€)</label><input type="number" id="a_prezzo" step="0.01" min="0" placeholder="0.00"/></div>
            <div class="form-group"><label>Descrizione</label><textarea id="a_descrizione" placeholder="Breve descrizione..."></textarea></div>
            <div class="form-group">
                <label>Copertina</label>
                <img id="a_coverPreview" class="cover-preview" src="" alt="Anteprima"/>
                <input type="file" id="a_coverFile" accept="image/jpeg,image/png,image/webp,image/gif" class="input-file"/>
                <p class="input-hint">JPG, PNG, WEBP o GIF · max 5MB</p>
                <span id="a_uploadProgress" style="font-size:.8rem;color:#8b5e3c"></span>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="chiudiModal('modalAggiungi')">Annulla</button>
                <button type="submit" class="btn-submit" id="btnAggiungi">Aggiungi Libro</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Modifica -->
<div class="modal-overlay" id="modalModifica">
    <div class="modal">
        <button class="modal-close" onclick="chiudiModal('modalModifica')">×</button>
        <h2>Modifica Libro</h2>
        <form id="formModifica" novalidate>
            <input type="hidden" id="m_id"/>
            <div class="form-group"><label>Titolo *</label><input type="text" id="m_titolo" required/></div>
            <div class="form-group"><label>Autore *</label><input type="text" id="m_autore" required/></div>
            <div class="form-group">
                <label>Categoria</label>
                <select id="m_categoria">
                    <option value="">Seleziona categoria</option>
                    <option>Narrativa</option><option>Saggistica</option><option>Romanzo</option>
                    <option>Giallo</option><option>Fantascienza</option><option>Fantasy</option>
                    <option>Biografia</option><option>Storia</option><option>Poesia</option><option>Varie</option>
                </select>
            </div>
            <div class="form-group"><label>Prezzo (€)</label><input type="number" id="m_prezzo" step="0.01" min="0"/></div>
            <div class="form-group"><label>Descrizione</label><textarea id="m_descrizione"></textarea></div>
            <div class="form-group">
                <label>Copertina</label>
                <img id="m_coverPreview" class="cover-preview" src="" alt="Anteprima copertina attuale"/>
                <input type="file" id="m_coverFile" accept="image/jpeg,image/png,image/webp,image/gif" class="input-file"/>
                <p class="input-hint">Seleziona solo se vuoi cambiare la copertina</p>
                <span id="m_uploadProgress" style="font-size:.8rem;color:#8b5e3c"></span>
                <input type="hidden" id="m_coverAttuale"/>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="chiudiModal('modalModifica')">Annulla</button>
                <button type="submit" class="btn-submit" id="btnModifica">Salva Modifiche</button>
            </div>
        </form>
    </div>
</div>

<script>
const API_BASE = 'http://185.6.242.121/~inb5/Rosso-Brocato-Scoccia/biblioteca/api';
const token    = localStorage.getItem('bs_token');
const user     = JSON.parse(localStorage.getItem('bs_user') || 'null');

if (!token || !user || user.ruolo !== 'admin') {
    window.location.href = 'index.php';
}

function logout() {
    const req = new XMLHttpRequest();
    req.open('POST', API_BASE + '/auth.php?action=logout', true);
    req.setRequestHeader('Authorization', 'Bearer ' + token);
    req.setRequestHeader('X-Token', token);
    req.onreadystatechange = function() {
        if (req.readyState !== 4) return;
        localStorage.removeItem('bs_token');
        localStorage.removeItem('bs_user');
        window.location.href = 'index.php';
    };
    req.send();
}

function caricaLibri(pagina) {
    const ricerca = document.getElementById('inputRicerca').value.trim();
    let url = API_BASE + '/libri.php?page=' + (pagina || 1) + '&limit=12';
    if (ricerca) url += '&ricerca=' + encodeURIComponent(ricerca);

    document.getElementById('booksGrid').innerHTML =
        '<div class="loading-state"><div class="spinner"></div><p>Caricamento...</p></div>';

    const req = new XMLHttpRequest();
    req.open('GET', url, true);
    req.setRequestHeader('Authorization', 'Bearer ' + token);
    req.setRequestHeader('X-Token', token);
    document.cookie = 'bs_token=' + token + '; path=/';
    req.timeout = 10000;

    req.onreadystatechange = function() {
        if (req.readyState !== 4) return;
        if (req.status === 401) { window.location.href = 'index.php'; return; }
        let risposta = { libri: [] };
        try { risposta = JSON.parse(req.responseText); } catch {}
        if (req.status === 200) renderLibri(risposta.libri || []);
        else document.getElementById('booksGrid').innerHTML = '<div class="empty-state"><p>Errore nel caricamento.</p></div>';
    };
    req.send();
}

function renderLibri(libri) {
    const grid = document.getElementById('booksGrid');
    if (!libri.length) {
        grid.innerHTML = '<div class="empty-state"><p>Nessun libro. Clicca "+" per aggiungerne uno.</p></div>';
        return;
    }

    window._libriData = {};
    libri.forEach(function(l) { window._libriData[l._id] = l; });

    var html = '';
    libri.forEach(function(libro) {
        var coverHtml = libro.copertina
            ? '<img src="' + esc(libro.copertina) + '" alt="' + esc(libro.titolo) + '" class="book-cover" onerror="this.style.display=\'none\'">'
            : '<div class="book-cover-placeholder">' + esc(libro.titolo.charAt(0)) + '</div>';

        html += '<div class="book-card">' +
            coverHtml +
            '<div class="book-actions">' +
                '<button class="dots-btn" onclick="toggleDropdown(this)">' +
                    '<svg viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>' +
                '</button>' +
                '<div class="dropdown-menu">' +
                    '<button class="edit-btn" onclick="apriModalModificaById(\'' + libro._id + '\')">' +
                        '<svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>' +
                        'Modifica' +
                    '</button>' +
                    '<button class="delete-btn" onclick="eliminaLibro(\'' + libro._id + '\')">' +
                        '<svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>' +
                        'Elimina' +
                    '</button>' +
                '</div>' +
            '</div>' +
            '<div class="book-info">' +
                '<span class="book-category">' + esc(libro.categoria || 'Varie') + '</span>' +
                '<h3 class="book-title">' + esc(libro.titolo) + '</h3>' +
                '<p class="book-author">di ' + esc(libro.autore) + '</p>' +
                '<p class="book-price">€' + parseFloat(libro.prezzo || 0).toFixed(2) + '</p>' +
            '</div>' +
        '</div>';
    });
    grid.innerHTML = html;
}

function uploadCopertina(fileInput, progressId) {
    return new Promise(function(resolve) {
        var file = fileInput.files[0];
        if (!file) return resolve(null);
        if (file.size > 5 * 1024 * 1024) { mostraMsg('error', 'File troppo grande (max 5MB)'); return resolve(null); }

        var formData = new FormData();
        formData.append('copertina', file);

        var req = new XMLHttpRequest();
        req.open('POST', API_BASE + '/upload.php', true);
        req.setRequestHeader('Authorization', 'Bearer ' + token);
        req.setRequestHeader('X-Token', token);

        req.upload.onprogress = function(e) {
            if (e.lengthComputable)
                document.getElementById(progressId).textContent = 'Caricamento ' + Math.round((e.loaded/e.total)*100) + '%';
        };

        req.onreadystatechange = function() {
    if (req.readyState !== 4) return;
    document.getElementById(progressId).textContent = '';
    if (req.status === 201) {
        resolve(JSON.parse(req.responseText).copertina);
    } else {
        var msg = 'Errore upload';
        try { msg = JSON.parse(req.responseText).error; } catch(e) {}
        mostraMsg('error', msg);
        resolve(null);
    }
};
        req.onerror = function() { mostraMsg('error', 'Errore di rete'); resolve(null); };
        req.send(formData);
    });
}

document.getElementById('formAggiungi').addEventListener('submit', async function(e) {
    e.preventDefault();
    var titolo = document.getElementById('a_titolo').value.trim();
    var autore = document.getElementById('a_autore').value.trim();
    if (!titolo || !autore) { mostraMsg('error', 'Titolo e autore sono obbligatori'); return; }

    var btn = document.getElementById('btnAggiungi');
    btn.disabled = true; btn.textContent = 'Salvataggio...';

    var copertina = null;
    var fileInput = document.getElementById('a_coverFile');
    if (fileInput.files.length > 0) copertina = await uploadCopertina(fileInput, 'a_uploadProgress');

    var payload = {
        titolo: titolo,
        autore: autore,
        categoria: document.getElementById('a_categoria').value,
        prezzo: parseFloat(document.getElementById('a_prezzo').value) || 0,
        descrizione: document.getElementById('a_descrizione').value.trim(),
        copertina: copertina
    };

    var req = new XMLHttpRequest();
    req.open('POST', API_BASE + '/libri.php', true);
    req.setRequestHeader('Content-Type', 'application/json');
    req.setRequestHeader('Authorization', 'Bearer ' + token);
    req.setRequestHeader('X-Token', token);
    req.timeout = 10000;

    req.onreadystatechange = function() {
        if (req.readyState !== 4) return;
        btn.disabled = false; btn.textContent = 'Aggiungi Libro';
        var risposta = {};
        try { risposta = JSON.parse(req.responseText); } catch(e) {}
        if (req.status === 201) {
            chiudiModal('modalAggiungi');
            document.getElementById('formAggiungi').reset();
            document.getElementById('a_coverPreview').style.display = 'none';
            mostraMsg('success', risposta.message);
            caricaLibri(1);
        } else {
            mostraMsg('error', risposta.error || "Errore durante l'aggiunta");
        }
    };
    req.send(JSON.stringify(payload));
});

document.getElementById('formModifica').addEventListener('submit', async function(e) {
    e.preventDefault();
    var id = document.getElementById('m_id').value;
    var titolo = document.getElementById('m_titolo').value.trim();
    var autore = document.getElementById('m_autore').value.trim();
    if (!titolo || !autore) { mostraMsg('error', 'Titolo e autore sono obbligatori'); return; }

    var btn = document.getElementById('btnModifica');
    btn.disabled = true; btn.textContent = 'Salvataggio...';

    var copertina = document.getElementById('m_coverAttuale').value || null;
    var fileInput = document.getElementById('m_coverFile');
    if (fileInput.files.length > 0) {
        var nuova = await uploadCopertina(fileInput, 'm_uploadProgress');
        if (nuova) copertina = nuova;
    }

    var payload = {
        titolo: titolo,
        autore: autore,
        categoria: document.getElementById('m_categoria').value,
        prezzo: parseFloat(document.getElementById('m_prezzo').value) || 0,
        descrizione: document.getElementById('m_descrizione').value.trim(),
        copertina: copertina
    };

    var req = new XMLHttpRequest();
    req.open('PUT', API_BASE + '/libri.php?id=' + id, true);
    req.setRequestHeader('Content-Type', 'application/json');
    req.setRequestHeader('Authorization', 'Bearer ' + token);
    req.setRequestHeader('X-Token', token);
    req.timeout = 10000;

    req.onreadystatechange = function() {
        if (req.readyState !== 4) return;
        btn.disabled = false; btn.textContent = 'Salva Modifiche';
        var risposta = {};
        try { risposta = JSON.parse(req.responseText); } catch(e) {}
        if (req.status === 200) {
            chiudiModal('modalModifica');
            mostraMsg('success', risposta.message);
            caricaLibri(1);
        } else {
            mostraMsg('error', risposta.error || 'Errore durante la modifica');
        }
    };
    req.send(JSON.stringify(payload));
});

function eliminaLibro(id) {
    chiudiTuttiDropdown();
    if (!confirm('Sei sicuro di voler eliminare questo libro?')) return;

    var req = new XMLHttpRequest();
    req.open('DELETE', API_BASE + '/libri.php?id=' + id, true);
    req.setRequestHeader('Authorization', 'Bearer ' + token);
    req.setRequestHeader('X-Token', token);
    req.timeout = 10000;

    req.onreadystatechange = function() {
        if (req.readyState !== 4) return;
        var risposta = {};
        try { risposta = JSON.parse(req.responseText); } catch(e) {}
        if (req.status === 200) { mostraMsg('success', risposta.message); caricaLibri(1); }
        else mostraMsg('error', risposta.error || "Errore durante l'eliminazione");
    };
    req.send();
}

function toggleDropdown(btn) {
    var dropdown = btn.nextElementSibling;
    chiudiTuttiDropdown();
    dropdown.classList.toggle('show');
}

function chiudiTuttiDropdown() {
    document.querySelectorAll('.dropdown-menu').forEach(function(d) { d.classList.remove('show'); });
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.book-actions')) chiudiTuttiDropdown();
});

function apriModalAggiungi() {
    document.getElementById('formAggiungi').reset();
    document.getElementById('a_coverPreview').style.display = 'none';
    document.getElementById('modalAggiungi').classList.add('show');
}

function apriModalModificaById(id) {
    var libro = window._libriData[id];
    if (!libro) return;
    apriModalModifica(libro);
}

function apriModalModifica(libro) {
    chiudiTuttiDropdown();
    document.getElementById('m_id').value          = libro._id;
    document.getElementById('m_titolo').value      = libro.titolo || '';
    document.getElementById('m_autore').value      = libro.autore || '';
    document.getElementById('m_categoria').value   = libro.categoria || '';
    document.getElementById('m_prezzo').value      = libro.prezzo || '';
    document.getElementById('m_descrizione').value = libro.descrizione || '';
    document.getElementById('m_coverAttuale').value = libro.copertina || '';
    document.getElementById('m_coverFile').value   = '';

    var prev = document.getElementById('m_coverPreview');
    if (libro.copertina) { prev.src = libro.copertina; prev.style.display = 'block'; }
    else { prev.style.display = 'none'; }

    document.getElementById('modalModifica').classList.add('show');
}

function chiudiModal(id) {
    document.getElementById(id).classList.remove('show');
}

document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) overlay.classList.remove('show');
    });
});

['a_coverFile', 'm_coverFile'].forEach(function(id) {
    document.getElementById(id).addEventListener('change', function() {
        var file = this.files[0];
        if (!file) return;
        var reader = new FileReader();
        var prevId = id === 'a_coverFile' ? 'a_coverPreview' : 'm_coverPreview';
        reader.onload = function(e) {
            var prev = document.getElementById(prevId);
            prev.src = e.target.result;
            prev.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });
});

function mostraMsg(tipo, testo) {
    document.querySelectorAll('.msg').forEach(function(m) { m.classList.remove('visible'); });
    document.getElementById(tipo === 'success' ? 'testoSuccesso' : 'testoErrore').textContent = testo;
    document.getElementById(tipo === 'success' ? 'msgSuccesso' : 'msgErrore').classList.add('visible');
    setTimeout(function() {
        document.querySelectorAll('.msg').forEach(function(m) { m.classList.remove('visible'); });
    }, 4000);
}

function esc(str) {
    return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

document.getElementById('inputRicerca').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') caricaLibri(1);
});

caricaLibri(1);
</script>
</body>
</html>