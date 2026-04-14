<?php
require_once '../includes/config.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Project — Ali Afzal Admin</title>
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
.topbar-actions{display:flex;gap:12px;}
.topbar-btn{padding:10px 24px;background:linear-gradient(135deg,var(--primary),var(--accent));color:var(--bg-deep);font-family:var(--font-mono);font-size:0.72rem;font-weight:700;letter-spacing:0.1em;border:none;cursor:pointer;border-radius:2px;100%,0% 100%);transition:box-shadow 0.3s;}
.topbar-btn:hover{box-shadow: none;}
.topbar-btn.secondary{background:rgba(74,55,40,0.06);color:var(--primary);border:1px solid rgba(74,55,40,0.15);clip-path:none;}
.topbar-btn:disabled{opacity:0.5;cursor:not-allowed;}
.content{padding:40px;width:100%;}
.form-grid{display:grid;grid-template-columns:1fr 340px;gap:28px;align-items:start;}
.form-section{background:var(--bg-card);border:1px solid var(--border);border-radius:4px;overflow:hidden;margin-bottom:24px;position:relative;}
.form-section::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,var(--primary),transparent);}
.section-header{padding:14px 24px;border-bottom:1px solid var(--border);font-family:var(--font-mono);font-size:0.72rem;color:var(--primary);letter-spacing:0.15em;text-transform:uppercase;}
.section-header::before{content:'// ';}
.section-body{padding:24px;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
.form-group{margin-bottom:20px;}
.form-group:last-child{margin-bottom:0;}
.form-label{display:block;font-family:var(--font-mono);font-size:0.65rem;color:var(--text-muted);letter-spacing:0.2em;text-transform:uppercase;margin-bottom:8px;}
.required{color:var(--secondary);}
.form-input,.form-textarea,.form-select{width:100%;padding:11px 14px;background:rgba(74,55,40,0.03);border:1px solid rgba(74,55,40,0.12);border-radius:2px;color:var(--text);font-family:var(--font-body);font-size:1rem;outline:none;transition:border-color 0.3s,box-shadow 0.3s;resize:vertical;}
.form-input:focus,.form-textarea:focus,.form-select:focus{border-color:var(--primary);box-shadow:0 0 0 2px rgba(74,55,40,0.06);}
.form-input::placeholder,.form-textarea::placeholder{color:rgba(107,139,164,0.35);}
.form-select option{background:var(--bg-card);color:var(--text);}
.form-textarea{min-height:120px;}
.form-hint{font-family:var(--font-mono);font-size:0.62rem;color:rgba(107,139,164,0.6);margin-top:6px;line-height:1.5;}
.slug-preview{font-family:var(--font-mono);font-size:0.68rem;color:rgba(74,55,40,0.5);margin-top:6px;}
.toggle-row{display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid rgba(74,55,40,0.06);}
.toggle-row:last-child{border-bottom:none;padding-bottom:0;}
.toggle-row:first-child{padding-top:0;}
.toggle-label{font-family:var(--font-mono);font-size:0.72rem;color:var(--text-muted);}
.toggle-label small{display:block;font-size:0.6rem;color:rgba(107,139,164,0.4);margin-top:2px;}
.toggle{position:relative;width:44px;height:24px;flex-shrink:0;}
.toggle input{opacity:0;width:0;height:0;}
.toggle-slider{position:absolute;inset:0;background:rgba(74,55,40,0.08);border:1px solid rgba(74,55,40,0.15);border-radius:24px;cursor:pointer;transition:0.3s;}
.toggle-slider::before{content:'';position:absolute;width:16px;height:16px;left:3px;top:3px;background:var(--text-muted);border-radius:50%;transition:0.3s;}
.toggle input:checked+.toggle-slider{background:rgba(74,55,40,0.12);border-color:var(--primary);}
.toggle input:checked+.toggle-slider::before{transform:translateX(20px);background:var(--primary);}
.upload-zone{border:2px dashed rgba(74,55,40,0.15);border-radius:4px;padding:32px 20px;text-align:center;cursor:pointer;transition:all 0.3s;position:relative;overflow:hidden;}
.upload-zone:hover,.upload-zone.dragover{border-color:var(--primary);background:rgba(74,55,40,0.04);}
.upload-zone input[type="file"]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;}
.upload-icon{font-size:2.5rem;margin-bottom:12px;opacity:0.4;}
.upload-text{font-family:var(--font-mono);font-size:0.7rem;color:var(--text-muted);line-height:1.8;}
.upload-text strong{color:var(--primary);display:block;margin-bottom:4px;}
.image-previews{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:14px;}
.preview-item{position:relative;aspect-ratio:1;background:rgba(74,55,40,0.03);border:1px solid var(--border);border-radius:2px;overflow:hidden;}
.preview-item img{width:100%;height:100%;object-fit:cover;display:block;}
.preview-remove{position:absolute;top:4px;right:4px;width:22px;height:22px;background:rgba(255,0,110,0.85);color:white;border:none;border-radius:50%;cursor:pointer;font-size:0.75rem;display:flex;align-items:center;justify-content:center;}
.preview-primary{position:absolute;bottom:4px;left:4px;font-family:var(--font-mono);font-size:0.55rem;background:var(--primary);color:var(--bg-deep);padding:2px 6px;border-radius:2px;font-weight:700;}
.set-primary-btn{position:absolute;bottom:4px;right:4px;font-family:var(--font-mono);font-size:0.55rem;background:rgba(74,55,40,0.08);color:var(--primary);border:1px solid rgba(74,55,40,0.2);padding:2px 6px;border:none;border-radius:2px;cursor:pointer;}
.tags-wrap{display:flex;flex-wrap:wrap;gap:6px;padding:8px 10px;background:rgba(74,55,40,0.03);border:1px solid rgba(74,55,40,0.12);border-radius:2px;min-height:44px;cursor:text;transition:border-color 0.3s;}
.tags-wrap:focus-within{border-color:var(--primary);box-shadow:0 0 0 2px rgba(74,55,40,0.06);}
.tag-chip{display:flex;align-items:center;gap:5px;background:rgba(74,55,40,0.1);border:1px solid rgba(74,55,40,0.25);color:var(--primary);font-family:var(--font-mono);font-size:0.65rem;padding:3px 8px;border-radius:2px;}
.tag-chip button{background:none;border:none;color:inherit;cursor:pointer;font-size:0.8rem;line-height:1;padding:0;opacity:0.7;}
.tags-input{flex:1;min-width:80px;background:none;border:none;outline:none;color:var(--text);font-family:var(--font-body);font-size:0.95rem;}
.tags-input::placeholder{color:rgba(107,139,164,0.35);}
.status-wrap{position:relative;}
.status-dot{position:absolute;left:12px;top:50%;transform:translateY(-50%);width:7px;height:7px;border-radius:50%;background:var(--primary);z-index:1;pointer-events:none;}
.status-dot.draft{background:var(--text-muted);}
.status-wrap .form-select{padding-left:28px;}
.char-count{font-family:var(--font-mono);font-size:0.6rem;color:rgba(122,106,90,0.5);text-align:right;margin-top:4px;}
.char-count.warn{color:rgba(255,165,0,0.7);}
.char-count.over{color:rgba(255,0,110,0.8);}
.angle-row{display:grid;grid-template-columns:42px 1fr;gap:12px;align-items:center;margin-bottom:12px;}
.angle-row img{width:42px;height:42px;object-fit:cover;border-radius:2px;border:1px solid var(--border);}
.progress-wrap{margin-top:12px;display:none;}
.progress-track{background:rgba(74,55,40,0.08);border:1px solid rgba(74,55,40,0.12);border-radius:2px;height:6px;overflow:hidden;}
.progress-bar{height:100%;background:linear-gradient(90deg,var(--primary),var(--accent));width:0%;transition:width 0.2s;}
.progress-label{font-family:var(--font-mono);font-size:0.62rem;color:var(--text-muted);margin-top:6px;text-align:center;}
.model-upload-zone{border:2px dashed rgba(123,47,255,0.35);border-radius:4px;padding:28px 20px;text-align:center;cursor:pointer;transition:all 0.3s;position:relative;overflow:hidden;background:rgba(123,47,255,0.03);}
.model-upload-zone:hover,.model-upload-zone.dragover{border-color:var(--accent);background:rgba(123,47,255,0.07);}
.model-upload-zone input[type="file"]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;}
.model-upload-icon{font-size:2.2rem;margin-bottom:10px;opacity:0.5;}
.model-upload-text{font-family:var(--font-mono);font-size:0.7rem;color:var(--text-muted);line-height:1.8;}
.model-upload-text strong{color:var(--accent);display:block;margin-bottom:4px;}
.model-file-list{margin-top:14px;display:flex;flex-direction:column;gap:8px;}
.model-file-item{display:flex;align-items:center;gap:10px;padding:10px 12px;background:rgba(123,47,255,0.06);border:1px solid rgba(123,47,255,0.2);border-radius:3px;}
.model-file-icon{font-size:1.4rem;flex-shrink:0;opacity:0.8;}
.model-file-info{flex:1;min-width:0;}
.model-file-name{font-family:var(--font-mono);font-size:0.68rem;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.model-file-size{font-family:var(--font-mono);font-size:0.58rem;color:var(--text-muted);margin-top:2px;}
.model-file-ext{font-family:var(--font-mono);font-size:0.55rem;padding:2px 7px;background:rgba(123,47,255,0.25);color:var(--accent);border-radius:2px;flex-shrink:0;text-transform:uppercase;}
.model-file-remove{background:rgba(255,0,110,0.12);border:1px solid rgba(255,0,110,0.25);color:var(--secondary);width:22px;height:22px;border-radius:50%;cursor:pointer;font-size:0.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background 0.2s;}
.model-file-remove:hover{background:rgba(255,0,110,0.3);}
.model-hint{font-family:var(--font-mono);font-size:0.62rem;color:rgba(107,139,164,0.6);margin-top:8px;line-height:1.5;}

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
    $categories = $db->query("SELECT name FROM categories ORDER BY sort_order")->fetchAll();
    $unreadMessages = $db->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();
} catch(Exception $e) { $categories=[]; $unreadMessages=0; }
?>
<aside class="sidebar">
    <div class="sidebar-logo"><a href="../index.php">ALI<span>.</span>AFZAL</a><small>// ADMIN PANEL</small></div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="dashboard.php" class="sidebar-link"><span class="icon">◈</span> Dashboard</a>
        <a href="projects.php" class="sidebar-link"><span class="icon">⬡</span> Projects</a>
        <a href="messages.php" class="sidebar-link"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></span> Messages<?= $unreadMessages>0?'<span class="badge">'.$unreadMessages.'</span>':'' ?></a>
        <div class="nav-section-label">Content</div>
        <a href="add-project.php" class="sidebar-link active"><span class="icon">+</span> Add Project</a>
        <a href="categories.php" class="sidebar-link"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg></span> Categories</a>
        <div class="nav-section-label">System</div>
        <a href="settings.php" class="sidebar-link"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg></span> Settings</a>
        <a href="../index.php" class="sidebar-link" target="_blank"><span class="icon">↗</span> View Site</a>
    </nav>
    <div class="sidebar-footer"><button class="logout-btn" onclick="logout()" style="color:#ffffffa9;">Logout</button></div>
