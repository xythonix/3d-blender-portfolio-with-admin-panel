<?php  
require_once '../includes/config.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Messages — Ali Afzal Admin</title>
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
.logout-btn{width:100%;padding:10px;background:rgba(255,0,110,0.08);border:1px solid rgba(201,169,110,0.15);color:var(--secondary);font-family:var(--font-mono);font-size:0.7rem;letter-spacing:0.1em;cursor:pointer;border-radius:2px;}
.main{margin-left:var(--sidebar);flex:1;}
.topbar{padding:20px 40px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:rgba(250,248,245,0.97);backdrop-filter:blur(20px);position:sticky;top:0;z-index:40;}
.page-title{font-family:var(--font-display);font-size:1.2rem;font-weight:700;color:var(--text);}
.topbar-right{display:flex;gap:12px;align-items:center;}
.topbar-btn{padding:8px 20px;background:rgba(74,55,40,0.06);border:1px solid rgba(74,55,40,0.15);color:var(--primary);font-family:var(--font-mono);font-size:0.72rem;letter-spacing:0.1em;cursor:pointer;border-radius:2px;text-decoration:none;transition:all 0.25s;}
.topbar-btn:hover{background:rgba(74,55,40,0.12);}
.content{display:flex;height:calc(100vh - 80px);}

/* Messages list */
.msg-list-panel{width:380px;border-right:1px solid var(--border);overflow-y:auto;flex-shrink:0;}
.msg-toolbar{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;gap:10px;align-items:center;background:rgba(245,240,234,0.6);}
.filter-tabs{display:flex;gap:0;}
.filter-tab{padding:6px 14px;background:transparent;border:1px solid rgba(74,55,40,0.12);color:var(--text-muted);font-family:var(--font-mono);font-size:0.68rem;letter-spacing:0.06em;cursor:pointer;transition:all 0.2s;}
.filter-tab:first-child{border-radius:2px 0 0 2px;}
.filter-tab:last-child{border-radius:0 2px 2px 0;border-left:none;}
.filter-tab.active{background:rgba(74,55,40,0.1);border-color:var(--primary);color:var(--primary);}

