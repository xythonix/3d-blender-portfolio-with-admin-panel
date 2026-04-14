<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Install — Ali Afzal 3D Portfolio</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
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
*{margin:0;padding:0;box-sizing:border-box;}
body{background:var(--bg-deep);color:var(--text);font-family:var(--font-body);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
body::before{content:'';position:fixed;inset:0;background:radial-gradient(ellipse at 50% 50%,rgba(74,55,40,0.05),transparent 60%);}
.install-wrap{max-width:700px;width:100%;position:relative;z-index:1;}
h1{font-family:var(--font-display);font-size:2rem;font-weight:900;color:var(--primary);letter-spacing:0.2em;margin-bottom:8px;}
.subtitle{font-family:var(--font-mono);font-size:0.75rem;color:var(--text-muted);letter-spacing:0.2em;margin-bottom:40px;}
.install-card{background:var(--bg-card);border:1px solid var(--border);border-radius:4px;padding:36px;margin-bottom:20px;}
.step-title{font-family:var(--font-mono);font-size:0.8rem;color:var(--primary);letter-spacing:0.2em;text-transform:uppercase;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border);}
.config-item{display:grid;grid-template-columns:180px 1fr;gap:12px;margin-bottom:14px;align-items:center;}
.config-label{font-family:var(--font-mono);font-size:0.72rem;color:var(--text-muted);letter-spacing:0.1em;}
.config-input{padding:10px 14px;background:rgba(74,55,40,0.03);border:1px solid rgba(74,55,40,0.12);color:var(--text);font-family:var(--font-mono);font-size:0.8rem;border-radius:2px;outline:none;transition:border-color 0.3s;}
.config-input:focus{border-color:var(--primary);}
.install-btn{width:100%;padding:16px;background:linear-gradient(135deg,var(--primary),#8b6a4f);color:#faf8f5;font-family:var(--font-display);font-size:0.9rem;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;border:none;cursor:pointer;border-radius:2px;margin-top:20px;transition:box-shadow 0.3s;}
.install-btn:hover{box-shadow:0 0 30px rgba(74,55,40,0.25);}
.install-btn:disabled{opacity:0.5;cursor:not-allowed;}
.log{margin-top:24px;background:var(--bg-dark);border:1px solid rgba(74,55,40,0.12);border-radius:2px;padding:20px;font-family:var(--font-mono);font-size:0.72rem;color:var(--text-muted);min-height:100px;line-height:1.8;}
.log .ok{color:#1a6e3c;}
.log .err{color:var(--secondary);}
.log .info{color:var(--primary);}
.credentials{padding:20px;background:rgba(74,55,40,0.05);border:1px solid rgba(74,55,40,0.15);border-radius:4px;margin-top:16px;}
.cred-item{font-family:var(--font-mono);font-size:0.75rem;margin-bottom:8px;}
.cred-key{color:var(--text-muted);}
.cred-val{color:var(--primary);}

/* ─── Light Theme Overrides ─── */
body { background: #faf8f5 !important; }
nav { background: rgba(250,248,245,0.96) !important; border-bottom: 1px solid rgba(74,55,40,0.1) !important; box-shadow: 0 1px 12px rgba(0,0,0,0.06) !important; }
.hero-name .line1 { color: #1a1714 !important; -webkit-text-fill-color: #1a1714 !important; }
.hero-name .line2 { color: transparent !important; -webkit-text-stroke: 1.5px #4a3728 !important; }
.hero-name .line2::after { color: #4a3728 !important; text-shadow: 0 4px 20px rgba(74,55,40,0.3) !important; }
.section-title { background: linear-gradient(135deg, #1a1714 0%, #4a3728 100%) !important; -webkit-background-clip: text !important; -webkit-text-fill-color: transparent !important; }
.project-card { background: #ffffff !important; box-shadow: 0 2px 12px rgba(0,0,0,0.06) !important; border: 1px solid rgba(74,55,40,0.12) !important; }
.project-card:hover { box-shadow: 0 8px 32px rgba(74,55,40,0.2), 0 2px 8px rgba(0,0,0,0.08) !important; }
.card-body { border-top: 1px solid rgba(74,55,40,0.1) !important; }
.card-title { color: #1a1714 !important; }
.card-desc { color: #7a6a5a !important; }
.card-overlay { background: linear-gradient(to top, rgba(15,15,35,0.88) 0%, transparent 60%) !important; }
.hero-desc { color: rgba(26,23,20,0.7) !important; }
.hero-tagline { color: #7a6a5a !important; }
.about-bio { color: rgba(26,23,20,0.78) !important; }
.contact-intro { color: rgba(26,23,20,0.72) !important; }
.about-3d-frame { background: linear-gradient(135deg, #f0ebe3, #dde4f8) !important; border: 1px solid rgba(74,55,40,0.15) !important; }
footer { background: rgba(240,235,227,0.95) !important; border-top: 1px solid rgba(74,55,40,0.1) !important; }
.contact-link { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.15) !important; }
.contact-link:hover { border-color: #4a3728 !important; box-shadow: 0 4px 16px rgba(74,55,40,0.15) !important; }
.form-input, .form-textarea { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.18) !important; color: #1a1714 !important; }
.form-input::placeholder, .form-textarea::placeholder { color: rgba(122,106,90,0.55) !important; }
.form-input:focus, .form-textarea:focus { border-color: #4a3728 !important; box-shadow: 0 0 0 3px rgba(74,55,40,0.1) !important; background: #fafbff !important; }
.categories-bar { background: rgba(255,255,255,0.8) !important; border: 1px solid rgba(74,55,40,0.1) !important; }
.skill-item { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.12) !important; }
.skill-item:hover { border-color: #4a3728 !important; }
.skill-bar { background: rgba(74,55,40,0.1) !important; }
.info-card { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.12) !important; box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important; }
.featured-badge { background: #c9a96e !important; }
.float-btn { background: rgba(250,248,245,0.95) !important; border: 1px solid rgba(74,55,40,0.2) !important; color: #7a6a5a !important; }
.float-btn:hover { border-color: #4a3728 !important; color: #4a3728 !important; }
::-webkit-scrollbar-track { background: #f0ebe3 !important; }
::-webkit-scrollbar-thumb { background: #4a3728 !important; }
.loader-logo { color: #4a3728 !important; text-shadow: none !important; }
#loader { background: #faf8f5 !important; }
.loader-text { color: #7a6a5a !important; }
.hero-stats { border-top: 1px solid rgba(74,55,40,0.12) !important; }
.stat-label { color: #7a6a5a !important; }
.scroll-hint { color: #7a6a5a !important; }
.page-header { border-bottom: 1px solid rgba(74,55,40,0.1) !important; }
.filters-bar { background: rgba(250,248,245,0.98) !important; border-bottom: 1px solid rgba(74,55,40,0.08) !important; }
.search-input { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.15) !important; color: #1a1714 !important; }
.search-input:focus { border-color: #4a3728 !important; }
.sort-select { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.15) !important; color: #7a6a5a !important; }
.layout-btn { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.15) !important; color: #7a6a5a !important; }
.card-placeholder { background: linear-gradient(135deg, #e8e6f0 0%, #d8d0f0 50%, #dde4f8 100%) !important; }
.card-placeholder .placeholder-icon { color: #4a3728 !important; }
.related-card { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.12) !important; }
.related-placeholder { color: rgba(74,55,40,0.3) !important; }
.hero-bg { background: linear-gradient(135deg, #f0ebe3 0%, #dde4f8 50%, #e8e6f0 100%) !important; }
.hero-bg::after { background: linear-gradient(to top, #faf8f5 0%, transparent 60%) !important; }
.project-title { color: #1a1714 !important; }
.viewer-main { background: radial-gradient(ellipse at center, #e8e6f0 0%, #f5f0ea 70%) !important; }
.viewer-container { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.12) !important; }
.viewer-toolbar { background: rgba(250,248,245,0.95) !important; border-top: 1px solid rgba(74,55,40,0.1) !important; }
.thumb-strip { background: rgba(240,235,227,0.8) !important; border-top: 1px solid rgba(74,55,40,0.08) !important; }
.thumb-item { background: #e8e6f0 !important; color: #7a6a5a !important; border: 2px solid transparent !important; }
.thumb-item:hover, .thumb-item.active { border-color: #4a3728 !important; box-shadow: 0 2px 8px rgba(74,55,40,0.2) !important; }
.angle-badge, .view-count-badge { background: rgba(250,248,245,0.9) !important; border: 1px solid rgba(74,55,40,0.15) !important; backdrop-filter: blur(10px) !important; }
.zoom-btn { background: rgba(250,248,245,0.95) !important; border: 1px solid rgba(74,55,40,0.2) !important; }
.content-grid .project-description p { color: rgba(26,23,20,0.72) !important; }
.share-btn { background: #faf8f5 !important; border: 1px solid rgba(74,55,40,0.15) !important; color: #7a6a5a !important; }
.share-btn:hover { border-color: #4a3728 !important; color: #4a3728 !important; }
.info-row { border-bottom: 1px solid rgba(74,55,40,0.05) !important; }


/* ═══ Interior Design Light Theme ═══ */
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

body { background: #faf8f5 !important; color: #1a1714 !important; font-family: 'Inter', sans-serif !important; }

/* ── NAV ── */
nav {
    background: rgba(250,248,245,0.97) !important;
    border-bottom: 1px solid rgba(74,55,40,0.1) !important;
    box-shadow: 0 1px 8px rgba(26,23,20,0.05) !important;
}
.nav-logo { color: #1a1714 !important; font-family: 'Cormorant Garamond', serif !important; font-size: 1.5rem !important; font-weight: 600 !important; letter-spacing: 0.08em !important; text-shadow: none !important; }
.nav-logo span { color: #8b6a4f !important; }
.nav-logo::after { background: linear-gradient(90deg, #8b6a4f, transparent) !important; }
.nav-links a { color: #7a6a5a !important; font-family: 'Inter', sans-serif !important; font-size: 0.8rem !important; letter-spacing: 0.06em !important; }
.nav-links a:hover { color: #1a1714 !important; text-shadow: none !important; }
.nav-links a::before { content: '' !important; display: none !important; }
.nav-cta { border: 1px solid #4a3728 !important; color: #4a3728 !important; border-radius: 0 !important; clip-path: none !important; padding: 9px 22px !important; }
.nav-cta:hover { background: #4a3728 !important; color: #faf8f5 !important; box-shadow: none !important; }

/* ── BUTTONS ── */
.btn-primary {
    background: #1a1714 !important;
    color: #faf8f5 !important;
    border: 1px solid #1a1714 !important;
    border-radius: 0 !important;
    clip-path: none !important;
    padding: 14px 36px !important;
    font-family: 'Inter', sans-serif !important;
    font-size: 0.78rem !important;
    font-weight: 500 !important;
    letter-spacing: 0.12em !important;
    text-transform: uppercase !important;
    box-shadow: none !important;
    transition: background 0.25s, color 0.25s !important;
}
.btn-primary::before { display: none !important; }
.btn-primary:hover { background: #4a3728 !important; border-color: #4a3728 !important; transform: none !important; box-shadow: none !important; }

.btn-secondary {
    background: transparent !important;
    color: #1a1714 !important;
    border: 1px solid #4a3728 !important;
    border-radius: 0 !important;
    clip-path: none !important;
    padding: 14px 36px !important;
    font-family: 'Inter', sans-serif !important;
    font-size: 0.78rem !important;
    font-weight: 500 !important;
    letter-spacing: 0.12em !important;
    text-transform: uppercase !important;
    box-shadow: none !important;
    transition: background 0.25s, color 0.25s !important;
}
.btn-secondary:hover { background: #4a3728 !important; color: #faf8f5 !important; border-color: #4a3728 !important; box-shadow: none !important; }

/* ── CAT / FILTER BUTTONS ── */
.cat-btn {
    background: transparent !important;
    border: 1px solid rgba(74,55,40,0.25) !important;
    color: #7a6a5a !important;
    border-radius: 0 !important;
    clip-path: none !important;
    font-family: 'Inter', sans-serif !important;
    font-size: 0.72rem !important;
    letter-spacing: 0.08em !important;
    padding: 7px 18px !important;
    transition: all 0.2s !important;
}
.cat-btn.active, .cat-btn:hover {
    background: #1a1714 !important;
    border-color: #1a1714 !important;
    color: #faf8f5 !important;
    box-shadow: none !important;
}

/* ── HERO ── */
#hero { background: #faf8f5 !important; }
.hero-badge {
    border: 1px solid rgba(74,55,40,0.25) !important;
    background: transparent !important;
    color: #7a6a5a !important;
    border-radius: 0 !important;
    animation: none !important;
    box-shadow: none !important;
}
.hero-badge .dot { background: #8b6a4f !important; animation: blink 2s ease-in-out infinite !important; }
.hero-name .line1 { color: #1a1714 !important; -webkit-text-fill-color: #1a1714 !important; }
.hero-name .line2 { color: transparent !important; -webkit-text-stroke: 1px #4a3728 !important; }
.hero-name .line2::after { color: #4a3728 !important; text-shadow: none !important; }
.hero-tagline { color: #7a6a5a !important; letter-spacing: 0.2em !important; }
.hero-tagline .sep { color: #8b6a4f !important; }
.hero-desc { color: rgba(26,23,20,0.65) !important; }
.hero-stats { border-top: 1px solid rgba(74,55,40,0.12) !important; }
.stat-num { color: #4a3728 !important; text-shadow: none !important; }
.stat-label { color: #7a6a5a !important; }
.scroll-hint { color: #7a6a5a !important; }
.scroll-line { background: linear-gradient(to bottom, #8b6a4f, transparent) !important; }

/* ── SECTION LABELS ── */
.section-label { color: #8b6a4f !important; }
.section-label::before { content: '—' !important; color: #8b6a4f !important; font-size: 0.8rem !important; }
.section-title { background: none !important; -webkit-text-fill-color: #1a1714 !important; color: #1a1714 !important; }
.section-line { background: linear-gradient(90deg, #8b6a4f, transparent) !important; }

/* ── PROJECT CARDS ── */
.project-card {
    background: #ffffff !important;
    border: 1px solid rgba(74,55,40,0.12) !important;
    border-radius: 0 !important;
    box-shadow: 0 2px 8px rgba(26,23,20,0.06) !important;
}
.project-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 8px 24px rgba(26,23,20,0.1) !important;
    border-color: rgba(74,55,40,0.3) !important;
}
.card-body { border-top: 1px solid rgba(74,55,40,0.08) !important; }
.card-title { color: #1a1714 !important; font-family: 'Cormorant Garamond', serif !important; font-size: 1.2rem !important; }
.card-desc { color: #7a6a5a !important; }
.card-category { color: #8b6a4f !important; font-family: 'DM Mono', monospace !important; }
.card-category::before { background: #8b6a4f !important; }
.card-year { color: #7a6a5a !important; }
.card-arrow { border: 1px solid rgba(74,55,40,0.25) !important; color: #4a3728 !important; border-radius: 0 !important; }
.project-card:hover .card-arrow { background: #1a1714 !important; color: #faf8f5 !important; transform: rotate(45deg) !important; }
.card-overlay { background: linear-gradient(to top, rgba(26,23,20,0.88) 0%, transparent 60%) !important; }
.card-placeholder { background: linear-gradient(135deg, #f0ebe3, #ede8e0) !important; }
.card-placeholder .placeholder-icon { color: #8b6a4f !important; }
.featured-badge { background: #4a3728 !important; border-radius: 0 !important; clip-path: none !important; }
.tag { border: 1px solid rgba(74,55,40,0.2) !important; color: #7a6a5a !important; border-radius: 0 !important; }
.project-card:hover .tag { border-color: rgba(74,55,40,0.4) !important; color: #4a3728 !important; }

/* ── CATEGORIES BAR ── */
.categories-bar { background: rgba(240,235,227,0.5) !important; border: 1px solid rgba(74,55,40,0.1) !important; border-radius: 0 !important; }

/* ── ABOUT ── */
.about-3d-frame { background: linear-gradient(135deg, #f0ebe3, #ede8e0) !important; border: 1px solid rgba(74,55,40,0.15) !important; border-radius: 0 !important; }
.about-corner { border-color: #8b6a4f !important; }
.about-bio { color: rgba(26,23,20,0.72) !important; }
.skill-item { background: #faf8f5 !important; border: 1px solid rgba(74,55,40,0.12) !important; border-radius: 0 !important; }
.skill-item:hover { border-color: #4a3728 !important; }
.skill-name { color: #7a6a5a !important; }
.skill-name span { color: #4a3728 !important; }
.skill-bar { background: rgba(74,55,40,0.08) !important; }
.skill-fill { background: linear-gradient(90deg, #4a3728, #8b6a4f) !important; }

/* ── CONTACT ── */
.contact-intro { color: rgba(26,23,20,0.65) !important; }
.contact-link { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.12) !important; border-radius: 0 !important; }
.contact-link:hover { border-color: #4a3728 !important; transform: translateX(6px) !important; box-shadow: 0 4px 16px rgba(26,23,20,0.08) !important; }
.contact-link-icon { background: rgba(74,55,40,0.07) !important; border-radius: 0 !important; color: #4a3728 !important; }
.contact-link-text { color: #7a6a5a !important; }
.contact-link strong { color: #1a1714 !important; }

/* ── SOCIAL ── */
.social-link { border: 1px solid rgba(74,55,40,0.18) !important; border-radius: 0 !important; clip-path: none !important; color: #7a6a5a !important; background: transparent !important; }
.social-link:hover { border-color: #4a3728 !important; color: #4a3728 !important; background: rgba(74,55,40,0.05) !important; box-shadow: none !important; }

/* ── FORM ── */
.form-label { color: #7a6a5a !important; font-family: 'DM Mono', monospace !important; letter-spacing: 0.1em !important; }
.form-input, .form-textarea { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.18) !important; border-radius: 0 !important; color: #1a1714 !important; font-family: 'Inter', sans-serif !important; }
.form-input::placeholder, .form-textarea::placeholder { color: rgba(122,106,90,0.45) !important; }
.form-input:focus, .form-textarea:focus { border-color: #4a3728 !important; box-shadow: 0 0 0 3px rgba(74,55,40,0.08) !important; background: #faf8f5 !important; }
.form-corner { border-color: #8b6a4f !important; }
.char-count { color: #7a6a5a !important; }

/* ── FLOATING BUTTONS ── */
.float-btn { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.2) !important; border-radius: 0 !important; clip-path: none !important; color: #7a6a5a !important; box-shadow: 0 2px 8px rgba(26,23,20,0.06) !important; }
.float-btn:hover { border-color: #4a3728 !important; color: #4a3728 !important; box-shadow: 0 4px 12px rgba(26,23,20,0.1) !important; }

/* ── FOOTER ── */
footer { background: #f0ebe3 !important; border-top: 1px solid rgba(74,55,40,0.1) !important; }
.footer-copy { color: #7a6a5a !important; }
.footer-copy span { color: #4a3728 !important; }

/* ── SCROLLBAR ── */
::-webkit-scrollbar { width: 4px !important; }
::-webkit-scrollbar-track { background: #f0ebe3 !important; }
::-webkit-scrollbar-thumb { background: #8b6a4f !important; border-radius: 0 !important; }

/* ── LOADER ── */
#loader { background: #faf8f5 !important; }
.loader-logo { color: #1a1714 !important; text-shadow: none !important; font-family: 'Cormorant Garamond', serif !important; letter-spacing: 0.15em !important; }
.loader-bar-wrap { background: rgba(74,55,40,0.1) !important; }
.loader-bar { background: linear-gradient(90deg, #4a3728, #8b6a4f) !important; }
.loader-text { color: #7a6a5a !important; }

/* ── CURSOR ── */
.cursor { background: #1a1714 !important; box-shadow: none !important; }
.cursor-ring { border-color: rgba(74,55,40,0.4) !important; }

/* ── CUSTOM OVERRIDES ── */
a { color: #4a3728; }
.section { background: transparent !important; }
#contact { background: #f5f0ea !important; }

/* ── PROJECTS PAGE SPECIFICS ── */
.page-title { background: none !important; -webkit-text-fill-color: #1a1714 !important; color: #1a1714 !important; }
.page-label { color: #8b6a4f !important; }
.page-count span { color: #4a3728 !important; }
.filters-bar { background: rgba(250,248,245,0.98) !important; border-bottom: 1px solid rgba(74,55,40,0.1) !important; }
.filter-label { color: #7a6a5a !important; }
.search-input { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.18) !important; border-radius: 0 !important; color: #1a1714 !important; }
.search-input:focus { border-color: #4a3728 !important; }
.search-icon { color: #7a6a5a !important; }
.sort-select { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.18) !important; border-radius: 0 !important; color: #7a6a5a !important; }
.layout-btn { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.18) !important; border-radius: 0 !important; color: #7a6a5a !important; }
.layout-btn.active, .layout-btn:hover { border-color: #4a3728 !important; color: #4a3728 !important; }
.no-results-icon { color: rgba(74,55,40,0.18) !important; }
.no-results-text { color: #7a6a5a !important; }
.btn-load { background: transparent !important; border: 1px solid rgba(74,55,40,0.3) !important; border-radius: 0 !important; clip-path: none !important; color: #7a6a5a !important; font-family: 'Inter', sans-serif !important; }
.btn-load:hover { border-color: #4a3728 !important; color: #1a1714 !important; background: transparent !important; box-shadow: none !important; }

/* ── PROJECT DETAIL PAGE ── */
.project-hero { background: #f0ebe3 !important; }
.hero-bg { background: linear-gradient(135deg, #f0ebe3 0%, #ede8e0 50%, #e8e0d5 100%) !important; }
.hero-bg::after { background: linear-gradient(to top, #faf8f5 0%, transparent 60%) !important; }
.project-breadcrumb { color: #7a6a5a !important; }
.project-breadcrumb a { color: #8b6a4f !important; }
.project-category-badge { background: rgba(74,55,40,0.07) !important; border: 1px solid rgba(74,55,40,0.2) !important; color: #4a3728 !important; clip-path: none !important; border-radius: 0 !important; }
.project-title { color: #1a1714 !important; }
.meta-label { color: #7a6a5a !important; }
.meta-value { color: #1a1714 !important; }
.viewer-container { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.12) !important; border-radius: 0 !important; }
.viewer-main { background: radial-gradient(ellipse at center, #ede8e0 0%, #f0ebe3 70%) !important; }
.viewer-toolbar { background: rgba(250,248,245,0.95) !important; border-top: 1px solid rgba(74,55,40,0.1) !important; }
.viewer-btn { background: transparent !important; border: 1px solid rgba(74,55,40,0.2) !important; border-radius: 0 !important; clip-path: none !important; color: #7a6a5a !important; }
.viewer-btn:hover, .viewer-btn.active { background: #1a1714 !important; border-color: #1a1714 !important; color: #faf8f5 !important; box-shadow: none !important; }
.viewer-btn.danger { border-color: rgba(107,58,42,0.3) !important; }
.viewer-btn.danger:hover { background: #6b3a2a !important; border-color: #6b3a2a !important; color: #faf8f5 !important; }
.mode-tab { border: 1px solid rgba(74,55,40,0.15) !important; color: #7a6a5a !important; border-radius: 0 !important; }
.mode-tab.active { background: #1a1714 !important; border-color: #1a1714 !important; color: #faf8f5 !important; }
.thumb-strip { background: rgba(240,235,227,0.6) !important; border-top: 1px solid rgba(74,55,40,0.08) !important; }
.thumb-item { background: #ede8e0 !important; border: 2px solid transparent !important; border-radius: 0 !important; color: #7a6a5a !important; }
.thumb-item:hover, .thumb-item.active { border-color: #4a3728 !important; box-shadow: 0 2px 8px rgba(26,23,20,0.1) !important; }
.angle-badge, .view-count-badge { background: rgba(250,248,245,0.92) !important; border: 1px solid rgba(74,55,40,0.15) !important; color: #4a3728 !important; border-radius: 0 !important; }
.zoom-btn { background: rgba(250,248,245,0.95) !important; border: 1px solid rgba(74,55,40,0.2) !important; border-radius: 0 !important; color: #4a3728 !important; }
.zoom-btn:hover { background: #1a1714 !important; border-color: #1a1714 !important; color: #faf8f5 !important; box-shadow: none !important; }
.viewer-hint { color: #7a6a5a !important; }
.content-grid { background: #faf8f5 !important; }
.project-description h2 { color: #4a3728 !important; font-family: 'Cormorant Garamond', serif !important; }
.project-description p { color: rgba(26,23,20,0.7) !important; }
.info-card { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.12) !important; border-radius: 0 !important; box-shadow: 0 2px 8px rgba(26,23,20,0.04) !important; }
.info-card-header { color: #8b6a4f !important; border-bottom: 1px solid rgba(74,55,40,0.1) !important; }
.info-key { color: #7a6a5a !important; }
.info-val { color: #1a1714 !important; }
.share-btn { background: #faf8f5 !important; border: 1px solid rgba(74,55,40,0.18) !important; border-radius: 0 !important; color: #7a6a5a !important; }
.share-btn:hover { border-color: #4a3728 !important; color: #4a3728 !important; background: rgba(74,55,40,0.04) !important; }
.related-card { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.12) !important; border-radius: 0 !important; }
.related-card:hover { box-shadow: 0 8px 24px rgba(26,23,20,0.1) !important; border-color: rgba(74,55,40,0.3) !important; }
.related-cat { color: #8b6a4f !important; }
.related-title2 { color: #1a1714 !important; font-family: 'Cormorant Garamond', serif !important; }
.related-placeholder { color: rgba(74,55,40,0.2) !important; }

</style>
</head>
<body>
<?php
// Simple installer
$installed = false;
$log = [];
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'] ?? 'localhost';
    $user = $_POST['user'] ?? 'root';
    $pass = $_POST['pass'] ?? '';
    $name = $_POST['name'] ?? 'ali3d_portfolio';

    try {
        // Try to connect
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $log[] = '<span class="ok">✔ Database connection successful</span>';

        // Create database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$name`");
        $log[] = '<span class="ok">✔ Database created: '.$name.'</span>';

        // Read and execute SQL
        $sql = file_get_contents(__DIR__ . '/setup.sql');
        // Remove USE statement since we already selected
        $sql = preg_replace('/USE\s+`[^`]+`;\s*/i', '', $sql);
        $sql = preg_replace('/CREATE\s+DATABASE[^;]+;\s*/i', '', $sql);
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($statements as $stmt) {
            if ($stmt) {
                try { $pdo->exec($stmt); } catch(Exception $e) {
                    // Ignore duplicate key errors for INSERT IGNORE
                    if (strpos($e->getMessage(), 'Duplicate') === false) {
                        $log[] = '<span class="err">✖ '.$e->getMessage().'</span>';
                    }
                }
            }
        }
        $log[] = '<span class="ok">✔ Tables created and default data inserted</span>';

        // Update config.php
        $configPath = __DIR__ . '/../includes/config.php';
        $config = file_get_contents($configPath);
        $config = preg_replace("/define\('DB_HOST', '[^']*'\)/", "define('DB_HOST', '$host')", $config);
        $config = preg_replace("/define\('DB_USER', '[^']*'\)/", "define('DB_USER', '$user')", $config);
        $config = preg_replace("/define\('DB_PASS', '[^']*'\)/", "define('DB_PASS', '$pass')", $config);
        $config = preg_replace("/define\('DB_NAME', '[^']*'\)/", "define('DB_NAME', '$name')", $config);
        file_put_contents($configPath, $config);
        $log[] = '<span class="ok">✔ Configuration file updated</span>';

        // Create upload dirs
        $dirs = ['projects', 'thumbnails'];
        foreach ($dirs as $d) {
            $path = __DIR__ . '/../uploads/' . $d;
            if (!is_dir($path)) mkdir($path, 0755, true);
        }
        $log[] = '<span class="ok">✔ Upload directories created</span>';

        $log[] = '<span class="info">━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</span>';
        $log[] = '<span class="ok">✔ Installation complete!</span>';
        $installed = true;

    } catch (Exception $e) {
        $log[] = '<span class="err">✖ Error: '.$e->getMessage().'</span>';
        $error = true;
    }
}
?>

<div class="install-wrap">
    <h1>Portfolio Setup</h1>
    <div class="subtitle">Ali Afzal 3D Portfolio — Database Installation</div>

    <?php if (!$installed): ?>
    <div class="install-card">
        <div class="step-title">// Database Configuration</div>
        <form method="POST">
            <div class="config-item">
                <label class="config-label">Database Host</label>
                <input type="text" name="host" class="config-input" value="localhost" placeholder="localhost">
            </div>
            <div class="config-item">
                <label class="config-label">Database User</label>
                <input type="text" name="user" class="config-input" value="root" placeholder="root">
            </div>
            <div class="config-item">
                <label class="config-label">Database Password</label>
                <input type="password" name="pass" class="config-input" value="" placeholder="(blank for XAMPP default)">
            </div>
            <div class="config-item">
                <label class="config-label">Database Name</label>
                <input type="text" name="name" class="config-input" value="ali3d_portfolio" placeholder="ali3d_portfolio">
            </div>
            <button type="submit" class="install-btn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> Run Installation</button>
        </form>
    </div>
    <?php endif; ?>

    <?php if (!empty($log)): ?>
    <div class="install-card">
        <div class="step-title">// Installation Log</div>
        <div class="log"><?= implode('<br>', $log) ?></div>
        <?php if ($installed): ?>
        <div class="credentials">
            <div style="font-family:var(--font-mono);font-size:0.8rem;color:var(--primary);margin-bottom:12px;letter-spacing:0.2em;">ADMIN CREDENTIALS</div>
            <div class="cred-item"><span class="cred-key">URL: </span><span class="cred-val">http://localhost/ali3d/admin/login.php</span></div>
            <div class="cred-item"><span class="cred-key">Username: </span><span class="cred-val">ali_afzal</span></div>
            <div class="cred-item"><span class="cred-key">Password: </span><span class="cred-val">Ali@3DArtist2024</span></div>
        </div>
        <div style="display:flex;gap:12px;margin-top:20px;flex-wrap:wrap;">
            <a href="../index.php" style="flex:1;padding:14px;background:linear-gradient(135deg,var(--primary),#8b6a4f);color:#faf8f5;font-family:var(--font-display);font-size:0.8rem;font-weight:700;letter-spacing:0.1em;text-align:center;text-decoration:none;border-radius:2px;">Visit Portfolio →</a>
            <a href="../admin/login.php" style="flex:1;padding:14px;background:rgba(74,55,40,0.08);border:1px solid rgba(74,55,40,0.15);color:var(--primary);font-family:var(--font-display);font-size:0.8rem;font-weight:700;letter-spacing:0.1em;text-align:center;text-decoration:none;border-radius:2px;">Go to Admin →</a>
        </div>
        <div style="margin-top:16px;padding:12px;background:rgba(255,0,110,0.08);border:1px solid rgba(201,169,110,0.15);border-radius:2px;font-family:var(--font-mono);font-size:0.7rem;color:var(--secondary);">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> IMPORTANT: Delete the /install/ folder after installation for security.
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (!$_POST): ?>
    <div style="font-family:var(--font-mono);font-size:0.7rem;color:var(--text-muted);text-align:center;padding:16px;">
        Requirements: PHP 8.0+ · MySQL 5.7+ · GD Extension · XAMPP/WAMP/LAMP
    </div>
    <?php endif; ?>
</div>
</body>
</html>
