<?php
// ── ALL PHP LOGIC FIRST — zero output before this block ─────
require_once '../includes/config.php';

if (isAdminLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === ADMIN_USER && password_verify($password, ADMIN_PASS_HASH)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user']      = $username;
        $_SESSION['login_time']      = time();
        try {
            $db = getDB();
            $db->prepare("INSERT INTO activity_log (action, details) VALUES (?, ?)")
               ->execute(['Admin Login', 'Login from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? '')]);
        } catch (Exception $e) {}
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
        sleep(1); // brute-force delay
    }
}
// ── HTML starts here — headers are safe to send above ────────
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login — Ali Afzal 3D</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
:root {
    --primary: #4a3728;
    --secondary: #8b6a4f;
    --accent: #6b3a2a;
    --gold: #c9a96e;
    --bg-deep: #faf8f5;
    --bg-dark: #f0ebe3;
    --bg-sidebar: #f5f0ea;
    --bg-card: #ffffff;
    --text: #1a1714;
    --text-muted: #7a6a5a;
    --border: rgba(74,55,40,0.15);
    --glow: 0 2px 12px rgba(74,55,40,0.12), 0 1px 4px rgba(74,55,40,0.08);
    --glow-red: 0 2px 8px rgba(107,58,42,0.2);
    --glow-purple: 0 2px 8px rgba(139,106,79,0.2);
    --font-display: 'Cormorant Garamond', serif;
    --font-body: 'Inter', sans-serif;
    --font-mono: 'DM Mono', monospace;
    --sidebar: 260px;
}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
body{background:var(--bg-deep);color:var(--text);font-family:var(--font-body);min-height:100vh;display:flex;align-items:center;justify-content:center;overflow:hidden;}
body::before{content:'';position:fixed;inset:0;background:radial-gradient(ellipse at 50% 50%,rgba(74,55,40,0.05) 0%,transparent 60%);}
body::after{content:'';position:fixed;inset:0;background:repeating-linear-gradient(0deg,transparent,transparent 40px,rgba(74,55,40,0.02) 40px,rgba(74,55,40,0.02) 41px),repeating-linear-gradient(90deg,transparent,transparent 40px,rgba(74,55,40,0.02) 40px,rgba(74,55,40,0.02) 41px);animation:grid-move 20s linear infinite;}
@keyframes grid-move{from{background-position:0 0,0 0;}to{background-position:0 80px,80px 0;}}
.login-wrap{position:relative;z-index:2;width:100%;max-width:440px;padding:20px;}
.login-logo{text-align:center;margin-bottom:40px;}
.login-logo a{font-family:var(--font-display);font-size:2rem;font-weight:900;color:var(--primary);text-decoration:none;letter-spacing:0.3em;text-shadow: none;}
.login-logo a span{color:var(--secondary);}
.login-logo p{font-family:var(--font-mono);font-size:0.7rem;color:var(--text-muted);letter-spacing:0.3em;margin-top:10px;text-transform:uppercase;}
.login-card{background:var(--bg-card);border:1px solid var(--border);border-radius:4px;padding:40px;position:relative;overflow:hidden;backdrop-filter:blur(20px);}
.login-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--primary),transparent);}
.login-title{font-family:var(--font-display);font-size:1.1rem;font-weight:700;letter-spacing:0.1em;margin-bottom:32px;color:var(--text);display:flex;align-items:center;gap:12px;}
.login-title::before{content:'//';color:var(--primary);}
.form-group{margin-bottom:24px;position:relative;}
.form-label{display:block;font-family:var(--font-mono);font-size:0.68rem;color:var(--text-muted);letter-spacing:0.2em;text-transform:uppercase;margin-bottom:10px;}
.form-input{width:100%;padding:14px 18px;background:rgba(74,55,40,0.03);border:1px solid rgba(74,55,40,0.12);border-radius:2px;color:var(--text);font-family:var(--font-body);font-size:1rem;outline:none;transition:border-color 0.3s,box-shadow 0.3s;}
.form-input:focus{border-color:var(--primary);box-shadow:0 0 0 2px rgba(74,55,40,0.08);}
.form-input::placeholder{color:rgba(107,139,164,0.4);}
.toggle-pw{position:absolute;right:14px;bottom:14px;cursor:pointer;color:var(--text-muted);font-size:0.9rem;transition:color 0.2s;}
.toggle-pw:hover{color:var(--primary);}
.login-btn{width:100%;padding:16px;background:linear-gradient(135deg,var(--primary),var(--accent));color:var(--bg-deep);font-family:var(--font-display);font-size:0.85rem;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;border:none;cursor:pointer;border-radius:2px;100%,0% 100%);transition:transform 0.2s,box-shadow 0.3s;margin-top:8px;}
.login-btn:hover{transform:translateY(-2px);box-shadow: none;}
.login-btn:disabled{opacity:0.6;transform:none;cursor:not-allowed;}
.login-footer{text-align:center;margin-top:24px;font-family:var(--font-mono);font-size:0.7rem;color:var(--text-muted);}
.login-footer a{color:var(--primary);text-decoration:none;}
.corner{position:absolute;width:20px;height:20px;}
.corner.tl{top:16px;left:16px;border-top:1px solid var(--primary);border-left:1px solid var(--primary);}
.corner.tr{top:16px;right:16px;border-top:1px solid var(--primary);border-right:1px solid var(--primary);}
.corner.bl{bottom:16px;left:16px;border-bottom:1px solid var(--primary);border-left:1px solid var(--primary);}
.corner.br{bottom:16px;right:16px;border-bottom:1px solid var(--primary);border-right:1px solid var(--primary);}

