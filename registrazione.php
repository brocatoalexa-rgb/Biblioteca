<?php
// registrazione.php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: libri.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libreria Online - Registrati</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Lato',sans-serif;background:url('sfondo-librieria.jpg') no-repeat center center fixed;background-size:cover;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
        .register-container{background:#faf6f1;border-radius:16px;box-shadow:0 25px 80px rgba(0,0,0,.4);padding:45px 40px;width:100%;max-width:440px;position:relative;overflow:hidden;border:1px solid #e8dcc8}
        .register-container::before{content:'';position:absolute;top:0;left:0;right:0;height:6px;background:linear-gradient(90deg,#8b5e3c,#a67c52,#c49a6c,#8b5e3c)}
        .register-container::after{content:'';position:absolute;bottom:0;left:0;right:0;height:6px;background:linear-gradient(90deg,#8b5e3c,#a67c52,#c49a6c,#8b5e3c)}
        .register-header{text-align:center;margin-bottom:35px}
        .register-header .icon{width:80px;height:80px;background:linear-gradient(135deg,#8b5e3c 0%,#5d3a1a 100%);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;box-shadow:0 8px 25px rgba(139,94,60,.4)}
        .register-header .icon svg{width:42px;height:42px;fill:#faf6f1}
        .register-header h2{font-family:'Playfair Display',serif;color:#2c1810;font-size:30px;font-weight:700;margin-bottom:8px;letter-spacing:-.5px}
        .register-header .subtitle{color:#8b7355;font-size:14px;font-style:italic;font-family:'Playfair Display',serif}
        .success-message,.error-message{padding:14px 16px;border-radius:10px;margin-bottom:24px;font-size:14px;display:none;align-items:center;gap:10px;animation:slideIn .3s ease}
        .success-message{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
        .error-message{background:#fff3f3;border:1px solid #ffd4d4;color:#8b2929;font-size:13px}
        .success-message.visible,.error-message.visible{display:flex}
        @keyframes slideIn{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:translateY(0)}}
        .form-group{margin-bottom:22px;position:relative}
        .form-group label{display:block;color:#5d3a1a;font-size:13px;font-weight:600;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px}
        .form-group .input-wrapper{position:relative}
        .form-group .input-icon{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#a67c52;transition:color .3s ease}
        .form-group .input-icon svg{width:20px;height:20px;fill:currentColor}
        .form-group input{width:100%;padding:15px 16px 15px 48px;border:2px solid #d4c4a8;border-radius:10px;font-size:15px;font-family:'Lato',sans-serif;transition:all .3s ease;background:#fffdf9;color:#2c1810}
        .form-group input:focus{outline:none;border-color:#8b5e3c;background:#fff;box-shadow:0 0 0 4px rgba(139,94,60,.12)}
        .form-group .input-wrapper:focus-within .input-icon{color:#8b5e3c}
        .form-group input::placeholder{color:#b8a88a}
        .btn-register{width:100%;padding:16px;background:linear-gradient(135deg,#8b5e3c 0%,#5d3a1a 100%);color:#faf6f1;border:none;border-radius:10px;font-size:16px;font-weight:600;font-family:'Lato',sans-serif;cursor:pointer;transition:all .3s ease;box-shadow:0 6px 20px rgba(139,94,60,.35);margin-top:12px;letter-spacing:.5px}
        .btn-register:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(139,94,60,.45);background:linear-gradient(135deg,#a67c52 0%,#8b5e3c 100%)}
        .btn-register:disabled{opacity:.65;cursor:not-allowed;transform:none}
        .divider{display:flex;align-items:center;margin:28px 0}
        .divider::before,.divider::after{content:'';flex:1;height:1px;background:#d4c4a8}
        .divider span{padding:0 18px;color:#a67c52;font-size:12px;text-transform:uppercase;letter-spacing:1px}
        .login-section{text-align:center}
        .login-section p{color:#8b7355;font-size:14px}
        .login-section a{color:#8b5e3c;text-decoration:none;font-weight:600;transition:all .3s ease;border-bottom:2px solid transparent}
        .login-section a:hover{color:#5d3a1a;border-bottom-color:#8b5e3c}
        @media(max-width:480px){.register-container{padding:35px 25px}.register-header h2{font-size:26px}}
    </style>
</head>
<body>
<div class="register-container">
    <div class="register-header">
        <div class="icon">
            <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        </div>
        <h2>Crea Account</h2>
        <p class="subtitle">Unisciti alla nostra libreria online</p>
    </div>

    <div class="success-message" id="msgSuccesso">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
        <span id="testoSuccesso">Registrazione completata! Reindirizzamento...</span>
    </div>
    <div class="error-message" id="msgErrore">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
        <span id="testoErrore">Errore durante la registrazione</span>
    </div>

    <form id="formRegistrazione" novalidate>
        <div class="form-group">
            <label for="username">Username</label>
            <div class="input-wrapper">
                <input type="text" id="username" placeholder="Scegli un username" required minlength="3"/>
                <span class="input-icon"><svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg></span>
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-wrapper">
                <input type="email" id="email" placeholder="Inserisci la tua email" required/>
                <span class="input-icon"><svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg></span>
            </div>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <input type="password" id="password" placeholder="Min 6 caratteri" required minlength="6"/>
                <span class="input-icon"><svg viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1s3.1 1.39 3.1 3.1v2z"/></svg></span>
            </div>
        </div>
        <button type="submit" class="btn-register" id="btnRegistrati">Registrati</button>
    </form>

    <div class="divider"><span>oppure</span></div>
    <div class="login-section">
        <p>Hai già un account? <a href="index.php">Accedi ora</a></p>
    </div>
</div>

<script>
const API_BASE = 'http://185.6.242.121/~inb5/Rosso-Brocato-Scoccia/biblioteca/api';

document.getElementById('formRegistrazione').addEventListener('submit', function(e) {
    e.preventDefault();

    const username = document.getElementById('username').value.trim();
    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const btn      = document.getElementById('btnRegistrati');

    // nascondi messaggi precedenti
    document.getElementById('msgSuccesso').classList.remove('visible');
    document.getElementById('msgErrore').classList.remove('visible');

    // validazione client
    if (username.length < 3) { mostraErrore('Username troppo corto (min 3 caratteri)'); return; }
    if (password.length < 6) { mostraErrore('Password troppo corta (min 6 caratteri)'); return; }

    btn.disabled    = true;
    btn.textContent = 'Registrazione in corso…';

    // ── XHR ──────────────────────────────────────────────────────────────
    const req = new XMLHttpRequest();

    req.open('POST', API_BASE + '/auth.php?action=register', true);
    req.setRequestHeader('Content-Type', 'application/json');
    req.timeout = 10000;

    req.onreadystatechange = function() {
        if (req.readyState !== 4) return;

        btn.disabled    = false;
        btn.textContent = 'Registrati';

        let risposta = {};
        try { risposta = JSON.parse(req.responseText); } catch {}

        if (req.status === 201) {
            localStorage.setItem('bs_token', risposta.token);
            localStorage.setItem('bs_user',  JSON.stringify(risposta.user));
            document.cookie = 'bs_token=' + risposta.token + '; path=/';
            document.getElementById('testoSuccesso').textContent = 'Account creato! Reindirizzamento...';
            document.getElementById('msgSuccesso').classList.add('visible');
            setTimeout(() => { window.location.href = 'libri.php'; }, 1500);
        } else {
            mostraErrore(risposta.error || 'Errore durante la registrazione');
        }
    };

    req.onerror   = () => { btn.disabled = false; btn.textContent = 'Registrati'; mostraErrore('Errore di rete'); };
    req.ontimeout = () => { btn.disabled = false; btn.textContent = 'Registrati'; mostraErrore('Timeout: server non risponde'); };

    req.send(JSON.stringify({ username, email, password }));
});

function mostraErrore(testo) {
    document.getElementById('testoErrore').textContent = testo;
    document.getElementById('msgErrore').classList.add('visible');
}
</script>
</body>
</html>