</aside>

<div class="main">
    <div class="topbar">
        <div class="page-title">Add New Project</div>
        <div class="topbar-actions">
            <button class="topbar-btn secondary" onclick="saveDraft()">Save Draft</button>
            <button class="topbar-btn" id="publish-btn" onclick="submitProject()">Publish Project →</button>
        </div>
    </div>
    <div class="content">
        <div class="form-grid">

            <!-- LEFT COLUMN -->
            <div>
                <div class="form-section">
                    <div class="section-header">Project Info</div>
                    <div class="section-body">
                        <div class="form-group">
                            <label class="form-label">Title <span class="required">*</span></label>
                            <input type="text" class="form-input" id="title" placeholder="e.g. Cyberpunk Street Scene" oninput="autoSlug(this.value)" maxlength="200">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Slug (URL)</label>
                            <input type="text" class="form-input" id="slug" placeholder="auto-generated-from-title" oninput="updateSlugPreview()">
                            <div class="slug-preview">yoursite.com/projects/<span id="slug-live">...</span></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Category <span class="required">*</span></label>
                                <select class="form-select" id="category">
                                    <option value="">— Select —</option>
                                    <?php foreach($categories as $c): ?>
                                    <option value="<?= htmlspecialchars($c['name']) ?>"><?= htmlspecialchars($c['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Year</label>
                                <input type="number" class="form-input" id="year" value="<?= date('Y') ?>" min="2000" max="<?= date('Y')+1 ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Client / For</label>
                                <input type="text" class="form-input" id="client" placeholder="Personal / Client name">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Software Used</label>
                                <input type="text" class="form-input" id="software" value="Blender" placeholder="Blender, ZBrush...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">Description</div>
                    <div class="section-body">
                        <div class="form-group">
                            <label class="form-label">Project Description</label>
                            <textarea class="form-textarea" id="description" placeholder="Describe the project, techniques, inspiration..." style="min-height:160px;" oninput="countChars(this,'desc-count',1000)" maxlength="1000"></textarea>
                            <div class="char-count" id="desc-count">0 / 1000</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">Tags</div>
                    <div class="section-body">
                        <div class="form-group">
                            <label class="form-label">Tags</label>
                            <div class="tags-wrap" id="tags-wrap" onclick="document.getElementById('tag-input').focus()">
                                <input type="text" class="tags-input" id="tag-input" placeholder="Type tag + Enter or comma...">
                            </div>
                            <input type="hidden" id="tags-hidden">
                            <div class="form-hint">Press Enter or , to add. Click × to remove. Max 15 tags.</div>
                        </div>
                    </div>
                </div>
                
                <!-- 3D MODEL UPLOAD -->
                <div class="form-section">
                    <div class="section-header">3D Model File</div>
                    <div class="section-body">
                        <div class="model-upload-zone" id="model-upload-zone">
                            <input type="file" id="model-input"
                                accept=".glb,.gltf,.fbx,.obj,.stl,.blend,.dae,.3ds,.ply,.abc,.usd,.usda,.usdc,.usdz,.x3d,.wrl">
                            <div class="model-upload-icon">⬡</div>
                            <div class="model-upload-text">
                                <strong>Click or drag 3D model here</strong>
                                GLB · GLTF · FBX · OBJ · STL · BLEND<br>
                                DAE · 3DS · PLY · ABC · USD · USDZ<br>
                                Max 200 MB
                            </div>
                        </div>
                        <div class="model-file-list" id="model-file-list"></div>
                        <div class="model-hint" id="model-hint"></div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div>
                <div class="form-section">
                    <div class="section-header">Publish Options</div>
                    <div class="section-body">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <div class="status-wrap">
                                <div class="status-dot" id="status-dot"></div>
                                <select class="form-select" id="status" onchange="updateStatusDot()">
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sort Order</label>
                            <input type="number" class="form-input" id="sort_order" value="0" min="0">
                            <div class="form-hint">Lower = appears first.</div>
                        </div>
                        <div class="toggle-row">
                            <div class="toggle-label">Featured Project<small>Show on homepage highlights</small></div>
                            <label class="toggle"><input type="checkbox" id="featured"><span class="toggle-slider"></span></label>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">Project Images</div>
                    <div class="section-body">
                        <div class="upload-zone" id="upload-zone">
                            <!--
                                IMPORTANT: input has NO onchange here.
                                We use a hidden input and JS to manage the
                                file list manually so drag+drop and click
                                both work and files accumulate correctly.
                            -->
                            <input type="file" id="image-input" accept="image/jpeg,image/png,image/webp,image/gif" multiple>
                            <div class="upload-icon">⬡</div>
                            <div class="upload-text">
                                <strong>Click or drag images here</strong>
                                JPG, PNG, WEBP, GIF — max 10 MB each<br>
                                First image = thumbnail
                            </div>
                        </div>
                        <div class="image-previews" id="image-previews"></div>
                        <div class="progress-wrap" id="progress-wrap">
                            <div class="progress-track"><div class="progress-bar" id="progress-bar"></div></div>
                            <div class="progress-label" id="progress-label">Uploading…</div>
                        </div>
                        <div class="form-hint" id="img-hint" style="margin-top:8px;"></div>
                    </div>
                </div>

                <div class="form-section" id="angle-section" style="display:none;">
                    <div class="section-header">View Angles</div>
                    <div class="section-body" id="angle-body"></div>
                </div>


            </div>
                                        
        </div><!-- /form-grid -->
    </div><!-- /content -->
</div><!-- /main -->

<script>
/* ================================================================
   TAGS
   ================================================================ */
const tags = [];
const tagInput = document.getElementById('tag-input');

tagInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        addTag(tagInput.value.trim().replace(/,/g, ''));
    } else if (e.key === 'Backspace' && !tagInput.value && tags.length) {
        removeTag(tags.length - 1);
    }
});

