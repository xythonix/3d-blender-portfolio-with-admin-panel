<?php  
require_once '../includes/config.php';
requireAdmin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings — Ali Afzal Admin</title>
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
body{background:var(--bg-deep);color:var(--text);font-family:var(--font-body);min-height:100vh;display:flex;}
::-webkit-scrollbar{width:4px;}::-webkit-scrollbar-thumb{background:rgba(74,55,40,0.2);}
.sidebar{width:var(--sidebar);background:var(--bg-sidebar);border-right:1px solid var(--border);position:fixed;top:0;left:0;height:100vh;display:flex;flex-direction:column;z-index:50;overflow-y:auto;}
.sidebar-logo{padding:28px 24px;border-bottom:1px solid var(--border);}
.sidebar-logo a{font-family:var(--font-display);font-size:1.2rem;font-weight:900;color:var(--primary);text-decoration:none;letter-spacing:0.2em;text-shadow: none;}
.sidebar-logo a span{color:var(--secondary);}
.sidebar-logo small{display:block;font-family:var(--font-mono);font-size:0.6rem;color:var(--text-muted);letter-spacing:0.2em;margin-top:6px;}
.sidebar-nav{flex:1;padding:20px 0;}
.nav-section-label{font-family:var(--font-mono);font-size:0.6rem;color:rgba(122,106,90,0.5);letter-spacing:0.3em;text-transform:uppercase;padding:12px 24px 6px;margin-top:8px;}
.sidebar-link{display:flex;align-items:center;gap:12px;padding:11px 24px;text-decoration:none;color:var(--text-muted);font-family:var(--font-mono);font-size:0.75rem;letter-spacing:0.05em;transition:all 0.25s;border-left:2px solid transparent;}
.sidebar-link:hover,.sidebar-link.active{color:var(--primary);background:rgba(74,55,40,0.04);border-left-color:var(--primary);}
.sidebar-link .icon{font-size:1rem;width:20px;text-align:center;flex-shrink:0;}
.sidebar-link .badge{margin-left:auto;background:var(--secondary);color:white;font-size:0.55rem;padding:2px 8px;border-radius: 0;}
.sidebar-footer{padding:20px 24px;border-top:1px solid var(--border);}
.logout-btn{width:100%;padding:10px;background:rgba(255,0,110,0.08);border:1px solid rgba(201,169,110,0.15);color:var(--secondary);font-family:var(--font-mono);font-size:0.7rem;cursor:pointer;border-radius:2px;}
.main{margin-left:var(--sidebar);flex:1;}
.topbar{padding:20px 40px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:rgba(250,248,245,0.97);backdrop-filter:blur(20px);position:sticky;top:0;z-index:40;}
.page-title{font-family:var(--font-display);font-size:1.2rem;font-weight:700;color:var(--text);}
.topbar-btn{padding:10px 24px;background:linear-gradient(135deg,var(--primary),var(--accent));color:var(--bg-deep);font-family:var(--font-mono);font-size:0.72rem;font-weight:700;letter-spacing:0.1em;border:none;cursor:pointer;border-radius:2px;100%,0% 100%);transition:box-shadow 0.3s;}
.topbar-btn:hover{box-shadow: none;}
.content{padding:40px;max-width:900px;}
.settings-section{background:var(--bg-card);border:1px solid var(--border);border-radius:4px;overflow:hidden;margin-bottom:24px;}
.section-header{padding:16px 24px;border-bottom:1px solid var(--border);font-family:var(--font-mono);font-size:0.78rem;color:var(--primary);letter-spacing:0.15em;text-transform:uppercase;}
.section-body{padding:28px;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;}
.form-group{margin-bottom:20px;}
.form-group.no-mb{margin-bottom:0;}
.form-label{display:block;font-family:var(--font-mono);font-size:0.68rem;color:var(--text-muted);letter-spacing:0.2em;text-transform:uppercase;margin-bottom:8px;}
.form-input,.form-textarea{width:100%;padding:12px 16px;background:rgba(74,55,40,0.03);border:1px solid rgba(74,55,40,0.12);border-radius:2px;color:var(--text);font-family:var(--font-body);font-size:1rem;outline:none;transition:border-color 0.3s;resize:vertical;}
.form-input:focus,.form-textarea:focus{border-color:var(--primary);box-shadow:0 0 0 2px rgba(74,55,40,0.06);}
.form-input::placeholder{color:rgba(107,139,164,0.4);}
.form-textarea{min-height:100px;}