/* ─── Admin Light Theme ─── */
body { background: #f5f0ea !important; }
.sidebar { background: #ffffff !important; border-right: 1px solid rgba(74,55,40,0.1) !important; box-shadow: 2px 0 12px rgba(0,0,0,0.04) !important; }
.topbar { background: rgba(250,248,245,0.98) !important; border-bottom: 1px solid rgba(74,55,40,0.1) !important; }
.stat-card, .dash-card { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.1) !important; box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important; }
.stat-card::before { background: linear-gradient(90deg, transparent, #4a3728, transparent) !important; }
table { background: #ffffff !important; }
.table-wrap { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.1) !important; border-radius: 4px !important; }
th { background: #faf8f5 !important; border-bottom: 1px solid rgba(74,55,40,0.1) !important; }
tr:hover { background: #fafbff !important; }
.form-group input, .form-group textarea, .form-group select { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.18) !important; color: #1a1714 !important; }
.form-group input:focus, .form-group textarea:focus, .form-group select:focus { border-color: #4a3728 !important; box-shadow: 0 0 0 3px rgba(74,55,40,0.1) !important; }
.card, .panel { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.1) !important; }


/* ═══ Admin Interior Theme ═══ */
body { background: #f0ebe3 !important; color: #1a1714 !important; }
.sidebar { background: #1a1714 !important; border-right: none !important; }
.sidebar-logo a { color: #c9a96e !important; font-family: 'Cormorant Garamond', serif !important; text-shadow: none !important; }
.sidebar-link { color: rgba(250,248,245,0.6) !important; border-left: 2px solid transparent !important; font-family: 'Inter', sans-serif !important; font-size: 0.8rem !important; letter-spacing: 0.04em !important; }
.sidebar-link:hover, .sidebar-link.active { color: #faf8f5 !important; background: rgba(201,169,110,0.1) !important; border-left-color: #c9a96e !important; }
.topbar { background: rgba(250,248,245,0.98) !important; border-bottom: 1px solid rgba(74,55,40,0.1) !important; box-shadow: 0 1px 6px rgba(26,23,20,0.05) !important; }
.admin-avatar { background: linear-gradient(135deg, #4a3728, #8b6a4f) !important; border-radius: 0 !important; }
.topbar-btn { background: transparent !important; border: 1px solid rgba(74,55,40,0.25) !important; color: #4a3728 !important; border-radius: 0 !important; clip-path: none !important; }
.topbar-btn:hover { background: #4a3728 !important; color: #faf8f5 !important; }
.topbar-btn.add { background: #1a1714 !important; border-color: #1a1714 !important; color: #faf8f5 !important; }
.topbar-btn.add:hover { background: #4a3728 !important; border-color: #4a3728 !important; }
.stat-card, .dash-card { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.1) !important; border-radius: 0 !important; box-shadow: 0 2px 8px rgba(26,23,20,0.04) !important; }
.stat-card::before { background: linear-gradient(90deg, transparent, #8b6a4f, transparent) !important; }
.stat-val { color: #4a3728 !important; text-shadow: none !important; }
.dash-card-title { color: #8b6a4f !important; font-family: 'DM Mono', monospace !important; }
.dash-card-action:hover { color: #4a3728 !important; }
.table-wrap, table { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.1) !important; border-radius: 0 !important; }
th { background: #f5f0ea !important; color: #7a6a5a !important; border-bottom: 1px solid rgba(74,55,40,0.12) !important; font-family: 'DM Mono', monospace !important; font-size: 0.7rem !important; letter-spacing: 0.08em !important; }
tr:hover td { background: #faf8f5 !important; }
.action-btn { border: 1px solid rgba(74,55,40,0.2) !important; border-radius: 0 !important; clip-path: none !important; color: #7a6a5a !important; }
.action-btn:hover { border-color: #4a3728 !important; color: #4a3728 !important; }
.td-cat { color: #8b6a4f !important; }
.msg-dot { background: #8b6a4f !important; }
.msg-item.unread { border-left: 2px solid #8b6a4f !important; }
.qa-btn { border: 1px solid rgba(74,55,40,0.2) !important; border-radius: 0 !important; clip-path: none !important; }
.qa-btn:hover { border-color: #4a3728 !important; background: rgba(74,55,40,0.05) !important; transform: none !important; }
.qa-btn:hover .qa-label { color: #4a3728 !important; }
.activity-icon { color: #8b6a4f !important; }
input, textarea, select { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.18) !important; border-radius: 0 !important; color: #1a1714 !important; font-family: 'Inter', sans-serif !important; }
input:focus, textarea:focus, select:focus { border-color: #4a3728 !important; box-shadow: 0 0 0 3px rgba(74,55,40,0.08) !important; outline: none !important; }
.form-group label { color: #7a6a5a !important; font-family: 'DM Mono', monospace !important; }
.btn, button[type=submit] { background: #1a1714 !important; border: 1px solid #1a1714 !important; border-radius: 0 !important; clip-path: none !important; color: #faf8f5 !important; }
.btn:hover, button[type=submit]:hover { background: #4a3728 !important; border-color: #4a3728 !important; }
footer { background: #ede8e0 !important; border-top: 1px solid rgba(74,55,40,0.1) !important; }
::-webkit-scrollbar-track { background: #f0ebe3 !important; }
::-webkit-scrollbar-thumb { background: #8b6a4f !important; border-radius: 0 !important; }

</style>
</head>
<body>

<div class="login-wrap">
    <div class="login-logo">
        <a href="../index.php">ALI<span>.</span>AFZAL</a>
        <p>Admin Control Panel</p>
    </div>
    <div class="login-card">
        <div class="corner tl"></div>
        <div class="corner tr"></div>
        <div class="corner bl"></div>
        <div class="corner br"></div>

        <div class="login-title">Secure Access</div>

        <?php if ($error): ?>
        <div style="padding:12px 16px;background:rgba(201,169,110,0.08);border:1px solid rgba(255,0,110,0.3);border-radius:2px;font-family:var(--font-mono);font-size:0.75rem;color:#ff6b8a;margin-bottom:24px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" id="login-form">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-input" placeholder="admin username" autocomplete="username" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" id="pw-input" placeholder="••••••••••" autocomplete="current-password" required>
                <span class="toggle-pw" onclick="togglePw()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></span>
            </div>
            <button type="submit" class="login-btn" id="login-btn">
                Access Control Panel →
            </button>
        </form>

        <div class="login-footer">
            <a href="../index.php">← Back to Portfolio</a>
        </div>
    </div>
</div>

<script>
function togglePw() {
    const inp = document.getElementById('pw-input');
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
document.getElementById('login-form').addEventListener('submit', function() {
    document.getElementById('login-btn').disabled = true;
    document.getElementById('login-btn').textContent = 'Authenticating...';
});
</script>
</body>
</html>