.msg-item{padding:18px 20px;border-bottom:1px solid rgba(74,55,40,0.04);cursor:pointer;transition:background 0.2s;display:flex;gap:12px;align-items:flex-start;}
.msg-item:hover,.msg-item.selected{background:rgba(74,55,40,0.04);}
.msg-item.selected{border-left:2px solid var(--primary);}
.msg-item.unread .msg-item-name{color:var(--text);font-weight:700;}
.msg-item-dot{width:8px;height:8px;background:var(--primary);border-radius:50%;flex-shrink:0;margin-top:5px;transition:background 0.2s;}
.msg-item-dot.read{background:transparent;border:1px solid rgba(107,139,164,0.3);}
.msg-item-dot.star{background:var(--gold);}
.msg-item-body{flex:1;min-width:0;}
.msg-item-name{font-size:0.95rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.msg-item-subject{font-size:0.82rem;color:var(--text-muted);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.msg-item-preview{font-size:0.78rem;color:rgba(107,139,164,0.6);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.msg-item-date{font-family:var(--font-mono);font-size:0.62rem;color:var(--text-muted);white-space:nowrap;}

/* Message view */
.msg-view-panel{flex:1;overflow-y:auto;display:flex;flex-direction:column;}
.msg-empty{flex:1;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:16px;color:var(--text-muted);}
.msg-empty-icon{font-size:3rem;opacity:0.2;}
.msg-empty-text{font-family:var(--font-mono);font-size:0.8rem;letter-spacing:0.2em;}

.msg-view-header{padding:28px 36px 20px;border-bottom:1px solid var(--border);}
.msg-view-actions{display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;}
.msg-action-btn{padding:8px 18px;background:rgba(74,55,40,0.05);border:1px solid rgba(74,55,40,0.12);color:var(--text-muted);font-family:var(--font-mono);font-size:0.7rem;letter-spacing:0.08em;cursor:pointer;border-radius:2px;transition:all 0.2s;text-decoration:none;}
.msg-action-btn:hover{border-color:var(--primary);color:var(--primary);}
.msg-action-btn.delete:hover{border-color:var(--secondary);color:var(--secondary);}
.msg-action-btn.star.active{border-color:var(--gold);color:var(--gold);}
.msg-view-subject{font-family:var(--font-display);font-size:1.3rem;font-weight:700;color:var(--text);margin-bottom:16px;line-height:1.3;}
.msg-view-meta{display:flex;gap:20px;flex-wrap:wrap;}
.msg-meta-item{font-family:var(--font-mono);font-size:0.72rem;}
.msg-meta-label{color:var(--text-muted);}
.msg-meta-val{color:var(--text);}
.msg-view-body{padding:36px;flex:1;}
.msg-view-text{font-size:1.05rem;color:rgba(26,23,20,0.85);line-height:1.9;white-space:pre-wrap;}
.msg-reply-bar{padding:20px 36px;border-top:1px solid var(--border);background:rgba(245,240,234,0.6);}
.msg-reply-link{display:inline-flex;align-items:center;gap:10px;padding:12px 24px;background:rgba(74,55,40,0.06);border:1px solid rgba(74,55,40,0.15);color:var(--primary);font-family:var(--font-mono);font-size:0.75rem;letter-spacing:0.1em;text-decoration:none;border-radius:2px;transition:all 0.25s;100%,0% 100%);}
.msg-reply-link:hover{background:rgba(74,55,40,0.12);box-shadow: none;}

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
    $messages = $db->query("SELECT * FROM messages ORDER BY is_starred DESC, created_at DESC")->fetchAll();
    $unreadCount = $db->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();
    $unreadMessages = $unreadCount;
} catch(Exception $e) {
    $messages = []; $unreadMessages = 0;
}

$selectedId = intval($_GET['id'] ?? 0);
$selectedMsg = null;
if ($selectedId) {
    foreach ($messages as $m) {
        if ($m['id'] == $selectedId) { $selectedMsg = $m; break; }
    }
    // Mark as read
    if ($selectedMsg && !$selectedMsg['is_read']) {
        try { $db->prepare("UPDATE messages SET is_read=1 WHERE id=?")->execute([$selectedId]); } catch(Exception $e){}
    }
}
?>

<aside class="sidebar">
    <div class="sidebar-logo">
        <a href="../index.php">ALI<span>.</span>AFZAL</a>
        <small>// ADMIN PANEL</small>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="dashboard.php" class="sidebar-link"><span class="icon">◈</span> Dashboard</a>
        <a href="projects.php" class="sidebar-link"><span class="icon">⬡</span> Projects</a>
        <a href="messages.php" class="sidebar-link active"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></span> Messages<?= $unreadMessages > 0 ? '<span class="badge">'.$unreadMessages.'</span>' : '' ?></a>
        <div class="nav-section-label">Content</div>
        <a href="add-project.php" class="sidebar-link"><span class="icon">+</span> Add Project</a>
        <a href="categories.php" class="sidebar-link"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg></span> Categories</a>
        <div class="nav-section-label">System</div>
        <a href="settings.php" class="sidebar-link"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg></span> Settings</a>
        <a href="../index.php" class="sidebar-link" target="_blank"><span class="icon">↗</span> View Site</a>
    </nav>
    <div class="sidebar-footer">
        <button class="logout-btn" onclick="logout()" style="color:#ffffffa9 !important;">Logout</button>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div class="page-title">Messages <?= $unreadMessages > 0 ? '<span style="font-size:0.8rem;color:var(--secondary);font-family:var(--font-mono);">('.$unreadMessages.' unread)</span>' : '' ?></div>
        <div class="topbar-right">
            <button class="topbar-btn" onclick="markAllRead()">Mark All Read</button>
        </div>
    </div>

    <div class="content">
        <!-- Messages List -->
        <div class="msg-list-panel">
            <div class="msg-toolbar">
                <div class="filter-tabs">
                    <button class="filter-tab active" data-filter="all" onclick="filterMsgs('all',this)">All</button>
                    <button class="filter-tab" data-filter="unread" onclick="filterMsgs('unread',this)">Unread</button>
                    <button class="filter-tab" data-filter="starred" onclick="filterMsgs('starred',this)"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></button>
                </div>
            </div>

            <?php foreach ($messages as $m): ?>
            <div class="msg-item <?= !$m['is_read']?'unread':'' ?> <?= $selectedId==$m['id']?'selected':'' ?>"
                 id="msg-row-<?= $m['id'] ?>"
                 data-unread="<?= $m['is_read']?'0':'1' ?>"
                 data-starred="<?= $m['is_starred'] ?>"
                 onclick="selectMsg(<?= $m['id'] ?>)">
                <div class="msg-item-dot <?= $m['is_read']?'read':'' ?> <?= $m['is_starred']?'star':'' ?>"></div>
                <div class="msg-item-body">
                    <div class="msg-item-name"><?= htmlspecialchars($m['name']) ?></div>
                    <div class="msg-item-subject"><?= htmlspecialchars($m['subject'] ?: '(No subject)') ?></div>
                    <div class="msg-item-preview"><?= htmlspecialchars(substr($m['message'], 0, 60)) ?>...</div>
                </div>
                <div class="msg-item-date"><?= date('M j', strtotime($m['created_at'])) ?></div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($messages)): ?>
            <div style="padding:50px 20px;text-align:center;font-family:var(--font-mono);font-size:0.75rem;color:var(--text-muted);">
                <div style="font-size:2rem;opacity:0.2;margin-bottom:12px;"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></div>
                No messages yet.
            </div>
            <?php endif; ?>
        </div>

        <!-- Message View -->
        <div class="msg-view-panel" id="msg-view-panel">
            <?php if ($selectedMsg): ?>
            <div class="msg-view-header">
                <div class="msg-view-actions">
                    <button class="msg-action-btn <?= $selectedMsg['is_starred']?'star active':'' ?>" id="star-btn" onclick="toggleStar(<?= $selectedMsg['id'] ?>)">
                        <?= $selectedMsg['is_starred']?'<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> Starred':'<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> Star' ?>
                    </button>
                    <button class="msg-action-btn" onclick="toggleRead(<?= $selectedMsg['id'] ?>, <?= $selectedMsg['is_read']?0:1 ?>)">
                        <?= $selectedMsg['is_read']?'Mark Unread':'Mark Read' ?>
                    </button>
                    <button class="msg-action-btn delete" onclick="deleteMsg(<?= $selectedMsg['id'] ?>)"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg> Delete</button>
                </div>
                <div class="msg-view-subject"><?= htmlspecialchars($selectedMsg['subject'] ?: '(No subject)') ?></div>
                <div class="msg-view-meta">
                    <div class="msg-meta-item"><span class="msg-meta-label">From: </span><span class="msg-meta-val"><?= htmlspecialchars($selectedMsg['name']) ?></span></div>
                    <div class="msg-meta-item"><span class="msg-meta-label">Email: </span><span class="msg-meta-val"><?= htmlspecialchars($selectedMsg['email']) ?></span></div>
                    <div class="msg-meta-item"><span class="msg-meta-label">Date: </span><span class="msg-meta-val"><?= date('M j, Y g:i A', strtotime($selectedMsg['created_at'])) ?></span></div>
                    <div class="msg-meta-item"><span class="msg-meta-label">IP: </span><span class="msg-meta-val"><?= htmlspecialchars($selectedMsg['ip_address'] ?? '—') ?></span></div>
                </div>
            </div>
            <div class="msg-view-body">
                <div class="msg-view-text"><?= htmlspecialchars($selectedMsg['message']) ?></div>
            </div>
            <div class="msg-reply-bar">
                <a href="mailto:<?= htmlspecialchars($selectedMsg['email']) ?>?subject=Re: <?= urlencode($selectedMsg['subject']??'Your Message') ?>" class="msg-reply-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg> Reply via Email
                </a>
            </div>
            <?php else: ?>
            <div class="msg-empty">
                <div class="msg-empty-icon" style="opacity:0.2"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></div>
                <div class="msg-empty-text">Select a message to read</div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function selectMsg(id) {
    window.location.href = 'messages.php?id=' + id;
}
function filterMsgs(type, btn) {
    document.querySelectorAll('.filter-tab').forEach(t=>t.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.msg-item').forEach(item => {
        if (type === 'all') { item.style.display = ''; }
        else if (type === 'unread') { item.style.display = item.dataset.unread === '1' ? '' : 'none'; }
        else if (type === 'starred') { item.style.display = item.dataset.starred === '1' ? '' : 'none'; }
    });
}
function toggleStar(id) {
    fetch('actions.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({action:'toggle_star',id}) })
    .then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function toggleRead(id, val) {
    fetch('actions.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({action:'mark_read',id,is_read:val}) })
    .then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function deleteMsg(id) {
    Swal.fire({ title:'Delete Message?', icon:'warning', showCancelButton:true, confirmButtonText:'Yes, delete', confirmButtonColor:'#c9a96e', background:'#f0ebe3', color:'#1a1714' })
    .then(r => {
        if (r.isConfirmed) {
            fetch('actions.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({action:'delete_message',id}) })
            .then(r=>r.json()).then(d => { if (d.success) window.location='messages.php'; });
        }
    });
}
function markAllRead() {
    fetch('actions.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({action:'mark_read',id:'all',is_read:1}) })
    .then(()=>location.reload());
}
function logout() {
    Swal.fire({ title:'Logout?', icon:'question', showCancelButton:true, confirmButtonText:'Yes, logout', confirmButtonColor:'#c9a96e', background:'#f0ebe3', color:'#1a1714' })
    .then(r=>{ if(r.isConfirmed) window.location='logout.php'; });
}
</script>
</body>
</html>