function addTag(v) {
    v = v.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
    if (!v || tags.includes(v) || tags.length >= 15) return;
    tags.push(v);
    const chip = document.createElement('div');
    chip.className = 'tag-chip';
    chip.innerHTML = v + '<button type="button" onclick="removeTagByName(\'' + v + '\')">×</button>';
    document.getElementById('tags-wrap').insertBefore(chip, tagInput);
    tagInput.value = '';
    syncTags();
}
function removeTagByName(v) { removeTag(tags.indexOf(v)); }
function removeTag(i) {
    if (i < 0) return;
    tags.splice(i, 1);
    document.querySelectorAll('.tag-chip')[i]?.remove();
    syncTags();
}
function syncTags() { document.getElementById('tags-hidden').value = tags.join(','); }

/* ================================================================
   SLUG
   ================================================================ */
function autoSlug(t) {
    const s = t.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    document.getElementById('slug').value = s;
    updateSlugPreview();
}
function updateSlugPreview() {
    document.getElementById('slug-live').textContent = document.getElementById('slug').value || '...';
}

/* ================================================================
   MISC UI
   ================================================================ */
function countChars(el, id, max) {
    const n = el.value.length;
    const e2 = document.getElementById(id);
    e2.textContent = n + ' / ' + max;
    e2.className = 'char-count' + (n > max ? ' over' : n > max * 0.85 ? ' warn' : '');
}
function updateStatusDot() {
    document.getElementById('status-dot').className =
        'status-dot' + (document.getElementById('status').value === 'draft' ? ' draft' : '');
}

