<?php  
require_once '../includes/config.php';
requireAdmin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — Ali Afzal</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
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
html{scroll-behavior:smooth;}
body{background:var(--bg-deep);color:var(--text);font-family:var(--font-body);min-height:100vh;display:flex;}
::-webkit-scrollbar{width:4px;}::-webkit-scrollbar-thumb{background:rgba(74,55,40,0.2);}

/* ─── SIDEBAR ─── */
.sidebar{width:var(--sidebar);background:var(--bg-sidebar);border-right:1px solid var(--border);position:fixed;top:0;left:0;height:100vh;display:flex;flex-direction:column;z-index:50;overflow-y:auto;}
.sidebar-logo{padding:28px 24px;border-bottom:1px solid var(--border);}
.sidebar-logo a{font-family:var(--font-display);font-size:1.2rem;font-weight:900;color:var(--primary);text-decoration:none;letter-spacing:0.2em;text-shadow: none;}
.sidebar-logo a span{color:var(--secondary);}
.sidebar-logo small{display:block;font-family:var(--font-mono);font-size:0.6rem;color:var(--text-muted);letter-spacing:0.2em;margin-top:6px;}

.sidebar-nav{flex:1;padding:20px 0;}
.nav-section-label{font-family:var(--font-mono);font-size:0.6rem;color:rgba(122,106,90,0.5);letter-spacing:0.3em;text-transform:uppercase;padding:12px 24px 6px;margin-top:8px;}
.sidebar-link{display:flex;align-items:center;gap:12px;padding:11px 24px;text-decoration:none;color:var(--text-muted);font-family:var(--font-mono);font-size:0.75rem;letter-spacing:0.05em;transition:all 0.25s;border-left:2px solid transparent;position:relative;}
.sidebar-link:hover,.sidebar-link.active{color:var(--primary);background:rgba(74,55,40,0.04);border-left-color:var(--primary);}
.sidebar-link .icon{font-size:1rem;width:20px;text-align:center;flex-shrink:0;}
.sidebar-link .badge{margin-left:auto;background:var(--secondary);color:white;font-size:0.55rem;padding:2px 8px;border-radius: 0;font-family:var(--font-mono);}

.sidebar-footer{padding:20px 24px;border-top:1px solid var(--border);}
.admin-info{display:flex;align-items:center;gap:12px;margin-bottom:16px;}
.admin-avatar{width:36px;height:36px;background:linear-gradient(135deg,var(--primary),var(--accent));border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:0.8rem;font-weight:900;color:var(--bg-deep);}
.admin-name{font-family:var(--font-mono);font-size:0.72rem;color:var(--text);}
.admin-role{font-size:0.65rem;color:var(--text-muted);}
.logout-btn{width:100%;padding:10px;background:rgba(255,0,110,0.08);border:1px solid rgba(201,169,110,0.15);color:var(--secondary);font-family:var(--font-mono);font-size:0.7rem;letter-spacing:0.1em;cursor:pointer;border-radius:2px;transition:all 0.25s;}
.logout-btn:hover{background:rgba(255,0,110,0.15);border-color:var(--secondary);}

/* ─── MAIN ─── */
.main{margin-left:var(--sidebar);flex:1;min-height:100vh;display:flex;flex-direction:column;}

/* Top bar */
.topbar{padding:20px 40px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:rgba(250,248,245,0.97);backdrop-filter:blur(20px);position:sticky;top:0;z-index:40;}
.page-title{font-family:var(--font-display);font-size:1.2rem;font-weight:700;color:var(--text);letter-spacing:0.08em;}
.topbar-right{display:flex;align-items:center;gap:16px;}
.topbar-btn{padding:8px 20px;background:rgba(74,55,40,0.06);border:1px solid rgba(74,55,40,0.15);color:var(--primary);font-family:var(--font-mono);font-size:0.72rem;letter-spacing:0.1em;cursor:pointer;border-radius:2px;text-decoration:none;transition:all 0.25s;100%,0% 100%);}
.topbar-btn:hover{background:rgba(74,55,40,0.12);box-shadow:0 0 10px rgba(74,55,40,0.15);}
.topbar-btn.add{background:linear-gradient(135deg,var(--primary),var(--accent));color:var(--bg-deep);font-weight:700;}

