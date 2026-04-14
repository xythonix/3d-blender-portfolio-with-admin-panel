<?php include 'includes/config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Projects — Ali Afzal 3D Artist</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
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
body{background:var(--bg-deep);color:var(--text);font-family:var(--font-body);overflow-x:hidden;cursor:none;}
::-webkit-scrollbar{width:4px;}::-webkit-scrollbar-thumb{background:var(--primary);}
body::before{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;opacity:0.03;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");background-size:200px 200px;}

.cursor{width:12px;height:12px;background:var(--primary);border-radius:50%;position:fixed;pointer-events:none;z-index:9999;transform:translate(-50%,-50%);box-shadow: none;}
.cursor-ring{width:40px;height:40px;border:1px solid rgba(74,55,40,0.5);border-radius:50%;position:fixed;pointer-events:none;z-index:9998;transform:translate(-50%,-50%);transition:transform 0.15s ease,width 0.2s,height 0.2s;}

canvas#bg-canvas{position:fixed;inset:0;z-index:0;pointer-events:none;}

nav{position:fixed;top:0;left:0;right:0;z-index:100;padding:20px 60px;display:flex;align-items:center;justify-content:space-between;background:rgba(250,248,245,0.99);backdrop-filter:blur(20px);border-bottom:1px solid rgba(74,55,40,0.08);}
.nav-logo{font-family:var(--font-display);font-size:1.4rem;font-weight:900;color:var(--primary);text-decoration:none;letter-spacing:0.2em;text-shadow: none;}
.nav-logo span{color:var(--secondary);}
.nav-links{display:flex;gap:40px;list-style:none;}
.nav-links a{text-decoration:none;color:var(--text-muted);font-family:var(--font-mono);font-size:0.8rem;letter-spacing:0.15em;text-transform:uppercase;transition:color 0.3s;}
.nav-links a:hover,.nav-links a.active{color:var(--primary);text-shadow: none;}

/* Page header */
.page-header{padding:160px 60px 60px;position:relative;z-index:2;border-bottom:1px solid var(--border);}
.page-label{font-family:var(--font-mono);font-size:0.75rem;color:var(--primary);letter-spacing:0.4em;text-transform:uppercase;margin-bottom:16px;display:flex;align-items:center;gap:12px;}
.page-label::before{content:'◆';font-size:0.5rem;}
.page-title{font-family:var(--font-display);font-size:clamp(3rem,6vw,5rem);font-weight:900;line-height:1;background:linear-gradient(135deg,var(--text) 0%,var(--primary) 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
.page-count{font-family:var(--font-mono);font-size:0.8rem;color:var(--text-muted);letter-spacing:0.2em;margin-top:16px;}
.page-count span{color:var(--primary);}

/* Filters bar */
.filters-bar{position:sticky;top:80px;z-index:50;padding:20px 60px;background:rgba(250,248,245,0.98);backdrop-filter:blur(20px);border-bottom:1px solid rgba(74,55,40,0.06);display:flex;gap:16px;align-items:center;flex-wrap:wrap;}
.filter-group{display:flex;gap:8px;align-items:center;}
.filter-label{font-family:var(--font-mono);font-size:0.65rem;color:var(--text-muted);letter-spacing:0.2em;text-transform:uppercase;white-space:nowrap;}
.cat-btn{padding:7px 18px;background:transparent;border:1px solid rgba(74,55,40,0.12);color:var(--text-muted);font-family:var(--font-mono);font-size:0.72rem;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;border-radius:2px;transition:all 0.25s;white-space:nowrap;100%,0% 100%);}
.cat-btn.active,.cat-btn:hover{background:rgba(74,55,40,0.1);border-color:var(--primary);color:var(--primary);box-shadow:0 0 10px rgba(74,55,40,0.12);}
.filters-right{margin-left:auto;display:flex;gap:10px;align-items:center;}
.search-wrap{position:relative;}
.search-input{padding:8px 16px 8px 36px;background:rgba(74,55,40,0.03);border:1px solid rgba(74,55,40,0.12);color:var(--text);font-family:var(--font-mono);font-size:0.75rem;border-radius:2px;outline:none;transition:border-color 0.3s;width:200px;}
.search-input:focus{border-color:var(--primary);}
.search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:0.85rem;}
.sort-select{padding:8px 14px;background:rgba(74,55,40,0.03);border:1px solid rgba(74,55,40,0.12);color:var(--text-muted);font-family:var(--font-mono);font-size:0.72rem;border-radius:2px;outline:none;cursor:pointer;}
.layout-btn{width:34px;height:34px;background:rgba(74,55,40,0.03);border:1px solid rgba(74,55,40,0.12);color:var(--text-muted);cursor:pointer;border-radius:2px;display:flex;align-items:center;justify-content:center;transition:all 0.25s;}
.layout-btn.active,.layout-btn:hover{border-color:var(--primary);color:var(--primary);}

/* Projects grid */
.projects-main{padding:60px;position:relative;z-index:2;}
.projects-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(360px,1fr));gap:28px;transition:all 0.3s;position:relative;min-height:200px;}
.projects-grid.list{grid-template-columns:1fr;}