/* ================================================================
   IMAGE HANDLING
   The key fix: we maintain our own `imageFiles` array of File objects.
   We NEVER rely on the <input> element's .files property at submit time
   because resetting previews clears it. Instead we append each File
   directly to FormData at submit time.
   ================================================================ */
let imageFiles  = [];   // array of { file: File, angle: string }
let primaryIndex = 0;

const ANGLES = ['', 'front', 'back', 'left', 'right', 'top', 'perspective', 'wireframe', 'clay render'];

// Wire up the file input
document.getElementById('image-input').addEventListener('change', function () {
    addFiles(this.files);
    // Reset input so the same file can be re-selected if removed
    this.value = '';
});

// Drag and drop
const zone = document.getElementById('upload-zone');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('dragover');
    addFiles(e.dataTransfer.files);
});

function addFiles(fileList) {
    Array.from(fileList).forEach(f => {
        if (imageFiles.length >= 20) return;
        // Client-side type check
        if (!f.type.startsWith('image/')) return;
        // Client-side size check (10 MB)
        if (f.size > 10 * 1024 * 1024) {
            Swal.fire({ icon: 'warning', title: 'File too large', text: f.name + ' exceeds 10 MB.', background: '#f0ebe3', color: '#1a1714' });
            return;
        }
        imageFiles.push({ file: f, angle: '' });
    });
    renderPreviews();
}