/* Content */
.content{padding:40px;flex:1;}

/* Stats Cards */
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:40px;}
.stat-card{background:var(--bg-card);border:1px solid var(--border);border-radius:4px;padding:24px;position:relative;overflow:hidden;transition:border-color 0.3s,transform 0.3s;}
.stat-card:hover{border-color:rgba(74,55,40,0.2);transform:translateY(-2px);}
.stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,var(--primary),transparent);}
.stat-icon{font-size:2rem;margin-bottom:16px;opacity:0.7;}
.stat-val{font-family:var(--font-display);font-size:2.2rem;font-weight:900;color:var(--primary);text-shadow: none;line-height:1;}
.stat-label{font-family:var(--font-mono);font-size:0.7rem;color:var(--text-muted);letter-spacing:0.15em;text-transform:uppercase;margin-top:8px;}
.stat-change{font-family:var(--font-mono);font-size:0.65rem;margin-top:10px;color:var(--text-muted);}
.stat-change.up{color:#1a6e3c;}
.stat-change.down{color:var(--secondary);}

/* Dashboard sections */
.dash-grid{display:grid;grid-template-columns:1.5fr 1fr;gap:24px;}
.dash-card{background:var(--bg-card);border:1px solid var(--border);border-radius:4px;overflow:hidden;}
.dash-card-header{padding:16px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
.dash-card-title{font-family:var(--font-mono);font-size:0.8rem;color:var(--primary);letter-spacing:0.15em;text-transform:uppercase;}
.dash-card-action{font-family:var(--font-mono);font-size:0.68rem;color:var(--text-muted);text-decoration:none;transition:color 0.2s;}
.dash-card-action:hover{color:var(--primary);}

/* Recent projects table */
.data-table{width:100%;border-collapse:collapse;}
.data-table th{padding:12px 16px;font-family:var(--font-mono);font-size:0.65rem;color:var(--text-muted);letter-spacing:0.2em;text-transform:uppercase;border-bottom:1px solid var(--border);text-align:left;}
.data-table td{padding:14px 16px;border-bottom:1px solid rgba(74,55,40,0.05);font-size:0.92rem;vertical-align:middle;}
.data-table tr:hover td{background:rgba(74,55,40,0.02);}
.data-table tr:last-child td{border-bottom:none;}
.td-title{font-weight:600;color:var(--text);}
.td-title a{text-decoration:none;color:inherit;transition:color 0.2s;}
.td-title a:hover{color:var(--primary);}
.td-cat{font-family:var(--font-mono);font-size:0.68rem;color:var(--primary);}
.td-date{font-family:var(--font-mono);font-size:0.68rem;color:var(--text-muted);}
.td-views{font-family:var(--font-mono);font-size:0.75rem;color:var(--text-muted);}
.status-badge{display:inline-block;padding:3px 10px;font-family:var(--font-mono);font-size:0.62rem;letter-spacing:0.08em;text-transform:uppercase;border-radius: 0;}
.status-badge.published{background:rgba(26,110,60,0.1);border:1px solid rgba(26,110,60,0.3);color:#1a6e3c;}
.status-badge.draft{background:rgba(255,214,0,0.1);border:1px solid rgba(255,214,0,0.3);color:var(--gold);}
.td-actions{display:flex;gap:6px;align-items:center;}
.action-btn{padding:5px 10px;background:transparent;border:1px solid rgba(74,55,40,0.12);color:var(--text-muted);font-family:var(--font-mono);font-size:0.62rem;cursor:pointer;border-radius:2px;text-decoration:none;transition:all 0.2s;white-space:nowrap;}
.action-btn:hover{border-color:var(--primary);color:var(--primary);}
.action-btn.delete:hover{border-color:var(--secondary);color:var(--secondary);}

/* Messages list */
.msg-list{max-height:400px;overflow-y:auto;}
.msg-item{padding:16px 24px;border-bottom:1px solid rgba(74,55,40,0.05);cursor:pointer;transition:background 0.2s;display:flex;gap:14px;align-items:flex-start;}
.msg-item:hover{background:rgba(74,55,40,0.03);}
.msg-item.unread{border-left:2px solid var(--primary);}
.msg-item.starred{border-left:2px solid var(--gold);}
.msg-dot{width:8px;height:8px;background:var(--primary);border-radius:50%;flex-shrink:0;margin-top:5px;}
.msg-dot.read{background:transparent;border:1px solid var(--text-muted);}
.msg-body{flex:1;min-width:0;}
.msg-from{font-weight:600;color:var(--text);font-size:0.92rem;}
.msg-subject{font-size:0.85rem;color:var(--text-muted);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.msg-date{font-family:var(--font-mono);font-size:0.65rem;color:var(--text-muted);white-space:nowrap;}

/* Quick actions */
.quick-actions{display:grid;grid-template-columns:1fr 1fr;gap:12px;padding:20px 24px;}
.qa-btn{padding:16px;background:rgba(74,55,40,0.04);border:1px solid rgba(74,55,40,0.12);border-radius:4px;text-align:center;text-decoration:none;transition:all 0.3s;display:block;}
.qa-btn:hover{border-color:var(--primary);background:rgba(74,55,40,0.08);transform:translateY(-2px);}
.qa-icon{font-size:1.5rem;display:block;margin-bottom:8px;}
.qa-label{font-family:var(--font-mono);font-size:0.7rem;color:var(--text-muted);letter-spacing:0.1em;text-transform:uppercase;}
.qa-btn:hover .qa-label{color:var(--primary);}

/* Activity log */
.activity-list{padding:0 24px 16px;}
.activity-item{padding:12px 0;border-bottom:1px solid rgba(74,55,40,0.04);display:flex;gap:14px;align-items:center;font-size:0.88rem;}
.activity-item:last-child{border-bottom:none;}
.activity-icon{color:var(--primary);font-size:1rem;flex-shrink:0;}
.activity-time{font-family:var(--font-mono);font-size:0.65rem;color:var(--text-muted);white-space:nowrap;}

@media(max-width:1200px){.stats-grid{grid-template-columns:repeat(2,1fr);}.dash-grid{grid-template-columns:1fr;}}
@media(max-width:768px){.sidebar{transform:translateX(-100%);}.main{margin-left:0;}.content{padding:20px;}}

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
    $totalProjects = $db->query("SELECT COUNT(*) FROM projects WHERE status='published'")->fetchColumn();
    $totalMessages = $db->query("SELECT COUNT(*) FROM messages")->fetchColumn();
    $unreadMessages = $db->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();
    $totalViews = $db->query("SELECT SUM(view_count) FROM projects")->fetchColumn() ?: 0;
    $recentProjects = $db->query("SELECT p.*, pi.filename as tf FROM projects p LEFT JOIN project_images pi ON p.id=pi.project_id AND pi.is_primary=1 ORDER BY p.created_at DESC LIMIT 8")->fetchAll();
    $recentMessages = $db->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 8")->fetchAll();
    $activityLog    = $db->query("SELECT * FROM activity_log ORDER BY created_at DESC LIMIT 6")->fetchAll();
} catch(Exception $e) {
    $totalProjects = $totalMessages = $unreadMessages = $totalViews = 0;
    $recentProjects = $recentMessages = $activityLog = [];
}
?>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <a href="../index.php">ALI<span>.</span>AFZAL</a>
        <small>// ADMIN PANEL</small>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="dashboard.php" class="sidebar-link active"><span class="icon">◈</span> Dashboard</a>
        <a href="projects.php" class="sidebar-link"><span class="icon">⬡</span> Projects</a>
        <a href="messages.php" class="sidebar-link">
            <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></span> Messages
            <?php if ($unreadMessages > 0): ?><span class="badge"><?= $unreadMessages ?></span><?php endif; ?>
        </a>

        <div class="nav-section-label">Content</div>
        <a href="add-project.php" class="sidebar-link"><span class="icon">+</span> Add Project</a>
        <a href="categories.php" class="sidebar-link"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg></span> Categories</a>

        <div class="nav-section-label">System</div>
        <a href="settings.php" class="sidebar-link"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg></span> Settings</a>
        <a href="../index.php" class="sidebar-link" target="_blank"><span class="icon">↗</span> View Site</a>
    </nav>
    <div class="sidebar-footer">
        <!-- <div class="admin-info">
            <div class="admin-avatar">AA</div>
            <div>
                <div class="admin-name">Ali Afzal</div>
                <div class="admin-role">Administrator</div>
            </div>
        </div> -->
        <button class="logout-btn" onclick="logout()" style="color:#ffffffa9 !important;">Logout</button>
    </div>
</aside>

<!-- MAIN -->
<div class="main">
    <div class="topbar">
        <div class="page-title">Dashboard</div>
        <div class="topbar-right">
            <span style="font-family:var(--font-mono);font-size:0.72rem;color:var(--text-muted);"><?= date('D, M j Y') ?></span>
            <a href="add-project.php" class="topbar-btn add">+ New Project</a>
        </div>
    </div>

    <div class="content">
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">⬡</div>
                <div class="stat-val"><?= $totalProjects ?></div>
                <div class="stat-label">Published Projects</div>
                <div class="stat-change up">▲ Portfolio Live</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="opacity:0.7"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></div>
                <div class="stat-val"><?= $totalMessages ?></div>
                <div class="stat-label">Total Messages</div>
                <?php if ($unreadMessages > 0): ?>
                <div class="stat-change" style="color:var(--secondary);"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> <?= $unreadMessages ?> unread</div>
                <?php endif; ?>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="opacity:0.7"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></div>
                <div class="stat-val"><?= number_format($totalViews) ?></div>
                <div class="stat-label">Total Views</div>
                <div class="stat-change up">▲ Growing</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="opacity:0.7"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>
                <div class="stat-val"><?= $db->query("SELECT COUNT(*) FROM projects WHERE featured=1")->fetchColumn() ?? 0 ?></div>
                <div class="stat-label">Featured Projects</div>
            </div>
        </div>

        <!-- Dashboard Grid -->
        <div class="dash-grid">
            <!-- Recent Projects -->
            <div class="dash-card">
                <div class="dash-card-header">
                    <span class="dash-card-title">// Recent Projects</span>
                    <a href="projects.php" class="dash-card-action">View All →</a>
                </div>
                <table class="data-table">
                    <thead><tr>
                        <th>Project</th><th>Category</th><th>Status</th><th>Views</th><th>Actions</th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($recentProjects as $p): ?>
                    <tr>
                        <td class="td-title"><a href="../project.php?slug=<?= htmlspecialchars($p['slug']) ?>"><?= htmlspecialchars($p['title']) ?></a></td>
                        <td class="td-cat"><?= htmlspecialchars($p['category']) ?></td>
                        <td><span class="status-badge <?= $p['status'] ?>"><?= $p['status'] ?></span></td>
                        <td class="td-views"><?= number_format($p['view_count']) ?></td>
                        <td class="td-actions">
                            <a href="edit-project.php?id=<?= $p['id'] ?>" class="action-btn">Edit</a>
                            <button onclick="deleteProject(<?= $p['id'] ?>)" class="action-btn delete">Del</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recentProjects)): ?>
                    <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);font-family:var(--font-mono);font-size:0.75rem;">No projects yet. <a href="add-project.php" style="color:var(--primary);">Add your first →</a></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Right column -->
            <div style="display:flex;flex-direction:column;gap:24px;">
                <!-- Quick Actions -->
                <div class="dash-card" >
                    <div class="dash-card-header">
                        <span class="dash-card-title">// Quick Actions</span>
                    </div>
                    <div class="quick-actions">
                        <a href="add-project.php" class="qa-btn"><span class="qa-icon">+</span><span class="qa-label">Add Project</span></a>
                        <a href="messages.php" class="qa-btn"><span class="qa-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></span><span class="qa-label">Messages<?= $unreadMessages > 0 ? " ($unreadMessages)" : '' ?></span></a>
                        <a href="settings.php" class="qa-btn"><span class="qa-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg></span><span class="qa-label">Settings</span></a>
                        <a href="../index.php" target="_blank" class="qa-btn"><span class="qa-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg></span><span class="qa-label">View Site</span></a>
                    </div>
                </div>

                <!-- Recent Messages -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <span class="dash-card-title">// Messages</span>
                        <a href="messages.php" class="dash-card-action">View All →</a>
                    </div>
                    <div class="msg-list">
                        <?php foreach ($recentMessages as $m): ?>
                        <div class="msg-item <?= !$m['is_read']?'unread':'' ?> <?= $m['is_starred']?'starred':'' ?>" onclick="window.location='messages.php?id=<?= $m['id'] ?>'">
                            <div class="msg-dot <?= $m['is_read']?'read':'' ?>"></div>
                            <div class="msg-body">
                                <div class="msg-from"><?= htmlspecialchars($m['name']) ?></div>
                                <div class="msg-subject"><?= htmlspecialchars($m['subject'] ?: $m['message']) ?></div>
                            </div>
                            <div class="msg-date"><?= date('M j', strtotime($m['created_at'])) ?></div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($recentMessages)): ?>
                        <div style="padding:30px;text-align:center;font-family:var(--font-mono);font-size:0.75rem;color:var(--text-muted);">No messages yet.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Log -->
        <!--
        <?php if (!empty($activityLog)): ?>
        <div class="dash-card" style="margin-top:24px;">
            <div class="dash-card-header">
                <span class="dash-card-title">// Recent Activity</span>
            </div>
            <div class="activity-list">
                <?php foreach ($activityLog as $log): ?>
                <div class="activity-item">
                    <span class="activity-icon">◆</span>
                    <span style="flex:1;"><?= htmlspecialchars($log['action']) ?><?= $log['details'] ? ' — <span style="color:var(--text-muted)">'.htmlspecialchars($log['details']).'</span>' : '' ?></span>
                    <span class="activity-time"><?= date('M j, g:ia', strtotime($log['created_at'])) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
                -->
    </div>