.project-card{position:relative;border-radius:4px;overflow:hidden;cursor:pointer;text-decoration:none;background:var(--bg-card);border:1px solid var(--border);transition:transform 0.4s cubic-bezier(0.23,1,0.32,1),box-shadow 0.4s,opacity 0.3s;display:block;}
.project-card:hover{transform:translateY(-6px) scale(1.01);box-shadow: none;border-color:rgba(74,55,40,0.25);}
.project-card.hidden{opacity:0.1;pointer-events:none;transform:scale(0.95);}
.card-image{aspect-ratio:16/10;overflow:hidden;position:relative;background:linear-gradient(135deg,#e8e6f0,#d8d0f0);}
.card-image img{width:100%;height:100%;object-fit:cover;transition:transform 0.6s cubic-bezier(0.23,1,0.32,1);filter:brightness(0.85);}
.project-card:hover .card-image img{transform:scale(1.08);filter:brightness(1);}
.card-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#dde4f8 0%,#d8d0f0 50%,#e8e6f0 100%);font-family:var(--font-display);font-size:2rem;color:rgba(74,55,40,0.12);}
.card-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(250,248,245,0.98) 0%,transparent 60%);display:flex;flex-direction:column;justify-content:flex-end;padding:24px;}
.card-category{font-family:var(--font-mono);font-size:0.68rem;color:var(--primary);letter-spacing:0.2em;text-transform:uppercase;margin-bottom:8px;display:flex;align-items:center;gap:8px;}
.card-category::before{content:'';width:20px;height:1px;background:var(--primary);display:block;}
.card-title{font-family:var(--font-display);font-size:1.05rem;font-weight:700;color:var(--text);margin-bottom:10px;line-height:1.2;}
.card-meta{display:flex;gap:16px;align-items:center;}
.card-year{font-family:var(--font-mono);font-size:0.68rem;color:var(--text-muted);}
.card-arrow{margin-left:auto;width:30px;height:30px;border:1px solid rgba(74,55,40,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);transition:all 0.3s;}
.project-card:hover .card-arrow{background:var(--primary);color:var(--bg-deep);transform:rotate(45deg);}
.featured-badge{position:absolute;top:14px;right:14px;z-index:3;padding:4px 12px;background:var(--secondary);color:white;font-family:var(--font-mono);font-size:0.62rem;letter-spacing:0.15em;text-transform:uppercase;100%,0% 100%);}
.card-body{padding:18px 22px;border-top:1px solid var(--border);}
.card-desc{font-size:0.92rem;color:var(--text-muted);line-height:1.6;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.card-tags{display:flex;gap:7px;flex-wrap:wrap;margin-top:10px;}
.tag{padding:3px 10px;border:1px solid rgba(74,55,40,0.12);font-family:var(--font-mono);font-size:0.63rem;color:var(--text-muted);letter-spacing:0.1em;border-radius: 0;transition:all 0.2s;}
.project-card:hover .tag{border-color:rgba(74,55,40,0.2);color:rgba(74,55,40,0.8);}

/* LIST MODE */
.projects-grid.list .project-card{display:flex;flex-direction:row;height:120px;}
.projects-grid.list .card-image{width:180px;flex-shrink:0;aspect-ratio:auto;height:100%;}
.projects-grid.list .card-image img{height:100%;width:100%;}
.projects-grid.list .card-overlay{background:linear-gradient(to right,rgba(250,248,245,0.98),transparent);}
.projects-grid.list .card-body{flex:1;padding:20px 24px;border-top:none;border-left:1px solid var(--border);display:flex;flex-direction:column;justify-content:center;}
.projects-grid.list .card-placeholder{height:100%;font-size:1.2rem;}

/* No results */
.no-results{text-align:center;padding:80px 20px;grid-column:1/-1;}
.no-results-icon{font-size:4rem;color:rgba(74,55,40,0.12);margin-bottom:20px;}
.no-results-text{font-family:var(--font-display);font-size:1.5rem;color:var(--text-muted);}

/* Pagination */
.pagination-wrap{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:60px;padding-bottom:20px;}
.pg-btn{width:38px;height:38px;display:flex;align-items:center;justify-content:center;background:transparent;border:1px solid rgba(74,55,40,0.18);color:var(--text-muted);font-family:var(--font-mono);font-size:0.75rem;cursor:pointer;border-radius:2px;transition:all 0.2s;letter-spacing:0.05em;}
.pg-btn:hover{border-color:var(--primary);color:var(--primary);}
.pg-btn.active{background:var(--primary);border-color:var(--primary);color:var(--bg-deep);}
.pg-btn:disabled{opacity:0.3;cursor:default;pointer-events:none;}
.pg-btn.pg-arrow{width:38px;font-size:1rem;}
.pg-ellipsis{width:38px;height:38px;display:flex;align-items:center;justify-content:center;font-family:var(--font-mono);font-size:0.8rem;color:var(--text-muted);letter-spacing:0.1em;}
.pg-info{font-family:var(--font-mono);font-size:0.68rem;color:var(--text-muted);letter-spacing:0.12em;margin:0 8px;white-space:nowrap;}

/* Grid spinner overlay */
.grid-loading{position:relative;min-height:200px;}
.grid-loading::after{content:'';position:absolute;inset:0;background:rgba(250,248,245,0.75);backdrop-filter:blur(2px);z-index:10;border-radius:4px;}
.grid-spinner{display:none;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:20;flex-direction:column;align-items:center;gap:14px;}
.grid-loading .grid-spinner{display:flex;}
.spinner-ring{width:36px;height:36px;border:2px solid rgba(74,55,40,0.12);border-top-color:var(--primary);border-radius:50%;animation:spin 0.75s linear infinite;}
@keyframes spin{to{transform:rotate(360deg);}}
.spinner-label{font-family:var(--font-mono);font-size:0.65rem;color:var(--primary);letter-spacing:0.2em;text-transform:uppercase;}

footer{position:relative;z-index:2;padding:30px 60px;border-top:1px solid rgba(74,55,40,0.08);display:flex;align-items:center;justify-content:space-between;background:rgba(250,248,245,0.92);}
.footer-copy{font-family:var(--font-mono);font-size:0.75rem;color:var(--text-muted);}
.footer-copy span{color:var(--primary);}

@media(max-width:1024px){nav{padding:20px 30px;}.filters-bar{padding:16px 30px;}.projects-main{padding:40px 30px;}.page-header{padding:140px 30px 50px;}footer{padding:30px;flex-direction:column;gap:16px;text-align:center;}}
@media(max-width:768px){.nav-links{display:none;}.projects-grid{grid-template-columns:1fr;}.filters-right{flex-wrap:wrap;width:100%;}.search-input{width:150px;}}

/* ── Mobile Nav ── */
.nav-hamburger{display:none;flex-direction:column;justify-content:center;align-items:center;width:42px;height:42px;background:transparent;border:1px solid rgba(74,55,40,0.2);border-radius:2px;cursor:pointer;gap:5px;padding:0;flex-shrink:0;transition:border-color 0.25s;z-index:201;}
.nav-hamburger:hover{border-color:var(--primary);}
.nav-hamburger span{display:block;width:18px;height:1.5px;background:var(--primary);border-radius:1px;transition:transform 0.3s cubic-bezier(0.23,1,0.32,1),opacity 0.3s,width 0.3s;transform-origin:center;}
.nav-hamburger.open span:nth-child(1){transform:translateY(6.5px) rotate(45deg);}
.nav-hamburger.open span:nth-child(2){opacity:0;width:0;}
.nav-hamburger.open span:nth-child(3){transform:translateY(-6.5px) rotate(-45deg);}
.nav-drawer-backdrop{display:none;position:fixed;inset:0;background:rgba(26,23,20,0.45);backdrop-filter:blur(4px);z-index:198;opacity:0;transition:opacity 0.3s;}
.nav-drawer-backdrop.visible{opacity:1;}
.nav-drawer{position:fixed;top:0;right:0;width:min(320px,85vw);height:100dvh;background:#faf8f5;border-left:1px solid rgba(74,55,40,0.12);z-index:200;display:flex;flex-direction:column;transform:translateX(100%);transition:transform 0.35s cubic-bezier(0.23,1,0.32,1);box-shadow:-8px 0 40px rgba(26,23,20,0.12);overflow-y:auto;}
.nav-drawer.open{transform:translateX(0);}
.nav-drawer-head{display:flex;align-items:center;justify-content:space-between;padding:22px 24px;border-bottom:1px solid rgba(74,55,40,0.08);flex-shrink:0;}
.nav-drawer-logo{font-family:var(--font-display);font-size:1.25rem;font-weight:900;color:#4a3728;text-decoration:none;letter-spacing:0.2em;}
.nav-drawer-logo span{color:#8b6a4f;}
.nav-drawer-close{width:36px;height:36px;background:transparent;border:1px solid rgba(74,55,40,0.18);border-radius:2px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#7a6a5a;transition:all 0.2s;font-size:1.1rem;line-height:1;}
.nav-drawer-close:hover{border-color:#4a3728;color:#4a3728;}
.nav-drawer-links{flex:1;padding:8px 0;list-style:none;display:flex;flex-direction:column;}
.nav-drawer-links li{border-bottom:1px solid rgba(74,55,40,0.05);}
.nav-drawer-links a{display:flex;align-items:center;gap:14px;padding:17px 24px;text-decoration:none;color:#7a6a5a;font-family:var(--font-mono);font-size:0.82rem;letter-spacing:0.12em;text-transform:uppercase;transition:color 0.2s,background 0.2s,padding-left 0.2s;}
.nav-drawer-links a::before{content:'';width:3px;height:3px;border-radius:50%;background:#c9a96e;flex-shrink:0;opacity:0;transition:opacity 0.2s;}
.nav-drawer-links a:hover,.nav-drawer-links a.active{color:#1a1714;background:rgba(74,55,40,0.04);padding-left:30px;}
.nav-drawer-links a:hover::before,.nav-drawer-links a.active::before{opacity:1;}
.nav-drawer-cta{margin:16px 24px;padding:14px 24px;background:#1a1714;color:#faf8f5 !important;font-family:var(--font-mono);font-size:0.78rem;letter-spacing:0.15em;text-transform:uppercase;text-decoration:none;display:flex;align-items:center;justify-content:center;border-radius:2px;transition:background 0.25s;}
.nav-drawer-cta:hover{background:#4a3728 !important;color:#faf8f5 !important;}
.nav-drawer-cta::before{display:none !important;}
.nav-drawer-footer{padding:20px 24px;border-top:1px solid rgba(74,55,40,0.08);font-family:var(--font-mono);font-size:0.62rem;color:#b0a090;letter-spacing:0.15em;flex-shrink:0;}
@media(max-width:768px){.nav-links{display:none !important;}.nav-hamburger{display:flex !important;}nav{padding:16px 20px !important;}}
@media(max-width:480px){.nav-drawer{width:100vw;border-left:none;}}

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
.card-title { color: #fff !important; font-family: 'Cormorant Garamond', serif !important; font-size: 1.2rem !important; }
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
.pg-btn { background: #ffffff !important; border: 1px solid rgba(74,55,40,0.2) !important; color: #7a6a5a !important; border-radius: 0 !important; }
.pg-btn:hover { border-color: #4a3728 !important; color: #4a3728 !important; }
.pg-btn.active { background: #1a1714 !important; border-color: #1a1714 !important; color: #faf8f5 !important; }
.pg-info { color: #7a6a5a !important; }
.pg-ellipsis { color: #7a6a5a !important; }

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
<div class="cursor" id="cursor"></div>
<div class="cursor-ring" id="cursor-ring"></div>
<canvas id="bg-canvas"></canvas>

<nav id="navbar">
    <a href="index.php" class="nav-logo">ALI<span>.</span>AFZAL</a>
    <ul class="nav-links">
        <li><a href="index.php#featured">Work</a></li>
        <li><a href="index.php#about">About</a></li>
        <li><a href="projects.php" class="active">Projects</a></li>
        <li><a href="index.php#contact">Contact</a></li>
    </ul>
    <button class="nav-hamburger" id="nav-hamburger" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>
</nav>

<div class="nav-drawer-backdrop" id="nav-backdrop"></div>
<aside class="nav-drawer" id="nav-drawer" role="dialog" aria-modal="true" aria-label="Navigation">
    <div class="nav-drawer-head">
        <a href="index.php" class="nav-drawer-logo">ALI<span>.</span>AFZAL</a>
        <button class="nav-drawer-close" id="nav-drawer-close" aria-label="Close menu">&#x2715;</button>
    </div>
    <ul class="nav-drawer-links">
        <li><a href="index.php#featured">Work</a></li>
        <li><a href="index.php#about">About</a></li>
        <li><a href="projects.php" class="active">Projects</a></li>
        <li><a href="index.php#contact">Contact</a></li>
    </ul>
    <a href="index.php#contact" class="nav-drawer-cta">Get In Touch</a>
    <div class="nav-drawer-footer">ALI AFZAL // 3D ARTIST &amp; DESIGNER</div>
</aside>

<div class="page-header">
    <div class="page-label">Portfolio</div>
    <h1 class="page-title">All Projects</h1>
    <div class="page-count">Showing <span id="visible-count">0</span> of <span id="total-count">0</span> projects</div>
</div>

<!-- Filters -->
<div class="filters-bar" id="filters-bar">
    <div class="filter-group">
        <span class="filter-label">Filter:</span>
        <button class="cat-btn active" data-cat="all">All</button>
        <?php
        require_once 'includes/config.php';
        try {
            $db = getDB();
            $cats = $db->query("SELECT DISTINCT category FROM projects WHERE status='published' ORDER BY category")->fetchAll();
            foreach ($cats as $c) {
                echo '<button class="cat-btn" data-cat="'.htmlspecialchars($c['category']).'">'.htmlspecialchars($c['category']).'</button>';
            }
        } catch(Exception $e) { }
        ?>
    </div>
    <div class="filters-right">
        <div class="search-wrap">
            <span class="search-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
            <input type="text" class="search-input" id="search-input" placeholder="Search projects...">
        </div>
        <select class="sort-select" id="sort-select">
            <option value="default">Latest First</option>
            <option value="oldest">Oldest First</option>
            <option value="name">A–Z</option>
            <option value="views">Most Viewed</option>
            <option value="featured">Featured First</option>
        </select>
        <button class="layout-btn active" id="grid-btn" onclick="setLayout('grid')" title="Grid View">⊞</button>
        <!-- <button class="layout-btn" id="list-btn" onclick="setLayout('list')" title="List View"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg></button> -->
    </div>
</div>

<!-- Projects -->
<div class="projects-main">
    <div class="projects-grid" id="projects-grid">
        <?php
        try {
            $db = getDB();
            $all = $db->query("SELECT p.*, pi.filename as tf, pi.view_angle as va
                FROM projects p
                LEFT JOIN project_images pi ON p.id=pi.project_id AND pi.is_primary=1
                WHERE p.status='published'
                ORDER BY p.featured DESC, p.sort_order ASC, p.created_at DESC")->fetchAll();
            echo '<script>const ALL_PROJECTS = '.json_encode(array_map(fn($p) => [
                'id' => $p['id'],
                'title' => $p['title'],
                'slug' => $p['slug'],
                'category' => $p['category'],
                'description' => $p['description'] ?? '',
                'year' => $p['year'],
                'software' => $p['software'] ?? '',
                'tags' => $p['tags'] ?? '',
                'featured' => (bool)$p['featured'],
                'views' => $p['view_count'],
                'thumb' => $p['tf'] ? UPLOAD_URL.'projects/'.$p['tf'] : null,
            ], $all)).';</script>';
        } catch(Exception $e) {
            echo '<script>const ALL_PROJECTS = [];</script>';
        }
        ?>
        <div class="no-results" id="no-results" style="display:none;">
            <div class="no-results-icon">⬡</div>
            <div class="no-results-text">No projects found</div>
        </div>
        <!-- Spinner overlay (inside grid so it overlays cards) -->
        <div class="grid-spinner" id="grid-spinner">
            <div class="spinner-ring"></div>
            <div class="spinner-label">Loading</div>
        </div>
    </div>
    <!-- Pagination -->
    <div class="pagination-wrap" id="pagination-wrap" style="display:none;"></div>
</div>


<script>
// ─── Cursor
const cursor=document.getElementById('cursor'),ring=document.getElementById('cursor-ring');
document.addEventListener('mousemove',e=>{cursor.style.left=e.clientX+'px';cursor.style.top=e.clientY+'px';setTimeout(()=>{ring.style.left=e.clientX+'px';ring.style.top=e.clientY+'px';},80);});

// ─── Background
(function(){
    const canvas=document.getElementById('bg-canvas');
    const renderer=new THREE.WebGLRenderer({canvas,antialias:true,alpha:true});
    renderer.setSize(window.innerWidth,window.innerHeight);
    const scene=new THREE.Scene(),camera=new THREE.PerspectiveCamera(60,window.innerWidth/window.innerHeight,0.1,1000);
    camera.position.z=30;
    const g=new THREE.BufferGeometry();
    const p=[];
    for(let i=0;i<300;i++)p.push((Math.random()-0.5)*100,(Math.random()-0.5)*80,(Math.random()-0.5)*50);
    g.setAttribute('position',new THREE.Float32BufferAttribute(p,3));
    scene.add(new THREE.Points(g,new THREE.PointsMaterial({color:0x8b6a4f,size:0.1,transparent:true,opacity:0.3})));
    const grid=new THREE.GridHelper(200,50,0x8b6a4f,0x8b6a4f);
    grid.material.transparent=true;grid.material.opacity=0.02;grid.position.y=-20;scene.add(grid);
    (function animate(){requestAnimationFrame(animate);grid.rotation.y+=0.0005;renderer.render(scene,camera);})();
    window.addEventListener('resize',()=>{camera.aspect=window.innerWidth/window.innerHeight;camera.updateProjectionMatrix();renderer.setSize(window.innerWidth,window.innerHeight);});
})();

// ─── Projects rendering with pagination
const ITEMS_PER_PAGE = 12;
let currentFilter  = 'all';
let currentSearch  = '';
let currentSort    = 'default';
let currentLayout  = 'grid';
let currentPage    = 1;

// ── helpers ──────────────────────────────────────────────
function escHtml(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function getFiltered() {
    let arr = [...ALL_PROJECTS];
    if (currentFilter !== 'all') arr = arr.filter(p => p.category === currentFilter);
    if (currentSearch) {
        const q = currentSearch.toLowerCase();
        arr = arr.filter(p =>
            p.title.toLowerCase().includes(q) ||
            p.description.toLowerCase().includes(q) ||
            (p.tags && p.tags.toLowerCase().includes(q))
        );
    }
    if      (currentSort === 'oldest')   arr.sort((a,b) => a.id - b.id);
    else if (currentSort === 'name')     arr.sort((a,b) => a.title.localeCompare(b.title));
    else if (currentSort === 'views')    arr.sort((a,b) => b.views - a.views);
    else if (currentSort === 'featured') arr.sort((a,b) => (b.featured?1:0) - (a.featured?1:0));
    else                                 arr.sort((a,b) => b.id - a.id);
    return arr;
}

function createCard(p) {
    const tags = p.tags
        ? p.tags.split(',').slice(0,3).map(t => `<span class="tag">${escHtml(t.trim())}</span>`).join('')
        : '';
    return `
    <a href="project.php?slug=${escHtml(p.slug)}" class="project-card" data-cat="${escHtml(p.category)}"
       style="opacity:0;transform:translateY(20px);transition:opacity 0.45s,transform 0.45s;">
        ${p.featured ? '<div class="featured-badge">Featured</div>' : ''}
        <div class="card-image">
            ${p.thumb
                ? `<img src="${escHtml(p.thumb)}" alt="${escHtml(p.title)}" loading="lazy">`
                : '<div class="card-placeholder">3D</div>'}
            <div class="card-overlay"">
                
                <div class="card-title">${escHtml(p.title)}</div>
            
            </div>
        </div>
        <div class="card-body">
            <p class="card-desc">${escHtml(p.description)}</p>
            <div class="card-tags">${tags}</div>
        </div>
    </a>`;
}

// commented out category and year for now to declutter cards, can re-add later if needed
// <div class="card-category">${escHtml(p.category)}</div>
// <div class="card-meta">
//     <span class="card-year">${p.year || ''}</span>
//     <div class="card-arrow">→</div>
// </div>

// ── spinner helpers ───────────────────────────────────────
function showSpinner() {
    const grid = document.getElementById('projects-grid');
    grid.classList.add('grid-loading');
}
function hideSpinner() {
    const grid = document.getElementById('projects-grid');
    grid.classList.remove('grid-loading');
}

// ── main render ──────────────────────────────────────────
function renderProjects(animate) {
    const filtered  = getFiltered();
    const totalPages = Math.max(1, Math.ceil(filtered.length / ITEMS_PER_PAGE));

    // clamp page
    if (currentPage > totalPages) currentPage = totalPages;
    if (currentPage < 1)          currentPage = 1;

    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const slice = filtered.slice(start, start + ITEMS_PER_PAGE);

    // update header counts
    const showing = filtered.length === 0 ? 0 : Math.min(start + ITEMS_PER_PAGE, filtered.length);
    document.getElementById('visible-count').textContent = showing;
    document.getElementById('total-count').textContent   = filtered.length;

    const grid      = document.getElementById('projects-grid');
    const noResults = document.getElementById('no-results');

    // inject cards
    grid.innerHTML = '';

    if (filtered.length === 0) {
        noResults.style.display = 'block';
        grid.appendChild(noResults);
        hideSpinner();
        renderPagination(0, 1);
        return;
    }

    noResults.style.display = 'none';
    slice.forEach(p => grid.insertAdjacentHTML('beforeend', createCard(p)));
    grid.appendChild(noResults); // keep in DOM

    // stagger fade-in
    requestAnimationFrame(() => {
        grid.querySelectorAll('.project-card').forEach((card, i) => {
            setTimeout(() => {
                card.style.opacity    = '1';
                card.style.transform  = 'translateY(0)';
            }, i * 55);
        });
    });

    hideSpinner();
    renderPagination(filtered.length, totalPages);
}

// ── pagination renderer ───────────────────────────────────
function renderPagination(total, totalPages) {
    const wrap = document.getElementById('pagination-wrap');
    wrap.innerHTML = '';

    if (totalPages <= 1) {
        wrap.style.display = 'none';
        return;
    }
    wrap.style.display = 'flex';

    const p = currentPage;

    // helper to make a button
    function btn(label, page, isActive, isDisabled, isArrow) {
        const el = document.createElement('button');
        el.className = 'pg-btn' + (isActive ? ' active' : '') + (isArrow ? ' pg-arrow' : '');
        el.innerHTML  = label;
        el.disabled   = isDisabled;
        if (!isDisabled && !isActive) {
            el.addEventListener('click', () => goToPage(page));
        }
        return el;
    }
    function ellipsis() {
        const el = document.createElement('span');
        el.className   = 'pg-ellipsis';
        el.textContent = '···';
        return el;
    }

    // ← Prev
    wrap.appendChild(btn('←', p - 1, false, p === 1, true));

    // page numbers with ellipsis
    const pages = buildPageList(p, totalPages);
    pages.forEach(item => {
        if (item === '…') {
            wrap.appendChild(ellipsis());
        } else {
            wrap.appendChild(btn(item, item, item === p, false, false));
        }
    });

    // → Next
    wrap.appendChild(btn('→', p + 1, false, p === totalPages, true));

    // info label
    const info = document.createElement('span');
    info.className   = 'pg-info';
    info.textContent = `Page ${p} / ${totalPages}`;
    wrap.appendChild(info);
}

// Build a compact page number list: always show first/last, current ±1, ellipsis gaps
function buildPageList(current, total) {
    if (total <= 7) return Array.from({length: total}, (_,i) => i+1);
    const pages = new Set([1, total, current, current-1, current+1].filter(n => n >= 1 && n <= total));
    const sorted = [...pages].sort((a,b) => a-b);
    const result = [];
    for (let i = 0; i < sorted.length; i++) {
        if (i > 0 && sorted[i] - sorted[i-1] > 1) result.push('…');
        result.push(sorted[i]);
    }
    return result;
}

// ── navigate to page with spinner ────────────────────────
function goToPage(page) {
    currentPage = page;

    // show spinner overlay on grid
    showSpinner();

    // scroll grid top into view smoothly
    const filtersBar = document.getElementById('filters-bar');
    if (filtersBar) {
        const top = filtersBar.getBoundingClientRect().bottom + window.scrollY - 20;
        window.scrollTo({ top, behavior: 'smooth' });
    }

    // brief delay so spinner is visible and scroll starts
    setTimeout(() => renderProjects(true), 320);
}

// ── layout toggle ─────────────────────────────────────────
function setLayout(l) {
    currentLayout = l;
    document.getElementById('projects-grid').className = 'projects-grid' + (l === 'list' ? ' list' : '');
    document.getElementById('grid-btn').classList.toggle('active', l === 'grid');
    // list-btn commented out in HTML but guard anyway
    const lb = document.getElementById('list-btn');
    if (lb) lb.classList.toggle('active', l === 'list');
}

// ── filter / search / sort wiring ────────────────────────
document.querySelectorAll('.cat-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentFilter = btn.dataset.cat;
        currentPage   = 1;
        renderProjects();
    });
});

let searchTimer;
document.getElementById('search-input').addEventListener('input', function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        currentSearch = this.value.trim();
        currentPage   = 1;
        renderProjects();
    }, 300);
});

document.getElementById('sort-select').addEventListener('change', function() {
    currentSort = this.value;
    currentPage = 1;
    renderProjects();
});

// ── init ──────────────────────────────────────────────────
renderProjects();
</script>

<script>
(function(){
    var h=document.getElementById('nav-hamburger'),d=document.getElementById('nav-drawer'),b=document.getElementById('nav-backdrop');
    if(!h||!d||!b)return;
    function open(){d.classList.add('open');b.style.display='block';requestAnimationFrame(function(){b.classList.add('visible');});h.classList.add('open');h.setAttribute('aria-expanded','true');document.body.style.overflow='hidden';}
    function close(){d.classList.remove('open');b.classList.remove('visible');h.classList.remove('open');h.setAttribute('aria-expanded','false');document.body.style.overflow='';setTimeout(function(){b.style.display='none';},320);}
    h.addEventListener('click',function(){d.classList.contains('open')?close():open();});
    b.addEventListener('click',close);
    document.getElementById('nav-drawer-close').addEventListener('click',close);
    d.querySelectorAll('a').forEach(function(a){a.addEventListener('click',function(){setTimeout(close,120);});});
    document.addEventListener('keydown',function(e){if(e.key==='Escape')close();});
    var tx=0;
    d.addEventListener('touchstart',function(e){tx=e.touches[0].clientX;},{passive:true});
    d.addEventListener('touchend',function(e){if(e.changedTouches[0].clientX-tx>60)close();},{passive:true});
})();
</script>
</body>
</html>