function removeImage(i) {
    imageFiles.splice(i, 1);
    if (primaryIndex >= imageFiles.length) primaryIndex = Math.max(0, imageFiles.length - 1);
    renderPreviews();
}

function setPrimary(i) {
    primaryIndex = i;
    renderPreviews();
}

function renderPreviews() {
    const container   = document.getElementById('image-previews');
    const angleSection = document.getElementById('angle-section');
    const angleBody   = document.getElementById('angle-body');
    const hint        = document.getElementById('img-hint');

    container.innerHTML = '';

    if (!imageFiles.length) {
        hint.textContent = '';
        angleSection.style.display = 'none';
        return;
    }

    hint.textContent = imageFiles.length + ' image' + (imageFiles.length > 1 ? 's' : '') + ' queued. First = thumbnail.';

    imageFiles.forEach((item, idx) => {
        const url  = URL.createObjectURL(item.file);
        const div  = document.createElement('div');
        div.className = 'preview-item';

        const isPrimary = (idx === primaryIndex);
        div.innerHTML =
            `<img src="${url}" alt="">` +
            `<button class="preview-remove" onclick="removeImage(${idx})">×</button>` +
            (isPrimary
                ? `<span class="preview-primary">PRIMARY</span>`
                : `<button class="set-primary-btn" onclick="setPrimary(${idx})">Set Primary</button>`
            );
        container.appendChild(div);
    });

    // View angle selectors
    angleSection.style.display = 'block';
    angleBody.innerHTML = '';
    imageFiles.forEach((item, idx) => {
        const row = document.createElement('div');
        row.className = 'angle-row';

        const thumb = document.createElement('img');
        thumb.src = URL.createObjectURL(item.file);

        const sel = document.createElement('select');
        sel.className = 'form-select';
        sel.style.fontSize = '0.85rem';
        sel.innerHTML = ANGLES.map(a =>
            `<option value="${a}"${item.angle === a ? ' selected' : ''}>${a || '— angle —'}</option>`
        ).join('');
        sel.addEventListener('change', () => { imageFiles[idx].angle = sel.value; });

        row.appendChild(thumb);
        row.appendChild(sel);
        angleBody.appendChild(row);
    });
}