/* Password change */
.pw-section{display:flex;flex-direction:column;gap:16px;}
.pw-hint{font-family:var(--font-mono);font-size:0.68rem;color:rgba(255,214,0,0.7);padding:12px 16px;background:rgba(255,214,0,0.06);border:1px solid rgba(255,214,0,0.2);border-radius:2px;}

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
<?php

try {
    $db = getDB();
    $settings = [];
    $rows = $db->query("SELECT key_name, value FROM settings")->fetchAll();
    foreach ($rows as $r) $settings[$r['key_name']] = $r['value'];
    $unreadMessages = $db->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();
} catch(Exception $e) { $settings = []; $unreadMessages = 0; }

function getSetting($settings, $key, $default = '') {
    return htmlspecialchars($settings[$key] ?? $default);
}
?>

<aside class="sidebar">
    <div class="sidebar-logo"><a href="../index.php">ALI<span>.</span>AFZAL</a><small>// ADMIN PANEL</small></div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="dashboard.php" class="sidebar-link"><span class="icon">◈</span> Dashboard</a>
        <a href="projects.php" class="sidebar-link"><span class="icon">⬡</span> Projects</a>
        <a href="messages.php" class="sidebar-link"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></span> Messages<?= $unreadMessages>0?'<span class="badge">'.$unreadMessages.'</span>':'' ?></a>
        <div class="nav-section-label">Content</div>
        <a href="add-project.php" class="sidebar-link"><span class="icon">+</span> Add Project</a>
        <a href="categories.php" class="sidebar-link"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg></span> Categories</a>
        <div class="nav-section-label">System</div>
        <a href="settings.php" class="sidebar-link active"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg></span> Settings</a>
        <a href="../index.php" class="sidebar-link" target="_blank"><span class="icon">↗</span> View Site</a>
    </nav>
    <div class="sidebar-footer"><button class="logout-btn" onclick="logout()" style="color:#ffffffa9;">Logout</button></div>
</aside>

