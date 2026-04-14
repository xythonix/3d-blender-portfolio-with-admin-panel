<?php  
require_once '../includes/config.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Projects — Admin</title>
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
.topbar-btn{padding:8px 20px;background:linear-gradient(135deg,var(--primary),var(--accent));color:var(--bg-deep);font-family:var(--font-mono);font-size:0.72rem;font-weight:700;letter-spacing:0.1em;border:none;cursor:pointer;border-radius:2px;text-decoration:none;100%,0% 100%);}

.content{padding:40px;}

/* Filter/search bar */
.list-toolbar{display:flex;gap:14px;margin-bottom:28px;align-items:center;flex-wrap:wrap;}
.search-input{padding:10px 16px;background:rgba(74,55,40,0.03);border:1px solid rgba(74,55,40,0.12);color:var(--text);font-family:var(--font-mono);font-size:0.75rem;border-radius:2px;outline:none;width:240px;}
.search-input:focus{border-color:var(--primary);}
.filter-select{padding:10px 14px;background:rgba(74,55,40,0.03);border:1px solid rgba(74,55,40,0.12);color:var(--text-muted);font-family:var(--font-mono);font-size:0.72rem;border-radius:2px;outline:none;}
.filter-select option{background:var(--bg-card);color:var(--text);}
.results-info{font-family:var(--font-mono);font-size:0.72rem;color:var(--text-muted);margin-left:auto;}