/* ================================================================
   3D MODEL HANDLING
   ================================================================ */
const MODEL_EXTENSIONS = ['glb','gltf','fbx','obj','stl','blend','dae','3ds','ply','abc','usd','usda','usdc','usdz','x3d','wrl'];
const MODEL_ICONS = { glb:'GLB',gltf:'GLTF',fbx:'FBX',obj:'OBJ',stl:'STL',blend:'BLD',dae:'DAE','3ds':'3DS',ply:'PLY',abc:'ABC',usd:'USD',usda:'USDA',usdc:'USDC',usdz:'USDZ',x3d:'X3D',wrl:'WRL' };
let modelFile = null;
const MODEL_MAX = 200 * 1024 * 1024; // 200 MB

const modelInput = document.getElementById('model-input');
const modelZone  = document.getElementById('model-upload-zone');

modelInput.addEventListener('change', function () {
    if (this.files[0]) setModelFile(this.files[0]);
    this.value = '';
});

modelZone.addEventListener('dragover', e => { e.preventDefault(); modelZone.classList.add('dragover'); });
modelZone.addEventListener('dragleave', () => modelZone.classList.remove('dragover'));
modelZone.addEventListener('drop', e => {
    e.preventDefault();
    modelZone.classList.remove('dragover');
    if (e.dataTransfer.files[0]) setModelFile(e.dataTransfer.files[0]);
});