<div class="main">
    <div class="topbar">
        <div class="page-title">Site Settings</div>
        <button class="topbar-btn" onclick="saveSettings()">Save All Settings</button>
    </div>
    <div class="content">

        <!-- Hero Section -->
        <!-- <div class="settings-section">
            <div class="section-header">// Hero Section</div>
            <div class="section-body">
                <div class="form-group">
                    <label class="form-label">Hero Headline</label>
                    <input type="text" class="form-input" name="hero_headline" value="<?= getSetting($settings,'hero_headline','Welcome to My 3D Universe') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Hero Sub-text</label>
                    <input type="text" class="form-input" name="hero_subtext" value="<?= getSetting($settings,'hero_subtext','Blender Artist • 3D Modeler • Visual Storyteller') ?>">
                </div>
                <div class="form-row">
                    <div class="form-group no-mb">
                        <label class="form-label">Years Experience</label>
                        <input type="number" class="form-input" name="years_experience" value="<?= getSetting($settings,'years_experience','5') ?>">
                    </div>
                    <div class="form-group no-mb">
                        <label class="form-label">Projects Completed</label>
                        <input type="text" class="form-input" name="projects_completed" value="<?= getSetting($settings,'projects_completed','50+') ?>">
                    </div>
                </div>
            </div>
        </div> -->

        <!-- About Section -->
        <!-- <div class="settings-section">
            <div class="section-header">// About / Bio</div>
            <div class="section-body">
                <div class="form-group">
                    <label class="form-label">Site Title</label>
                    <input type="text" class="form-input" name="site_title" value="<?= getSetting($settings,'site_title','Ali Afzal — 3D Artist') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Bio / About Text</label>
                    <textarea class="form-textarea" name="about_text" style="min-height:140px;"><?= getSetting($settings,'about_text') ?></textarea>
                </div>
            </div>
        </div> -->

        <!-- Contact Info -->
        <div class="settings-section">
            <div class="section-header">// Contact Information</div>
            <div class="section-body">
                <div class="form-row">
                    <div class="form-group no-mb">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" name="email" value="<?= getSetting($settings,'email') ?>">
                    </div>
                    <div class="form-group no-mb">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-input" name="phone" value="<?= getSetting($settings,'phone') ?>">
                    </div>
                </div>
                <div class="form-group" style="margin-top:20px;">
                    <label class="form-label">Location</label>
                    <input type="text" class="form-input" name="location" value="<?= getSetting($settings,'location','Pakistan') ?>">
                </div>
            </div>
        </div>

        <!-- Social Links -->
        <div class="settings-section">
            <div class="section-header">// Social Links</div>
            <div class="section-body">
                <div class="form-row">
                    <div class="form-group no-mb">
                        <label class="form-label">Instagram</label>
                        <input type="url" class="form-input" name="instagram" value="<?= getSetting($settings,'instagram') ?>" placeholder="https://instagram.com/...">
                    </div>
                    <div class="form-group no-mb">
                        <label class="form-label">ArtStation</label>
                        <input type="url" class="form-input" name="artstation" value="<?= getSetting($settings,'artstation') ?>" placeholder="https://artstation.com/...">
                    </div>
                </div>
                <div class="form-row" style="margin-top:20px;">
                    <div class="form-group no-mb">
                        <label class="form-label">LinkedIn</label>
                        <input type="url" class="form-input" name="linkedin" value="<?= getSetting($settings,'linkedin') ?>" placeholder="https://linkedin.com/in/...">
                    </div>
                    <div class="form-group no-mb">
                        <label class="form-label">YouTube</label>
                        <input type="url" class="form-input" name="youtube" value="<?= getSetting($settings,'youtube') ?>" placeholder="https://youtube.com/...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <!-- <div class="settings-section">
            <div class="section-header">// Change Admin Password</div>
            <div class="section-body">
                <div class="pw-hint"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> After changing the password, you will need to update the config.php file with the new hash.</div>
                <div class="form-row" style="margin-top:20px;">
                    <div class="form-group no-mb">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-input" id="new-pw" placeholder="Enter new password">
                    </div>
                    <div class="form-group no-mb">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-input" id="conf-pw" placeholder="Confirm new password">
                    </div>
                </div>
                <button onclick="changePassword()" style="margin-top:20px;padding:10px 24px;background:rgba(74,55,40,0.08);border:1px solid rgba(74,55,40,0.15);color:var(--primary);font-family:var(--font-mono);font-size:0.72rem;letter-spacing:0.1em;cursor:pointer;border-radius:2px;">Generate Hash →</button>
                <div id="pw-hash-result" style="margin-top:12px;font-family:var(--font-mono);font-size:0.7rem;color:var(--text-muted);"></div>
            </div>
        </div> -->

    </div>
</div>

<script>
function saveSettings() {
    const data = { action: 'save_settings' };
    document.querySelectorAll('[name]').forEach(el => {
        data[el.name] = el.value;
    });
    fetch('actions.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(data) })
    .then(r=>r.json()).then(d => {
        if (d.success) Swal.fire({ icon:'success', title:'Settings Saved!', timer:1500, showConfirmButton:false, background:'#f0ebe3', color:'#1a1714' });
        else Swal.fire({ icon:'error', title:'Error', text:d.message, background:'#f0ebe3', color:'#1a1714' });
    });
}
function changePassword() {
    const p1 = document.getElementById('new-pw').value;
    const p2 = document.getElementById('conf-pw').value;
    if (!p1) { Swal.fire({icon:'warning',title:'Enter a password',background:'#f0ebe3',color:'#1a1714'}); return; }
    if (p1 !== p2) { Swal.fire({icon:'error',title:"Passwords don't match",background:'#f0ebe3',color:'#1a1714'}); return; }
    // Simple display - real implementation would call server
    document.getElementById('pw-hash-result').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Update <code style="color:var(--primary)">ADMIN_PASS_HASH</code> in includes/config.php with the hash generated by: <br><code style="color:var(--gold)">password_hash("'+p1+'", PASSWORD_DEFAULT)</code>';
}
function logout(){ Swal.fire({title:'Logout?',icon:'question',showCancelButton:true,confirmButtonText:'Yes',confirmButtonColor:'#c9a96e',background:'#f0ebe3',color:'#1a1714'}).then(r=>{if(r.isConfirmed)window.location='logout.php';}); }
</script>
</body>
</html>
