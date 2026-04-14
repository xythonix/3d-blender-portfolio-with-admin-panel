<?php
require_once '../includes/config.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Categories — Ali Afzal Admin</title>
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
.content{padding:40px;}
.layout{display:grid;grid-template-columns:1fr 320px;gap:28px;align-items:start;}
/* Category list */
.cat-list{background:var(--bg-card);border:1px solid var(--border);border-radius:4px;overflow:hidden;position:relative;}
.cat-list::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,var(--primary),transparent);}
.list-header{padding:14px 24px;border-bottom:1px solid var(--border);font-family:var(--font-mono);font-size:0.72rem;color:var(--primary);letter-spacing:0.15em;display:flex;align-items:center;justify-content:space-between;}
.list-header::before{content:'// ';}
.cat-item{display:grid;grid-template-columns:36px 1fr auto auto auto;align-items:center;gap:14px;padding:14px 24px;border-bottom:1px solid rgba(74,55,40,0.05);transition:background 0.2s;}
.cat-item:last-child{border-bottom:none;}
.cat-item:hover{background:rgba(74,55,40,0.02);}
.cat-drag{color:var(--text-muted);cursor:grab;font-size:1.1rem;opacity:0.4;transition:opacity 0.2s;}
.cat-item:hover .cat-drag{opacity:0.8;}
.cat-icon{width:32px;height:32px;background:rgba(74,55,40,0.06);border:1px solid var(--border);border-radius:2px;display:flex;align-items:center;justify-content:center;font-size:0.9rem;flex-shrink:0;}
.cat-info{min-width:0;}
.cat-name{font-family:var(--font-body);font-size:1rem;font-weight:600;color:var(--text);line-height:1.2;}
.cat-slug{font-family:var(--font-mono);font-size:0.62rem;color:var(--text-muted);}
.cat-count{font-family:var(--font-mono);font-size:0.7rem;color:var(--text-muted);white-space:nowrap;}
.cat-actions{display:flex;gap:6px;}
.cat-btn{padding:5px 10px;font-family:var(--font-mono);font-size:0.6rem;border-radius:2px;cursor:pointer;border:1px solid;transition:all 0.2s;}
.cat-btn.edit{color:var(--primary);border-color:rgba(74,55,40,0.25);background:rgba(74,55,40,0.05);}
.cat-btn.edit:hover{background:rgba(74,55,40,0.12);}
.cat-btn.del{color:var(--secondary);border-color:rgba(255,0,110,0.25);background:rgba(255,0,110,0.05);}
.cat-btn.del:hover{background:rgba(255,0,110,0.12);}
.empty-state{text-align:center;padding:48px 24px;font-family:var(--font-mono);font-size:0.75rem;color:var(--text-muted);}
/* Form panel */
.form-panel{background:var(--bg-card);border:1px solid var(--border);border-radius:4px;overflow:hidden;position:relative;position:sticky;top:80px;}
.form-panel::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,var(--primary),transparent);}
.panel-header{padding:14px 24px;border-bottom:1px solid var(--border);font-family:var(--font-mono);font-size:0.72rem;color:var(--primary);letter-spacing:0.15em;display:flex;align-items:center;justify-content:space-between;}
.panel-header-title::before{content:'// ';}
.panel-body{padding:24px;}
.form-group{margin-bottom:20px;}
.form-group:last-child{margin-bottom:0;}
.form-label{display:block;font-family:var(--font-mono);font-size:0.65rem;color:var(--text-muted);letter-spacing:0.2em;text-transform:uppercase;margin-bottom:8px;}
.required{color:var(--secondary);}
.form-input,.form-select{width:100%;padding:11px 14px;background:rgba(74,55,40,0.03);border:1px solid rgba(74,55,40,0.12);border-radius:2px;color:var(--text);font-family:var(--font-body);font-size:1rem;outline:none;transition:border-color 0.3s,box-shadow 0.3s;}
.form-input:focus,.form-select:focus{border-color:var(--primary);box-shadow:0 0 0 2px rgba(74,55,40,0.06);}
.form-input::placeholder{color:rgba(107,139,164,0.35);}
.form-select option{background:var(--bg-card);color:var(--text);}
.form-hint{font-family:var(--font-mono);font-size:0.62rem;color:rgba(107,139,164,0.6);margin-top:6px;}
.slug-preview{font-family:var(--font-mono);font-size:0.68rem;color:rgba(74,55,40,0.5);margin-top:6px;}
.form-actions{display:flex;gap:10px;margin-top:24px;}
.form-btn{flex:1;padding:11px 16px;font-family:var(--font-mono);font-size:0.7rem;font-weight:700;letter-spacing:0.08em;border:none;cursor:pointer;border-radius:2px;transition:all 0.2s;}
.form-btn.submit{background:linear-gradient(135deg,var(--primary),var(--accent));color:var(--bg-deep);100%,0% 100%);}
.form-btn.submit:hover{box-shadow: none;}
.form-btn.cancel{background:rgba(74,55,40,0.06);color:var(--text-muted);border:1px solid rgba(74,55,40,0.12);}
.form-btn.cancel:hover{color:var(--primary);}
/* Icon grid picker */
.icon-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:6px;margin-top:8px;}
.icon-opt{width:100%;aspect-ratio:1;background:rgba(74,55,40,0.04);border:1px solid rgba(74,55,40,0.12);border-radius:2px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1rem;transition:all 0.2s;}
.icon-opt:hover{border-color:var(--primary);background:rgba(74,55,40,0.08);}
.icon-opt.selected{border-color:var(--primary);background:rgba(74,55,40,0.12);}
.selected-icon-preview{display:flex;align-items:center;gap:8px;font-family:var(--font-mono);font-size:0.68rem;color:var(--text-muted);margin-top:6px;}

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
    $categories = $db->query("
        SELECT c.*, COUNT(p.id) as project_count
        FROM categories c
        LEFT JOIN projects p ON p.category = c.name
        GROUP BY c.id
        ORDER BY c.sort_order ASC
    ")->fetchAll(PDO::FETCH_ASSOC);
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
        <a href="add-project.php" class="sidebar-link"><span class="icon">+</span> Add Project</a>
        <a href="categories.php" class="sidebar-link active"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg></span> Categories</a>
        <div class="nav-section-label">System</div>
        <a href="settings.php" class="sidebar-link"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg></span> Settings</a>
        <a href="../index.php" class="sidebar-link" target="_blank"><span class="icon">↗</span> View Site</a>
    </nav>
    <div class="sidebar-footer"><button class="logout-btn" onclick="logout()" style="color:#ffffffa9;">Logout</button></div>
</aside>

<div class="main">
    <div class="topbar">
        <div class="page-title">Categories</div>
        <button class="topbar-btn" onclick="newCategory()">+ Add Category</button>
    </div>
    <div class="content" style="width:100% !important">
        <div class="layout" style="width:100%;">

            <!-- Category List -->
            <div class="cat-list" style="width:100% !important" id="cat-list">
                <div class="list-header">
                    <span>All Categories (<?= count($categories) ?>)</span>
                </div>
                <?php if($categories): ?>
                <?php foreach($categories as $cat): ?>
                <div class="cat-item" id="cat-<?= $cat['id'] ?>" data-id="<?= $cat['id'] ?>">
                    <span class="cat-drag" title="Drag to reorder">⠿</span>
                    <div class="cat-icon" style="width:100px;padding:10px 20px;">⬡</div>
                    <div class="cat-info">
                        <div class="cat-name"><?= htmlspecialchars($cat['name']) ?></div>
                        <div class="cat-slug">/<?= htmlspecialchars($cat['slug']) ?></div>
                    </div>
                    <div class="cat-count"><?= $cat['project_count'] ?> project<?= $cat['project_count']!=1?'s':'' ?></div>
                    <div class="cat-actions">
                        <button class="cat-btn edit" onclick='editCategory(<?= json_encode($cat) ?>)'>Edit</button>
                        <button class="cat-btn del" onclick="deleteCategory(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['name'], ENT_QUOTES) ?>', <?= $cat['project_count'] ?>)">Del</button>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="empty-state">No categories yet.<br>Add your first one →</div>
                <?php endif; ?>
            </div>

            <!-- Add/Edit Form -->
            <div class="form-panel" id="form-panel">
                <div class="panel-header">
                    <span class="panel-header-title" id="panel-title">Add Category</span>
                    <button id="cancel-edit-btn" onclick="resetForm()" style="display:none;background:none;border:none;color:var(--text-muted);cursor:pointer;font-family:var(--font-mono);font-size:0.65rem;"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Cancel</button>
                </div>
                <div class="panel-body">
                    <input type="hidden" id="edit-id" value="">

                    <div class="form-group">
                        <label class="form-label">Name <span class="required">*</span></label>
                        <input type="text" class="form-input" id="cat-name" placeholder="e.g. Characters" oninput="autoSlug(this.value)" maxlength="100">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-input" id="cat-slug" placeholder="auto-generated" oninput="updateSlugPreview()">
                        <div class="slug-preview">/category/<span id="slug-live">...</span></div>
                    </div>

                    <div class="form-group">
                        <!-- <label class="form-label">Icon</label> -->
                        <input type="hidden" class="form-input" id="cat-icon" placeholder="Emoji or icon char" maxlength="10" oninput="updateIconPreview()">
                        <div class="icon-grid" id="icon-grid" style="display:none;">
                            <?php
                            $icons = ['person','landscape','vehicle','building','box','rotate','play','sparkle','robot','wave','fire','zap','masks','statue','arm','galaxy','crystal','gem'];
                            foreach($icons as $ic): ?>
                            <div class="icon-opt" onclick="selectIcon('<?= $ic ?>')"><?= $ic ?></div>
                            <?php endforeach; ?>
                        </div>
                        <!-- <div class="selected-icon-preview">Selected: <span id="icon-preview" style="font-size:1.2rem;">—</span></div> -->
                    </div>

                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" class="form-input" id="cat-order" value="0" min="0">
                        <div class="form-hint">Lower = appears first in lists.</div>
                    </div>

                    <div class="form-actions">
                        <button class="form-btn cancel" id="cancel-btn" onclick="resetForm()" style="display:none;">Cancel</button>
                        <button class="form-btn submit" id="submit-btn" onclick="submitCategory()">Add Category →</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
let editMode = false;

function autoSlug(v) {
    if (editMode) return; // don't overwrite slug when editing
    const s = v.toLowerCase().trim().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
    document.getElementById('cat-slug').value = s;
    updateSlugPreview();
}
function updateSlugPreview() {
    document.getElementById('slug-live').textContent = document.getElementById('cat-slug').value || '...';
}
function selectIcon(ic) {
    document.getElementById('cat-icon').value = ic;
    updateIconPreview();
    document.querySelectorAll('.icon-opt').forEach(el => el.classList.toggle('selected', el.textContent === ic));
}
function updateIconPreview() {
    const v = document.getElementById('cat-icon').value;
    document.getElementById('icon-preview').textContent = v || '—';
    document.querySelectorAll('.icon-opt').forEach(el => el.classList.toggle('selected', el.textContent.trim() === v.trim()));
}

function newCategory() {
    resetForm();
    document.getElementById('cat-name').focus();
}

function editCategory(cat) {
    editMode = true;
    document.getElementById('edit-id').value = cat.id;
    document.getElementById('cat-name').value = cat.name;
    document.getElementById('cat-slug').value = cat.slug;
    document.getElementById('cat-icon').value = cat.icon || '';
    document.getElementById('cat-order').value = cat.sort_order || 0;
    document.getElementById('panel-title').textContent = 'Edit Category';
    document.getElementById('submit-btn').textContent = 'Save Changes';
    document.getElementById('cancel-btn').style.display = 'block';
    document.getElementById('cancel-edit-btn').style.display = 'block';
    updateSlugPreview();
    updateIconPreview();
    document.getElementById('cat-name').focus();
}

function resetForm() {
    editMode = false;
    document.getElementById('edit-id').value = '';
    document.getElementById('cat-name').value = '';
    document.getElementById('cat-slug').value = '';
    document.getElementById('cat-icon').value = '';
    document.getElementById('cat-order').value = 0;
    document.getElementById('panel-title').textContent = 'Add Category';
    document.getElementById('submit-btn').textContent = 'Add Category →';
    document.getElementById('cancel-btn').style.display = 'none';
    document.getElementById('cancel-edit-btn').style.display = 'none';
    document.getElementById('slug-live').textContent = '...';
    document.getElementById('icon-preview').textContent = '—';
    document.querySelectorAll('.icon-opt').forEach(el => el.classList.remove('selected'));
}

function submitCategory() {
    const name = document.getElementById('cat-name').value.trim();
    const slug = document.getElementById('cat-slug').value.trim() || name.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
    const icon = document.getElementById('cat-icon').value.trim();
    const order = parseInt(document.getElementById('cat-order').value) || 0;
    const editId = document.getElementById('edit-id').value;

    if (!name) { Swal.fire({icon:'warning',title:'Name required',background:'#f0ebe3',color:'#1a1714'}); return; }

    const btn = document.getElementById('submit-btn');
    btn.disabled = true; btn.textContent = 'Saving...';

    const action = editId ? 'update_category' : 'create_category';
    const data = { action, name, slug, icon, sort_order: order };
    if (editId) data.id = parseInt(editId);

    fetch('actions.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(data) })
    .then(r=>r.json()).then(d => {
        btn.disabled = false;
        btn.textContent = editId ? 'Save Changes' : 'Add Category →';
        if (d.success) {
            Swal.fire({ icon:'success', title: editId ? 'Category Updated!' : 'Category Added!', timer:1400, showConfirmButton:false, background:'#f0ebe3', color:'#1a1714' })
            .then(() => location.reload());
        } else {
            Swal.fire({ icon:'error', title:'Error', text: d.message, background:'#f0ebe3', color:'#1a1714' });
        }
    }).catch(() => {
        btn.disabled = false; btn.textContent = 'Add Category →';
        Swal.fire({ icon:'error', title:'Network Error', background:'#f0ebe3', color:'#1a1714' });
    });
}

function deleteCategory(id, name, count) {
    const msg = count > 0
        ? `This category has <strong>${count} project(s)</strong>. Projects will remain but lose their category assignment.`
        : 'This action cannot be undone.';
    Swal.fire({
        title: `Delete "${name}"?`, html: msg, icon:'warning',
        showCancelButton:true, confirmButtonText:'Delete', confirmButtonColor:'#c9a96e',
        background:'#f0ebe3', color:'#1a1714'
    }).then(r => {
        if (!r.isConfirmed) return;
        fetch('actions.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({action:'delete_category', id}) })
        .then(r=>r.json()).then(d => {
            if (d.success) {
                document.getElementById('cat-'+id)?.remove();
                Swal.fire({ icon:'success', title:'Deleted', timer:1200, showConfirmButton:false, background:'#f0ebe3', color:'#1a1714' });
            } else {
                Swal.fire({ icon:'error', title:'Error', text:d.message, background:'#f0ebe3', color:'#1a1714' });
            }
        });
    });
}

function logout(){ Swal.fire({title:'Logout?',icon:'question',showCancelButton:true,confirmButtonText:'Yes',confirmButtonColor:'#c9a96e',background:'#f0ebe3',color:'#1a1714'}).then(r=>{if(r.isConfirmed)window.location='logout.php';}); }
</script>
</body>
</html>