function setModelFile(f) {
    const ext = f.name.split('.').pop().toLowerCase();
    if (!MODEL_EXTENSIONS.includes(ext)) {
        Swal.fire({ icon: 'warning', title: 'Unsupported format',
            text: 'Allowed: ' + MODEL_EXTENSIONS.join(', ').toUpperCase(),
            background: '#f0ebe3', color: '#1a1714' });
        return;
    }
    if (f.size > MODEL_MAX) {
        Swal.fire({ icon: 'warning', title: 'File too large',
            text: f.name + ' exceeds 200 MB.',
            background: '#f0ebe3', color: '#1a1714' });
        return;
    }
    modelFile = f;
    renderModelPreview();
}

function removeModelFile() {
    modelFile = null;
    renderModelPreview();
}

function formatBytes(b) {
    if (b < 1024) return b + ' B';
    if (b < 1048576) return (b/1024).toFixed(1) + ' KB';
    return (b/1048576).toFixed(1) + ' MB';
}

function renderModelPreview() {
    const list = document.getElementById('model-file-list');
    const hint = document.getElementById('model-hint');
    list.innerHTML = '';

    if (!modelFile) {
        hint.textContent = '';
        return;
    }

    const ext  = modelFile.name.split('.').pop().toLowerCase();
    const icon = MODEL_ICONS[ext] || ext.toUpperCase();

    const item = document.createElement('div');
    item.className = 'model-file-item';
    item.innerHTML =
        `<span class="model-file-icon">${icon}</span>` +
        `<div class="model-file-info">` +
            `<div class="model-file-name">${modelFile.name}</div>` +
            `<div class="model-file-size">${formatBytes(modelFile.size)}</div>` +
        `</div>` +
        `<span class="model-file-ext">.${ext}</span>` +
        `<button class="model-file-remove" onclick="removeModelFile()">×</button>`;
    list.appendChild(item);
    hint.textContent = '✔ 3D model queued for upload.';
}

/* ================================================================
   SUBMIT  —  THE CRITICAL FIX
   Must use FormData + XMLHttpRequest (NOT JSON.stringify, NOT fetch
   with Content-Type: application/json) so that File objects are
   transmitted as multipart/form-data and PHP sees $_FILES['images'].
   ================================================================ */
function validate() {
    if (!document.getElementById('title').value.trim()) {
        Swal.fire({ icon: 'warning', title: 'Title required', background: '#f0ebe3', color: '#1a1714' });
        return false;
    }
    if (!document.getElementById('category').value) {
        Swal.fire({ icon: 'warning', title: 'Category required', background: '#f0ebe3', color: '#1a1714' });
        return false;
    }
    return true;
}

function submitProject() { if (validate()) sendProject('published'); }
function saveDraft()      { if (validate()) sendProject('draft'); }