</div>

<script>
function logout() {
    Swal.fire({
        title: 'Logout?', text: 'You will be redirected to the login page.',
        icon: 'question', showCancelButton: true,
        confirmButtonText: 'Yes, logout', confirmButtonColor: '#c9a96e',
        cancelButtonColor: '#4a3728', background: '#f0ebe3', color: '#1a1714'
    }).then(r => { if (r.isConfirmed) window.location = 'logout.php'; });
}

function deleteProject(id) {
    Swal.fire({
        title: 'Delete Project?', text: 'This action cannot be undone. All images will be deleted.',
        icon: 'warning', showCancelButton: true,
        confirmButtonText: 'Yes, delete it', confirmButtonColor: '#c9a96e',
        cancelButtonColor: '#4a3728', background: '#f0ebe3', color: '#1a1714'
    }).then(r => {
        if (r.isConfirmed) {
            fetch('actions.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ action: 'delete_project', id })
            }).then(r => r.json()).then(d => {
                if (d.success) { Swal.fire({ icon: 'success', title: 'Deleted!', timer: 1500, showConfirmButton: false, background: '#f0ebe3', color: '#1a1714' }).then(()=>location.reload()); }
                else Swal.fire({ icon: 'error', title: 'Error', text: d.message, background: '#f0ebe3', color: '#1a1714' });
            });
        }
    });
}
</script>
</body>
</html>