/* Projects table */
.projects-table-wrap{background:var(--bg-card);border:1px solid var(--border);border-radius:4px;overflow:hidden;}
.table-header{padding:16px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
.table-title{font-family:var(--font-mono);font-size:0.78rem;color:var(--primary);letter-spacing:0.15em;text-transform:uppercase;}
table{width:100%;border-collapse:collapse;}
thead th{padding:12px 16px;font-family:var(--font-mono);font-size:0.62rem;color:var(--text-muted);letter-spacing:0.2em;text-transform:uppercase;border-bottom:1px solid var(--border);text-align:left;white-space:nowrap;}
tbody td{padding:14px 16px;border-bottom:1px solid rgba(74,55,40,0.04);vertical-align:middle;}
tbody tr:hover td{background:rgba(74,55,40,0.02);}
tbody tr:last-child td{border-bottom:none;}

.td-thumb{width:60px;height:40px;border-radius:2px;object-fit:cover;background:linear-gradient(135deg,#e8e6f0,#d8d0f0);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:0.6rem;color:rgba(74,55,40,0.15);}
.td-thumb img{width:60px;height:40px;object-fit:cover;border-radius:2px;}
.td-title{font-weight:600;color:var(--text);font-size:0.95rem;}
.td-title a{text-decoration:none;color:inherit;transition:color 0.2s;}
.td-title a:hover{color:var(--primary);}
.td-slug{font-family:var(--font-mono);font-size:0.62rem;color:var(--text-muted);margin-top:2px;}
.td-cat{font-family:var(--font-mono);font-size:0.7rem;color:var(--primary);}
.td-year{font-family:var(--font-mono);font-size:0.7rem;color:var(--text-muted);}
.td-views{font-family:var(--font-mono);font-size:0.75rem;}
.status-badge{display:inline-block;padding:3px 10px;font-family:var(--font-mono);font-size:0.6rem;letter-spacing:0.08em;text-transform:uppercase;border-radius: 0;cursor:pointer;transition:all 0.2s;}
.status-badge.published{background:rgba(26,110,60,0.1);border:1px solid rgba(26,110,60,0.3);color:#1a6e3c;}
.status-badge.draft{background:rgba(255,214,0,0.1);border:1px solid rgba(255,214,0,0.3);color:var(--gold);}
.status-badge:hover{filter:brightness(1.3);}
.featured-toggle{cursor:pointer;font-size:1rem;transition:transform 0.2s;display:inline-block;}
.featured-toggle:hover{transform:scale(1.3);}
.td-actions{display:flex;gap:6px;align-items:center;white-space:nowrap;}
.action-btn{padding:5px 12px;background:transparent;border:1px solid rgba(74,55,40,0.12);color:var(--text-muted);font-family:var(--font-mono);font-size:0.62rem;cursor:pointer;border-radius:2px;text-decoration:none;transition:all 0.2s;}
.action-btn:hover{border-color:var(--primary);color:var(--primary);}
.action-btn.view:hover{border-color:rgba(139,106,79,0.25);color:var(--accent);}
.action-btn.delete:hover{border-color:var(--secondary);color:var(--secondary);}
.no-projects{padding:60px;text-align:center;font-family:var(--font-mono);font-size:0.8rem;color:var(--text-muted);}

/* ─── Pagination ─── */
.pagination-bar{display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-top:1px solid var(--border);background:var(--bg-card);}
.pg-left{font-family:var(--font-mono);font-size:0.7rem;color:var(--text-muted);letter-spacing:0.08em;}
.pg-left span{color:var(--primary);font-weight:600;}
.pg-btns{display:flex;gap:4px;align-items:center;}
.pg-btn{width:34px;height:34px;display:flex;align-items:center;justify-content:center;background:transparent;border:1px solid rgba(74,55,40,0.15);color:var(--text-muted);font-family:var(--font-mono);font-size:0.72rem;cursor:pointer;border-radius:2px;transition:all 0.18s;letter-spacing:0.03em;line-height:1;}
.pg-btn:hover:not(:disabled){border-color:var(--primary);color:var(--primary);background:rgba(74,55,40,0.04);}
.pg-btn.active{background:var(--primary);border-color:var(--primary);color:var(--bg-deep);}
.pg-btn:disabled{opacity:0.28;cursor:default;pointer-events:none;}
.pg-ellipsis{width:34px;height:34px;display:flex;align-items:center;justify-content:center;font-family:var(--font-mono);font-size:0.75rem;color:var(--text-muted);letter-spacing:0.05em;}

/* ─── Table body loading overlay ─── */
.table-loading-wrap{position:relative;}
.table-overlay{display:none;position:absolute;inset:0;background:rgba(250,248,245,0.82);backdrop-filter:blur(2px);z-index:10;align-items:center;justify-content:center;flex-direction:column;gap:12px;}
.table-overlay.visible{display:flex;}
.tbl-spinner{width:32px;height:32px;border:2px solid rgba(74,55,40,0.1);border-top-color:var(--primary);border-radius:50%;animation:tspin 0.65s linear infinite;}
@keyframes tspin{to{transform:rotate(360deg);}}
.tbl-spinner-label{font-family:var(--font-mono);font-size:0.62rem;color:var(--primary);letter-spacing:0.2em;text-transform:uppercase;}

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

/* Pagination — interior theme */
.pagination-bar { background: #ffffff !important; border-top: 1px solid rgba(74,55,40,0.1) !important; }
.pg-btn { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.2) !important; color: #7a6a5a !important; border-radius: 0 !important; }
.pg-btn:hover:not(:disabled) { border-color: #4a3728 !important; color: #4a3728 !important; background: rgba(74,55,40,0.04) !important; }
.pg-btn.active { background: #1a1714 !important; border-color: #1a1714 !important; color: #faf8f5 !important; }
.pg-ellipsis { color: #7a6a5a !important; }
.pg-left { color: #7a6a5a !important; }
.pg-left span { color: #4a3728 !important; }
.projects-table-wrap { border-radius: 0 !important; }

</style>
</head>
<body>
<?php

try {
    $db = getDB();
    $projects = $db->query("SELECT p.*, pi.filename as tf, (SELECT COUNT(*) FROM project_images WHERE project_id=p.id) as img_count FROM projects p LEFT JOIN project_images pi ON p.id=pi.project_id AND pi.is_primary=1 ORDER BY p.created_at DESC")->fetchAll();
    $totalProjects = count($projects);
    $unreadMessages = $db->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();
} catch(Exception $e) { $projects = []; $unreadMessages = 0; }
?>

<aside class="sidebar">
    <div class="sidebar-logo"><a href="../index.php">ALI<span>.</span>AFZAL</a><small>// ADMIN PANEL</small></div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="dashboard.php" class="sidebar-link"><span class="icon">◈</span> Dashboard</a>
        <a href="projects.php" class="sidebar-link active"><span class="icon">⬡</span> Projects</a>
        <a href="messages.php" class="sidebar-link"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></span> Messages<?= $unreadMessages>0?'<span class="badge">'.$unreadMessages.'</span>':'' ?></a>
        <div class="nav-section-label">Content</div>
        <a href="add-project.php" class="sidebar-link"><span class="icon">+</span> Add Project</a>
        <a href="categories.php" class="sidebar-link"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg></span> Categories</a>
        <div class="nav-section-label">System</div>
        <a href="settings.php" class="sidebar-link"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg></span> Settings</a>
        <a href="../index.php" class="sidebar-link" target="_blank"><span class="icon">↗</span> View Site</a>
    </nav>
    <div class="sidebar-footer"><button class="logout-btn" style="color:#ffffffa9 !important;" onclick="logout()">Logout</button></div>
</aside>

<div class="main">
    <div class="topbar">
        <div class="page-title">Projects (<span id="title-count"><?= $totalProjects ?></span>)</div>
        <a href="add-project.php" class="topbar-btn">+ New Project</a>
    </div>
    <div class="content">
        <div class="list-toolbar">
            <input type="text" class="search-input" id="search" placeholder="Search projects..." oninput="filterTable()">
            <select class="filter-select" id="cat-filter" onchange="filterTable()">
                <option value="">All Categories</option>
                <?php $cats = array_unique(array_column($projects,'category')); foreach($cats as $c) echo '<option value="'.htmlspecialchars($c).'">'.htmlspecialchars($c).'</option>'; ?>
            </select>
            <select class="filter-select" id="status-filter" onchange="filterTable()">
                <option value="">All Status</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
            <div class="results-info" id="results-info"><?= $totalProjects ?> projects</div>
        </div>

        <div class="table-loading-wrap" id="table-loading-wrap">
            <div class="table-overlay" id="table-overlay">
                <div class="tbl-spinner"></div>
                <div class="tbl-spinner-label">Loading</div>
            </div>
        <div class="projects-table-wrap">
            <div class="table-header">
                <span class="table-title">// All Projects</span>
            </div>
            <table id="projects-table">
                <thead><tr>
                    <th>Thumb</th><th>Title</th><th>Category</th><th>Year</th>
                    <th>Images</th><th>Views</th><th>Status</th><th>Featured</th><th>Actions</th>
                </tr></thead>
                <tbody id="table-body">
                <?php foreach ($projects as $p): ?>
                <tr data-title="<?= strtolower(htmlspecialchars($p['title'])) ?>" data-cat="<?= strtolower(htmlspecialchars($p['category'])) ?>" data-status="<?= $p['status'] ?>" data-slug="<?= strtolower(htmlspecialchars($p['slug'])) ?>">
                    <td>
                        <?php if ($p['tf']): ?>
                        <img class="td-thumb" src="<?= UPLOAD_URL ?>projects/<?= htmlspecialchars($p['tf']) ?>" alt="">
                        <?php else: ?>
                        <div class="td-thumb">3D</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="td-title"><a href="../project.php?slug=<?= htmlspecialchars($p['slug']) ?>" target="_blank"><?= htmlspecialchars($p['title']) ?></a></div>
                        <div class="td-slug">/<?= htmlspecialchars($p['slug']) ?></div>
                    </td>
                    <td class="td-cat"><?= htmlspecialchars($p['category']) ?></td>
                    <td class="td-year"><?= htmlspecialchars($p['year']??'—') ?></td>
                    <td class="td-views" style="text-align:center;"><?= $p['img_count'] ?></td>
                    <td class="td-views"><?= number_format($p['view_count']) ?></td>
                    <td>
                        <span class="status-badge <?= $p['status'] ?>" onclick="toggleStatus(<?= $p['id'] ?>, '<?= $p['status']==='published'?'draft':'published' ?>', this)">
                            <?= $p['status'] ?>
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <span class="featured-toggle" onclick="toggleFeatured(<?= $p['id'] ?>, <?= $p['featured']?0:1 ?>, this)">
                            <?= $p['featured'] ? '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>' ?>
                        </span>
                    </td>
                    <td class="td-actions">
                        <a href="edit-project.php?id=<?= $p['id'] ?>" class="action-btn">Edit</a>
                        <a href="../project.php?slug=<?= htmlspecialchars($p['slug']) ?>" target="_blank" class="action-btn view">View</a>
                        <button onclick="deleteProject(<?= $p['id'] ?>)" class="action-btn delete">Del</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($projects)): ?>
                <tr><td colspan="9" class="no-projects">No projects yet. <a href="add-project.php" style="color:var(--primary);">Create your first →</a></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div><!-- /projects-table-wrap -->

        <!-- Pagination bar -->
        <div class="pagination-bar" id="pagination-bar" style="display:none;">
            <div class="pg-left">Showing <span id="pg-from">1</span>–<span id="pg-to">12</span> of <span id="pg-total">0</span> projects</div>
            <div class="pg-btns" id="pg-btns"></div>
        </div>

        </div><!-- /table-loading-wrap -->
    </div>
</div>

<script>
// ── Pagination state ──────────────────────────────────────────────────────────
const PER_PAGE = 12;
let currentPage = 1;

// Collect all rows from PHP render into a JS array for client-side pagination
const ALL_ROWS = Array.from(document.querySelectorAll('#table-body tr[data-title]'));

function getFiltered() {
    const q      = document.getElementById('search').value.toLowerCase().trim();
    const cat    = document.getElementById('cat-filter').value.toLowerCase();
    const status = document.getElementById('status-filter').value;
    return ALL_ROWS.filter(row =>
        (!q      || row.dataset.title.includes(q) || (row.dataset.slug||'').includes(q)) &&
        (!cat    || row.dataset.cat.includes(cat)) &&
        (!status || row.dataset.status === status)
    );
}

function filterTable() {
    currentPage = 1;
    renderPage();
}

function showOverlay() {
    document.getElementById('table-overlay').classList.add('visible');
}
function hideOverlay() {
    document.getElementById('table-overlay').classList.remove('visible');
}

function renderPage() {
    showOverlay();
    // Small delay so the spinner is visible
    setTimeout(() => {
        const filtered = getFiltered();
        const total    = filtered.length;
        const pages    = Math.max(1, Math.ceil(total / PER_PAGE));
        if (currentPage > pages) currentPage = pages;

        const start = (currentPage - 1) * PER_PAGE;
        const end   = Math.min(start + PER_PAGE, total);

        // Show/hide all rows
        ALL_ROWS.forEach(r => r.style.display = 'none');
        filtered.slice(start, end).forEach(r => r.style.display = '');

        // Update info
        document.getElementById('results-info').textContent = total + ' project' + (total !== 1 ? 's' : '');
        document.getElementById('title-count').textContent  = total;
        document.getElementById('pg-from').textContent = total === 0 ? 0 : start + 1;
        document.getElementById('pg-to').textContent   = end;
        document.getElementById('pg-total').textContent = total;

        const bar = document.getElementById('pagination-bar');
        bar.style.display = total > PER_PAGE ? 'flex' : 'none';

        renderPagination(pages);
        hideOverlay();
    }, 180);
}

function renderPagination(pages) {
    const wrap = document.getElementById('pg-btns');
    wrap.innerHTML = '';
    const p = currentPage;

    function btn(label, page, isActive, isDisabled) {
        const el = document.createElement('button');
        el.className = 'pg-btn' + (isActive ? ' active' : '');
        el.innerHTML  = label;
        el.disabled   = isDisabled;
        if (!isDisabled && !isActive) el.onclick = () => goTo(page);
        return el;
    }

    // Prev arrow
    wrap.appendChild(btn('←', p - 1, false, p === 1));

    // Page numbers with smart ellipsis
    const nums = buildPageList(p, pages);
    nums.forEach(n => {
        if (n === '…') {
            const s = document.createElement('span');
            s.className = 'pg-ellipsis'; s.textContent = '···';
            wrap.appendChild(s);
        } else {
            wrap.appendChild(btn(n, n, n === p, false));
        }
    });

    // Next arrow
    wrap.appendChild(btn('→', p + 1, false, p === pages));
}

function buildPageList(cur, total) {
    if (total <= 7) return Array.from({length: total}, (_, i) => i + 1);
    const set = new Set([1, total, cur, cur-1, cur+1].filter(n => n >= 1 && n <= total));
    const arr = [...set].sort((a,b) => a-b);
    const out = [];
    for (let i = 0; i < arr.length; i++) {
        if (i > 0 && arr[i] - arr[i-1] > 1) out.push('…');
        out.push(arr[i]);
    }
    return out;
}

function goTo(page) {
    currentPage = page;
    // Scroll table into view
    document.getElementById('table-loading-wrap').scrollIntoView({behavior:'smooth', block:'start'});
    renderPage();
}

// Wire filters — debounce search
let searchTimer;
document.getElementById('search').addEventListener('input', () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(filterTable, 250);
});
document.getElementById('cat-filter').addEventListener('change', filterTable);
document.getElementById('status-filter').addEventListener('change', filterTable);

// Initial render
renderPage();
function toggleStatus(id, newStatus, el) {
    fetch('actions.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({action:'toggle_status',id,status:newStatus}) })
    .then(r=>r.json()).then(d=>{ if(d.success){el.textContent=d.status;el.className='status-badge '+d.status;el.closest('tr').dataset.status=d.status;filterTable();} });
}
function toggleFeatured(id, val, el) {
    fetch('actions.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({action:'toggle_featured',id,featured:val}) })
    .then(r=>r.json()).then(d=>{ if(d.success){el.innerHTML=d.featured?'<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>':'<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';el.setAttribute('onclick',`toggleFeatured(${id},${d.featured?0:1},this)`);} });
}
function deleteProject(id) {
    Swal.fire({ title:'Delete Project?', text:'All images will be permanently deleted.', icon:'warning', showCancelButton:true, confirmButtonText:'Delete', confirmButtonColor:'#c9a96e', background:'#f0ebe3', color:'#1a1714' })
    .then(r=>{ if(r.isConfirmed) { fetch('actions.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'delete_project',id})}).then(r=>r.json()).then(d=>{if(d.success){Swal.fire({icon:'success',title:'Deleted!',timer:1200,showConfirmButton:false,background:'#f0ebe3',color:'#1a1714'}).then(()=>location.reload());}else Swal.fire({icon:'error',title:'Error',text:d.message,background:'#f0ebe3',color:'#1a1714'});}); } });
}
function logout(){ Swal.fire({title:'Logout?',icon:'question',showCancelButton:true,confirmButtonText:'Yes',confirmButtonColor:'#c9a96e',background:'#f0ebe3',color:'#1a1714'}).then(r=>{if(r.isConfirmed)window.location='logout.php';}); }
</script>
</body>
</html>