function sendProject(statusOverride) {
    const btn = document.getElementById('publish-btn');
    btn.disabled    = true;
    btn.textContent = 'Saving…';

    // ── Build FormData — this is what carries the files ──────
    const fd = new FormData();
    fd.append('action',        'create_project');
    fd.append('title',         document.getElementById('title').value.trim());
    fd.append('slug',          document.getElementById('slug').value.trim());
    fd.append('category',      document.getElementById('category').value);
    fd.append('year',          document.getElementById('year').value);
    fd.append('client',        document.getElementById('client').value.trim());
    fd.append('software',      document.getElementById('software').value.trim());
    fd.append('description',   document.getElementById('description').value.trim());
    fd.append('tags',          document.getElementById('tags-hidden').value);
    fd.append('status',        statusOverride);
    fd.append('featured',      document.getElementById('featured').checked ? '1' : '0');
    fd.append('sort_order',    document.getElementById('sort_order').value);
    fd.append('primary_index', String(primaryIndex));

    // ── Append every File object + its angle ─────────────────
    // PHP will receive these as $_FILES['images'] (array)
    // and $_POST['angles'][0], $_POST['angles'][1], …
    imageFiles.forEach((item, idx) => {
        fd.append('images[]',            item.file);          // actual File binary
        fd.append('angles[' + idx + ']', item.angle || '');   // corresponding angle
    });

    // ── Append 3D model file if present ──────────────────────
    if (modelFile) {
        fd.append('model_3d', modelFile);
    }

    // ── Progress bar ──────────────────────────────────────────
    const progressWrap = document.getElementById('progress-wrap');
    const progressBar  = document.getElementById('progress-bar');
    const progressLbl  = document.getElementById('progress-label');

    if (imageFiles.length > 0) {
        progressWrap.style.display = 'block';
        progressBar.style.width    = '0%';
        progressLbl.textContent    = 'Uploading 0%…';
    }

    // ── XHR — do NOT set Content-Type header manually ─────────
    // The browser sets it automatically to multipart/form-data
    // with the correct boundary. If you set it manually it breaks.
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'actions.php');

    xhr.upload.addEventListener('progress', e => {
        if (!e.lengthComputable) return;
        const pct = Math.round((e.loaded / e.total) * 100);
        progressBar.style.width = pct + '%';
        progressLbl.textContent = 'Uploading ' + pct + '%…';
    });

    xhr.addEventListener('load', () => {
        btn.disabled    = false;
        btn.textContent = 'Publish Project →';
        progressWrap.style.display = 'none';
        progressBar.style.width    = '0%';

        let response;
        try {
            response = JSON.parse(xhr.responseText);
        } catch (e) {
            Swal.fire({
                icon: 'error', title: 'Server Error',
                html: '<pre style="font-size:0.75rem;text-align:left;overflow:auto;max-height:200px;">' +
                      xhr.responseText.replace(/</g,'&lt;') + '</pre>',
                background: '#f0ebe3', color: '#1a1714'
            });
            return;
        }

        if (response.success) {
            let warningHtml = '';
            if (response.image_warnings && response.image_warnings.length) {
                warningHtml = '<br><small style="color:#b07800;"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Image warnings:<br>' +
                    response.image_warnings.join('<br>') + '</small>';
            }
            Swal.fire({
                icon: 'success',
                title: statusOverride === 'draft' ? 'Draft Saved!' : 'Project Published!',
                html: `<span style="font-family:monospace;font-size:.85rem;color:#7a6a5a">
                         ID #${response.id} · <strong style="color:#4a3728">${response.slug}</strong>
                       </span>${warningHtml}`,
                showDenyButton:     true,
                confirmButtonText:  'Go to Projects',
                denyButtonText:     'Add Another',
                background:         '#f0ebe3',
                color:              '#1a1714',
                confirmButtonColor: '#4a3728'
            }).then(r => {
                if (r.isConfirmed) window.location = 'projects.php';
                else location.reload();
            });
        } else {
            Swal.fire({ icon: 'error', title: 'Error', text: response.message, background: '#f0ebe3', color: '#1a1714' });
        }
    });

    xhr.addEventListener('error', () => {
        btn.disabled    = false;
        btn.textContent = 'Publish Project →';
        progressWrap.style.display = 'none';
        Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not reach server.', background: '#f0ebe3', color: '#1a1714' });
    });

    // Send — no Content-Type header set, browser handles it
    xhr.send(fd);
}

function logout() {
    Swal.fire({
        title: 'Logout?', icon: 'question', showCancelButton: true,
        confirmButtonText: 'Yes', confirmButtonColor: '#c9a96e',
        background: '#f0ebe3', color: '#1a1714'
    }).then(r => { if (r.isConfirmed) window.location = 'logout.php'; });
}
</script>
</body>
</html>