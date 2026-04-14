<?php include 'includes/config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ali Afzal — 3D Artist</title>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<!-- Three.js for 3D background -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<!-- GSAP for animations -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Particles.js -->
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

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

*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

html { scroll-behavior: smooth; overflow-x: hidden; }

body {
    background: var(--bg-deep);
    color: var(--text);
    font-family: var(--font-body);
    overflow-x: hidden;
    cursor: none;
}

/* ─── Custom Cursor ─── */
.cursor {
    width: 12px; height: 12px;
    background: var(--primary);
    border-radius: 50%;
    position: fixed; pointer-events: none; z-index: 9999;
    transform: translate(-50%,-50%);
    transition: transform 0.1s, background 0.2s;
    box-shadow: none;
}
.cursor-ring {
    width: 40px; height: 40px;
    border: 1px solid rgba(74,55,40,0.35);
    border-radius: 50%;
    position: fixed; pointer-events: none; z-index: 9998;
    transform: translate(-50%,-50%);
    transition: transform 0.15s ease, width 0.2s, height 0.2s, border-color 0.2s;
}
body:hover .cursor-ring { opacity: 1; }

/* ─── Scrollbar ─── */
::-webkit-scrollbar { width: 4px; }
::-webkit-scrollbar-track { background: var(--bg-deep); }
::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 2px; }

/* ─── Noise Texture overlay ─── */
body::before {
    content: '';
    position: fixed; inset: 0; z-index: 0; pointer-events: none;
    opacity: 0.03;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
    background-size: 200px 200px;
}

/* ─── 3D Canvas Background ─── */
#bg-canvas {
    position: fixed; inset: 0; z-index: 0;
    pointer-events: none;
    display:none; /* Hidden by default, can be enabled for 3D background */
}

/* ─── NAVIGATION ─── */
nav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    padding: 20px 60px;
    display: flex; align-items: center; justify-content: space-between;
    background: linear-gradient(180deg, rgba(250,248,245,0.95) 0%, transparent 100%);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(74,55,40,0.05);
}
.nav-logo {
    font-family: var(--font-display);
    font-size: 1.4rem; font-weight: 900;
    color: var(--primary);
    text-decoration: none;
    letter-spacing: 0.2em;
    text-shadow: none;
    position: relative;
}
.nav-logo span { color: var(--secondary); }
.nav-logo::after {
    content: '';
    position: absolute; bottom: -4px; left: 0; width: 100%; height: 1px;
    background: linear-gradient(90deg, var(--primary), transparent);
}
.nav-links { display: flex; gap: 40px; list-style: none; }
.nav-links a {
    text-decoration: none; color: var(--text-muted);
    font-family: var(--font-mono); font-size: 0.8rem;
    letter-spacing: 0.15em; text-transform: uppercase;
    transition: color 0.3s, text-shadow 0.3s;
    position: relative;
}
.nav-links a::before {
    content: '//';
    color: var(--primary); opacity: 0;
    margin-right: 6px;
    transition: opacity 0.3s;
}
.nav-links a:hover { color: var(--primary); text-shadow: none; }
.nav-links a:hover::before { opacity: 1; }
.nav-cta {
    padding: 10px 24px;
    border: 1px solid var(--primary);
    color: var(--primary) !important;
    border-radius: 2px;
    transition: background 0.3s, box-shadow 0.3s !important;
    100%, 0% 100%);
}
.nav-cta:hover { background: rgba(74,55,40,0.1) !important; box-shadow: none; }

/* ─── HERO SECTION ─── */
#hero {
    min-height: 100vh;
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
    padding: 120px 60px 80px;
}
#particles-js {
    position: absolute; inset: 0; z-index: 1;
}
.hero-content {
    position: relative; z-index: 2;
    text-align: center; max-width: 900px;
}
.hero-badge {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 8px 20px;
    border: 1px solid rgba(74,55,40,0.2);
    border-radius: 0;font-family: var(--font-mono); font-size: 0.75rem;
    color: var(--primary); letter-spacing: 0.2em; text-transform: uppercase;
    margin-bottom: 30px;
    background: rgba(74,55,40,0.05);
    animation: pulse-border 2s ease-in-out infinite;
}
@keyframes pulse-border {
    0%, 100% { border-color: rgba(74,55,40,0.2); box-shadow: 0 0 10px rgba(74,55,40,0.1); }
    50% { border-color: rgba(74,55,40,0.7); box-shadow: none; }
}
.hero-badge .dot {
    width: 8px; height: 8px; background: var(--primary);
    border-radius: 50%; animation: blink 1.5s ease-in-out infinite;
}
@keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0.2;} }

.hero-name {
    font-family: var(--font-display);
    font-size: clamp(3rem, 8vw, 7rem);
    font-weight: 900; line-height: 0.9;
    letter-spacing: -0.02em;
    margin-bottom: 20px;
    opacity: 0;
}
.hero-name .line1 { display: block; color: var(--text); }
.hero-name .line2 {
    display: block; color: transparent;
    -webkit-text-stroke: 1px var(--primary);
    text-shadow: none;
    position: relative;
}
.hero-name .line2::after {
    content: attr(data-text);
    position: absolute; left: 0; top: 0; width: 100%;
    color: var(--primary);
    text-shadow: none;
    animation: glitch-text 4s ease-in-out infinite;
    }
@keyframes glitch-text {
    0%,90%,100% { transform: translateX(0); opacity: 0; }
    92% { transform: translateX(-4px); opacity: 1; }
    94% { transform: translateX(4px); }
    96% { transform: translateX(-2px); }
    98% { transform: translateX(0); opacity: 0; }
}

.hero-tagline {
    font-family: var(--font-mono); font-size: 1rem;
    color: var(--text-muted); letter-spacing: 0.3em;
    text-transform: uppercase; margin-bottom: 20px;
    opacity: 0;
}
.hero-tagline .sep { color: var(--secondary); margin: 0 12px; }

.hero-desc {
    font-size: 1.2rem; color: rgba(26,23,20,0.65);
    max-width: 600px; margin: 0 auto 50px;
    line-height: 1.7; opacity: 0;
}

.hero-buttons {
    display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;
    opacity: 0;
}
.btn-primary {
    padding: 16px 40px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: var(--bg-deep); font-family: var(--font-display);
    font-size: 0.85rem; font-weight: 700; letter-spacing: 0.15em;
    text-transform: uppercase; text-decoration: none;
    border: none; cursor: pointer;
    100%, 0% 100%);
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative; overflow: hidden;
}
.btn-primary::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, transparent 40%, rgba(74,55,40,0.2));
    transform: translateX(-100%);
    transition: transform 0.4s;
}
.btn-primary:hover { transform: translateY(-3px); box-shadow: none; }
.btn-primary:hover::before { transform: translateX(100%); }

.btn-secondary {
    padding: 16px 40px;
    background: transparent;
    border: 1px solid rgba(74,55,40,0.15);
    color: var(--text);
    font-family: var(--font-display); font-size: 0.85rem;
    font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase;
    text-decoration: none; cursor: pointer;
    100%, 0% 100%);
    transition: border-color 0.3s, color 0.3s, box-shadow 0.3s;
}
.btn-secondary:hover {
    border-color: var(--primary); color: var(--primary);
    box-shadow: none;
}

/* Hero Stats */
.hero-stats {
    display: flex; justify-content: center; gap: 60px;
    margin-top: 80px; padding-top: 40px;
    border-top: 1px solid rgba(74,55,40,0.1);
    opacity: 0;
}
.stat-item { text-align: center; }
.stat-num {
    font-family: var(--font-display); font-size: 2.5rem; font-weight: 900;
    color: var(--primary); display: block;
    text-shadow: none;
}
.stat-label {
    font-family: var(--font-mono); font-size: 0.7rem;
    color: var(--text-muted); letter-spacing: 0.2em; text-transform: uppercase;
}

/* Scroll indicator */
.scroll-hint {
    position: absolute; bottom: 40px; left: 50%;
    transform: translateX(-50%);
    display: flex; flex-direction: column; align-items: center; gap: 10px;
    color: var(--text-muted); font-family: var(--font-mono); font-size: 0.7rem;
    letter-spacing: 0.2em; opacity: 0.6; z-index: 2;
    animation: float-up 2s ease-in-out infinite;
}
@keyframes float-up { 0%,100%{transform:translateX(-50%) translateY(0);} 50%{transform:translateX(-50%) translateY(-8px);} }
.scroll-line {
    width: 1px; height: 40px;
    background: linear-gradient(to bottom, var(--primary), transparent);
    animation: line-down 1.5s ease-in-out infinite;
}
@keyframes line-down { 0%,100%{height:40px;opacity:1;} 50%{height:20px;opacity:0.5;} }

/* ─── SECTION HEADER ─── */
.section { position: relative; z-index: 2; padding: 120px 60px; }
.section-header {
    margin-bottom: 80px;
}
.section-label {
    font-family: var(--font-mono); font-size: 0.75rem;
    color: var(--primary); letter-spacing: 0.4em;
    text-transform: uppercase; margin-bottom: 16px;
    display: flex; align-items: center; gap: 12px;
}
.section-label::before { content: '◆'; font-size: 0.5rem; }
.section-title {
    font-family: var(--font-display);
    font-size: clamp(2rem, 4vw, 3.5rem);
    font-weight: 900; line-height: 1;
    background: linear-gradient(135deg, var(--text) 0%, var(--primary) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.section-line {
    width: 80px; height: 2px; margin-top: 20px;
    background: linear-gradient(90deg, var(--primary), transparent);
}

/* ─── FEATURED WORK ─── */
#featured { background: linear-gradient(180deg, transparent, rgba(74,55,40,0.02), transparent); }

.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 30px;
}

.project-card {
    position: relative; border-radius: 4px; overflow: hidden;
    cursor: pointer; text-decoration: none;
    background: var(--bg-card);
    border: 1px solid var(--border);
    transition: transform 0.4s cubic-bezier(0.23,1,0.32,1), box-shadow 0.4s;
    display: block;
}
.project-card:hover {
    transform: translateY(-8px) scale(1.01);
    box-shadow: none;
    border-color: rgba(74,55,40,0.25);
}
.card-image {
    aspect-ratio: 16/10; overflow: hidden; position: relative;
    background: linear-gradient(135deg, #e8e6f0, #d8d0f0);
}
.card-image img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.23,1,0.32,1);
    filter: brightness(0.85);
}
.project-card:hover .card-image img { transform: scale(1.08); filter: brightness(1); }

/* Placeholder for cards without images */
.card-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #dde4f8 0%, #d8d0f0 50%, #e8e6f0 100%);
    position: relative; overflow: hidden;
}
.card-placeholder::before {
    content: '';
    position: absolute; inset: 0;
    background: repeating-linear-gradient(
        45deg, transparent, transparent 20px,
        rgba(74,55,40,0.02) 20px, rgba(74,55,40,0.02) 21px
    );
}
.card-placeholder .placeholder-icon {
    font-size: 4rem; opacity: 0.2;
    font-family: var(--font-display);
    color: var(--primary);
    z-index: 1;
}

.card-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(250,248,245,0.98) 0%, transparent 60%);
    display: flex; flex-direction: column; justify-content: flex-end;
    padding: 24px;
}
.card-category {
    font-family: var(--font-mono); font-size: 0.7rem;
    color: var(--primary); letter-spacing: 0.2em; text-transform: uppercase;
    margin-bottom: 8px;
    display: flex; align-items: center; gap: 8px;
}
.card-category::before { content: ''; width: 20px; height: 1px; background: var(--primary); display: block; }
.card-title {
    font-family: var(--font-display); font-size: 1.1rem; font-weight: 700;
    color: var(--text); margin-bottom: 10px; line-height: 1.2;
}
.card-meta {
    display: flex; gap: 16px; align-items: center;
}
.card-year {
    font-family: var(--font-mono); font-size: 0.7rem;
    color: var(--text-muted);
}
.card-arrow {
    margin-left: auto; width: 32px; height: 32px;
    border: 1px solid rgba(74,55,40,0.2); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: var(--primary);
    transition: background 0.3s, transform 0.3s;
}
.project-card:hover .card-arrow {
    background: var(--primary); color: var(--bg-deep);
    transform: rotate(45deg);
}
.featured-badge {
    position: absolute; top: 16px; right: 16px; z-index: 3;
    padding: 4px 12px; background: var(--secondary);
    color: white; font-family: var(--font-mono); font-size: 0.65rem;
    letter-spacing: 0.15em; text-transform: uppercase;
    100%, 0% 100%);
}

.card-body { padding: 20px 24px; border-top: 1px solid var(--border); }
.card-desc {
    font-size: 0.95rem; color: var(--text-muted); line-height: 1.6;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden;
}
.card-tags { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 12px; }
.tag {
    padding: 3px 10px;
    border: 1px solid rgba(74,55,40,0.12);
    font-family: var(--font-mono); font-size: 0.65rem;
    color: var(--text-muted); letter-spacing: 0.1em;
    border-radius: 0;transition: border-color 0.2s, color 0.2s;
}
.project-card:hover .tag { border-color: rgba(74,55,40,0.35); color: var(--primary); }

.view-all-wrap { text-align: center; margin-top: 60px; }

/* ─── CATEGORIES FILTER ─── */
.categories-bar {
    display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 50px;
    padding: 24px; background: rgba(74,55,40,0.03);
    border: 1px solid var(--border); border-radius: 4px;
}
.cat-btn {
    padding: 8px 20px;
    background: transparent;
    border: 1px solid rgba(74,55,40,0.15);
    color: var(--text-muted);
    font-family: var(--font-mono); font-size: 0.75rem;
    letter-spacing: 0.1em; text-transform: uppercase;
    cursor: pointer; border-radius: 2px;
    transition: all 0.3s;
    100%, 0% 100%);
}
.cat-btn.active, .cat-btn:hover {
    background: rgba(74,55,40,0.1);
    border-color: var(--primary);
    color: var(--primary);
    box-shadow: none;
}

/* ─── ABOUT SECTION ─── */
#about {
    display: grid; grid-template-columns: 1fr 1fr; gap: 100px; align-items: center;
}
.about-visual {
    position: relative;
}
.about-3d-frame {
    aspect-ratio: 1; border-radius: 4px;
    background: linear-gradient(135deg, #f0ebe3, #ede9f5);
    border: 1px solid var(--border);
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
}
#about-canvas { width: 100%; height: 100%; }
.about-corner { position: absolute; width: 30px; height: 30px; }
.about-corner.tl { top: 10px; left: 10px; border-top: 2px solid var(--primary); border-left: 2px solid var(--primary); }
.about-corner.tr { top: 10px; right: 10px; border-top: 2px solid var(--primary); border-right: 2px solid var(--primary); }
.about-corner.bl { bottom: 10px; left: 10px; border-bottom: 2px solid var(--primary); border-left: 2px solid var(--primary); }
.about-corner.br { bottom: 10px; right: 10px; border-bottom: 2px solid var(--primary); border-right: 2px solid var(--primary); }

.about-text .section-header { margin-bottom: 40px; }
.about-bio {
    font-size: 1.05rem; color: rgba(26,23,20,0.75);
    line-height: 1.9; margin-bottom: 40px;
}
.skills-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 40px; }
.skill-item {
    padding: 14px 18px;
    border: 1px solid var(--border);
    border-radius: 2px;
    background: rgba(74,55,40,0.03);
    transition: border-color 0.3s;
}
.skill-item:hover { border-color: var(--primary); }
.skill-name {
    font-family: var(--font-mono); font-size: 0.75rem;
    color: var(--text-muted); letter-spacing: 0.1em;
    display: flex; justify-content: space-between; margin-bottom: 8px;
}
.skill-name span { color: var(--primary); }
.skill-bar { height: 2px; background: rgba(74,55,40,0.08); border-radius: 1px; }
.skill-fill {
    height: 100%; background: linear-gradient(90deg, var(--primary), var(--accent));
    border-radius: 1px; width: 0%;
    transition: width 1.5s cubic-bezier(0.23,1,0.32,1);
}

/* ─── CONTACT SECTION ─── */
#contact {
    background: linear-gradient(180deg, transparent, rgba(139,106,79,0.05), transparent);
}
.contact-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 80px; align-items: start; }
.contact-info { }
.contact-intro { font-size: 1.05rem; color: rgba(26,23,20,0.65); line-height: 1.8; margin-bottom: 40px; }
.contact-links { display: flex; flex-direction: column; gap: 20px; }
.contact-link {
    display: flex; align-items: center; gap: 16px;
    padding: 16px 20px;
    border: 1px solid var(--border); border-radius: 4px;
    color: var(--text); text-decoration: none;
    background: var(--bg-card);
    transition: border-color 0.3s, transform 0.3s, box-shadow 0.3s;
}
.contact-link:hover {
    border-color: var(--primary); transform: translateX(8px);
    box-shadow: none;
}
.contact-link-icon {
    width: 40px; height: 40px; background: rgba(74,55,40,0.1);
    border-radius: 2px; display: flex; align-items: center; justify-content: center;
    color: var(--primary); font-size: 1.2rem; flex-shrink: 0;
}
.contact-link-text { font-size: 0.95rem; color: var(--text-muted); }
.contact-link strong { color: var(--text); display: block; }

/* Contact Form */
.contact-form { position: relative; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
.form-group { position: relative; }
.form-group.full { grid-column: 1/-1; }
.form-label {
    display: block; font-family: var(--font-mono); font-size: 0.7rem;
    color: var(--text-muted); letter-spacing: 0.2em; text-transform: uppercase;
    margin-bottom: 8px;
}
.form-input, .form-textarea {
    width: 100%; padding: 14px 18px;
    background: rgba(74,55,40,0.03);
    border: 1px solid rgba(74,55,40,0.12);
    border-radius: 2px; color: var(--text);
    font-family: var(--font-body); font-size: 1rem;
    outline: none; transition: border-color 0.3s, box-shadow 0.3s, background 0.3s;
    resize: none;
}
.form-input::placeholder, .form-textarea::placeholder { color: rgba(122,106,90,0.5); }
.form-input:focus, .form-textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(74,55,40,0.08);
    background: rgba(74,55,40,0.06);
}
.form-textarea { min-height: 150px; }

.form-corner { position: absolute; width: 12px; height: 12px; pointer-events: none; }
.form-corner.tl { top: 28px; left: 0; border-top: 1px solid var(--primary); border-left: 1px solid var(--primary); }
.form-corner.br { bottom: 0; right: 0; border-bottom: 1px solid var(--primary); border-right: 1px solid var(--primary); }

.char-count {
    position: absolute; bottom: 10px; right: 14px;
    font-family: var(--font-mono); font-size: 0.65rem; color: var(--text-muted);
}

/* Social bar */
.social-bar {
    display: flex; gap: 16px; margin-top: 40px;
}
.social-link {
    width: 44px; height: 44px;
    border: 1px solid var(--border); border-radius: 2px;
    display: flex; align-items: center; justify-content: center;
    color: var(--text-muted); text-decoration: none; font-size: 1.1rem;
    transition: all 0.3s;
    background: rgba(74,55,40,0.02);
    100%, 0% 100%);
}
.social-link:hover { border-color: var(--primary); color: var(--primary); box-shadow: none; background: rgba(74,55,40,0.08); }

/* ─── FOOTER ─── */
footer {
    position: relative; z-index: 2;
    padding: 40px 60px;
    border-top: 1px solid rgba(74,55,40,0.08);
    display: flex; align-items: center; justify-content: space-between;
    background: rgba(250,248,245,0.9); backdrop-filter: blur(10px);
}
.footer-copy {
    font-family: var(--font-mono); font-size: 0.75rem; color: var(--text-muted);
}
.footer-copy span { color: var(--primary); }

/* ─── FLOATING ELEMENTS ─── */
.floating-widget {
    position: fixed; bottom: 40px; right: 40px; z-index: 50;
    display: flex; flex-direction: column; gap: 12px;
}
.float-btn {
    width: 48px; height: 48px;
    background: rgba(250,248,245,0.95); border: 1px solid rgba(74,55,40,0.2);
    border-radius: 2px; display: flex; align-items: center; justify-content: center;
    color: var(--text-muted); cursor: pointer; font-size: 1.1rem;
    transition: all 0.3s; text-decoration: none;
    100%, 0% 100%);
    backdrop-filter: blur(10px);
}
.float-btn:hover { border-color: var(--primary); color: var(--primary); box-shadow: none; }

/* ─── LOADING SCREEN ─── */
#loader {
    position: fixed; inset: 0; z-index: 1000;
    background: var(--bg-deep);
    display: flex; align-items: center; justify-content: center;
    flex-direction: column; gap: 30px;
}
.loader-logo {
    font-family: var(--font-display); font-size: 2rem; font-weight: 900;
    color: var(--primary); letter-spacing: 0.3em; text-shadow: none;
}
.loader-bar-wrap {
    width: 300px; height: 2px; background: rgba(74,55,40,0.08); border-radius: 1px;
}
.loader-bar {
    height: 100%; width: 0%; background: linear-gradient(90deg, var(--primary), var(--accent));
    border-radius: 1px; transition: width 0.1s linear;
}
.loader-text {
    font-family: var(--font-mono); font-size: 0.7rem; color: var(--text-muted);
    letter-spacing: 0.2em; text-transform: uppercase;
}

/* ─── ANIMATIONS ─── */
.reveal { opacity: 0; transform: translateY(40px); transition: opacity 0.8s, transform 0.8s; }
.reveal.visible { opacity: 1; transform: translateY(0); }

@media (max-width: 1024px) {
    nav { padding: 20px 30px; }
    .section { padding: 80px 30px; }
    #hero { padding: 120px 30px 80px; }
    #about { grid-template-columns: 1fr; gap: 60px; }
    .contact-grid { grid-template-columns: 1fr; gap: 50px; }
    .hero-stats { gap: 40px; }
    footer { padding: 30px; flex-direction: column; gap: 16px; text-align: center; }
}
@media (max-width: 768px) {
    .nav-links { display: none; }
    .projects-grid { grid-template-columns: 1fr; }
    .form-grid { grid-template-columns: 1fr; }
    .skills-grid { grid-template-columns: 1fr; }
    .hero-stats { flex-direction: column; gap: 24px; }
}

/* ─────────────────────────────────────────────────────────────
   MOBILE NAV — hamburger + slide-in drawer
───────────────────────────────────────────────────────────── */
.nav-hamburger {
    display: none;
    flex-direction: column; justify-content: center; align-items: center;
    width: 42px; height: 42px;
    background: transparent;
    border: 1px solid rgba(74,55,40,0.2);
    border-radius: 2px;
    cursor: pointer; gap: 5px; padding: 0;
    flex-shrink: 0;
    transition: border-color 0.25s;
    z-index: 201;
}
.nav-hamburger:hover { border-color: var(--primary); }
.nav-hamburger span {
    display: block; width: 18px; height: 1.5px;
    background: var(--primary); border-radius: 1px;
    transition: transform 0.3s cubic-bezier(0.23,1,0.32,1), opacity 0.3s, width 0.3s;
    transform-origin: center;
}
.nav-hamburger.open span:nth-child(1) { transform: translateY(6.5px) rotate(45deg); }
.nav-hamburger.open span:nth-child(2) { opacity: 0; width: 0; }
.nav-hamburger.open span:nth-child(3) { transform: translateY(-6.5px) rotate(-45deg); }
.nav-drawer-backdrop {
    display: none; position: fixed; inset: 0;
    background: rgba(26,23,20,0.45); backdrop-filter: blur(4px);
    z-index: 198; opacity: 0; transition: opacity 0.3s;
}
.nav-drawer-backdrop.visible { opacity: 1; }
.nav-drawer {
    position: fixed; top: 0; right: 0;
    width: min(320px, 85vw); height: 100dvh;
    background: #faf8f5;
    border-left: 1px solid rgba(74,55,40,0.12);
    z-index: 200;
    display: flex; flex-direction: column;
    transform: translateX(100%);
    transition: transform 0.35s cubic-bezier(0.23,1,0.32,1);
    box-shadow: -8px 0 40px rgba(26,23,20,0.12);
    overflow-y: auto;
}
.nav-drawer.open { transform: translateX(0); }
.nav-drawer-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 22px 24px; border-bottom: 1px solid rgba(74,55,40,0.08); flex-shrink: 0;
}
.nav-drawer-logo {
    font-family: var(--font-display); font-size: 1.25rem; font-weight: 900;
    color: #4a3728; text-decoration: none; letter-spacing: 0.2em;
}
.nav-drawer-logo span { color: #8b6a4f; }
.nav-drawer-close {
    width: 36px; height: 36px; background: transparent;
    border: 1px solid rgba(74,55,40,0.18); border-radius: 2px;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    color: #7a6a5a; transition: all 0.2s; font-size: 1.1rem; line-height: 1;
}
.nav-drawer-close:hover { border-color: #4a3728; color: #4a3728; }
.nav-drawer-links { flex: 1; padding: 8px 0; list-style: none; display: flex; flex-direction: column; }
.nav-drawer-links li { border-bottom: 1px solid rgba(74,55,40,0.05); }
.nav-drawer-links a {
    display: flex; align-items: center; gap: 14px;
    padding: 17px 24px; text-decoration: none; color: #7a6a5a;
    font-family: var(--font-mono); font-size: 0.82rem;
    letter-spacing: 0.12em; text-transform: uppercase;
    transition: color 0.2s, background 0.2s, padding-left 0.2s;
}
.nav-drawer-links a::before {
    content: ''; width: 3px; height: 3px; border-radius: 50%;
    background: #c9a96e; flex-shrink: 0; opacity: 0; transition: opacity 0.2s;
}
.nav-drawer-links a:hover, .nav-drawer-links a.active {
    color: #1a1714; background: rgba(74,55,40,0.04); padding-left: 30px;
}
.nav-drawer-links a:hover::before, .nav-drawer-links a.active::before { opacity: 1; }
.nav-drawer-cta {
    margin: 16px 24px; padding: 14px 24px;
    background: #1a1714; color: #faf8f5 !important;
    font-family: var(--font-mono); font-size: 0.78rem;
    letter-spacing: 0.15em; text-transform: uppercase;
    text-decoration: none; display: flex; align-items: center;
    justify-content: center; border-radius: 2px; transition: background 0.25s;
}
.nav-drawer-cta:hover { background: #4a3728 !important; color: #faf8f5 !important; }
.nav-drawer-cta::before { display: none !important; }
.nav-drawer-footer {
    padding: 20px 24px; border-top: 1px solid rgba(74,55,40,0.08);
    font-family: var(--font-mono); font-size: 0.62rem;
    color: #b0a090; letter-spacing: 0.15em; flex-shrink: 0;
}
@media (max-width: 768px) {
    .nav-links { display: none !important; }
    .nav-hamburger { display: flex !important; }
    nav { padding: 16px 20px !important; }
}
@media (max-width: 480px) {
    .nav-drawer { width: 100vw; border-left: none; }
}


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
.nav-cta { border: 1px solid #4a3728 !important; color: #4a3728 ; border-radius: 0 !important; clip-path: none !important; padding: 9px 22px !important; }
.nav-cta:hover {color: #ffffff !important;background: #ffffff2d !important; box-shadow:0 1px 8px rgba(112, 68, 24, 0.2) !important; }

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

<!-- Loading Screen -->
<div id="loader">
    <div class="loader-logo">AA3D</div>
    <div class="loader-bar-wrap">
        <div class="loader-bar" id="loader-bar"></div>
    </div>
    <div class="loader-text" id="loader-text">Initializing 3D Engine...</div>
</div>

<!-- Custom Cursor -->
<div class="cursor" id="cursor"></div>
<div class="cursor-ring" id="cursor-ring"></div>

<!-- 3D Background Canvas -->
<canvas id="bg-canvas"></canvas>

<!-- Navigation -->
<nav id="navbar">
    <a href="index.php" class="nav-logo">ALI<span>.</span>AFZAL</a>
    <ul class="nav-links">
        <li><a href="#featured">Work</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="projects.php">All Projects</a></li>
        <li><a href="#contact" class="nav-cta">Contact</a></li>
    </ul>
    <button class="nav-hamburger" id="nav-hamburger" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>
</nav>

<!-- Mobile nav drawer -->
<div class="nav-drawer-backdrop" id="nav-backdrop"></div>
<aside class="nav-drawer" id="nav-drawer" role="dialog" aria-modal="true" aria-label="Navigation">
    <div class="nav-drawer-head">
        <a href="index.php" class="nav-drawer-logo">ALI<span>.</span>AFZAL</a>
        <button class="nav-drawer-close" id="nav-drawer-close" aria-label="Close menu">&#x2715;</button>
    </div>
    <ul class="nav-drawer-links">
        <li><a href="#featured">Work</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="projects.php">All Projects</a></li>
    </ul>
    <a href="#contact" class="nav-drawer-cta">Get In Touch</a>
    <div class="nav-drawer-footer">ALI AFZAL // 3D ARTIST &amp; DESIGNER</div>
</aside>

<!-- HERO -->
<section id="hero">
    <div id="particles-js"></div>
    <div class="hero-content">
        <div class="hero-badge">
            <div class="dot"></div>
            Available for Freelance
        </div>
        <h1 class="hero-name">
            <span class="line1">Ali Afzal</span>
            <span class="line2" data-text="3D ARTIST">3D ARTIST</span>
        </h1>
        <p class="hero-tagline">
            Blender<span class="sep">◆</span>3D Modeler<span class="sep">◆</span>Visual Storyteller
        </p>
        <p class="hero-desc">
            Crafting immersive three-dimensional worlds, characters, and environments. 
            Every polygon tells a story.
        </p>
        <div class="hero-buttons">
            <a href="projects.php" class="btn-primary">View My Work</a>
            <a href="#contact" class="btn-secondary">Let's Collaborate</a>
        </div>
        <div class="hero-stats">
            <div class="stat-item">
                <span class="stat-num" data-count="5">0</span>
                <span class="stat-label">Years Experience</span>
            </div>
            <div class="stat-item">
                <span class="stat-num" data-count="50">0</span>
                <span class="stat-label">Projects Done</span>
            </div>
            <div class="stat-item">
                <span class="stat-num" data-count="30">0</span>
                <span class="stat-label">Happy Clients</span>
            </div>
        </div>
    </div>
    <div class="scroll-hint">
        <div class="scroll-line"></div>
        SCROLL
    </div>
</section>

<!-- FEATURED WORK -->
<section id="featured" class="section">
    <div class="section-header reveal">
        <div class="section-label">Selected Projects</div>
        <h2 class="section-title">Featured Work</h2>
        <div class="section-line"></div>
    </div>

    <!-- Category Filter -->
    <div class="categories-bar reveal" id="cat-filter">
        <button class="cat-btn active" data-cat="all">All</button>
        <?php
        require_once 'includes/config.php';
        try {
            $db = getDB();
            $cats = $db->query("SELECT DISTINCT category FROM projects WHERE status='published' ORDER BY category")->fetchAll();
            foreach ($cats as $c) {
                echo '<button class="cat-btn" data-cat="'.htmlspecialchars($c['category']).'">'.htmlspecialchars($c['category']).'</button>';
            }
        } catch(Exception $e) { /* DB not yet setup */ }
        ?>
    </div>

    <div class="projects-grid" id="projects-grid">
        <?php
        try {
            $db = getDB();
            $projects = $db->query("SELECT p.*, pi.filename as thumb_file FROM projects p 
                LEFT JOIN project_images pi ON p.id = pi.project_id AND pi.is_primary = 1 
                WHERE p.status='published' AND p.featured = 1 ORDER BY p.sort_order ASC, p.created_at DESC LIMIT 6")->fetchAll();
            foreach ($projects as $p):
                $tags = $p['tags'] ? explode(',', $p['tags']) : [];
                $thumb = $p['thumb_file'] ? UPLOAD_URL . 'projects/' . $p['thumb_file'] : null;
        ?>
        <a href="project.php?slug=<?= htmlspecialchars($p['slug']) ?>" class="project-card reveal" data-cat="<?= htmlspecialchars($p['category']) ?>">
            <?php if ($p['featured']): ?><div class="featured-badge">Featured</div><?php endif; ?>
            <div class="card-image">
                <?php if ($thumb): ?>
                    <img src="<?= $thumb ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy">
                <?php else: ?>
                    <div class="card-placeholder"><div class="placeholder-icon">3D</div></div>
                <?php endif; ?>
                <div class="card-overlay">
                    <!-- <div class="card-category"><?= htmlspecialchars($p['category']) ?></div> -->
                    <div class="card-title"><?= htmlspecialchars($p['title']) ?></div>
                    <!-- <div class="card-meta">
                        <span class="card-year"><?= $p['year'] ? htmlspecialchars($p['year']) : '' ?></span>
                        <div class="card-arrow">→</div>
                    </div> -->
                </div>
            </div>
            <div class="card-body">
                <p class="card-desc"><?= htmlspecialchars($p['description'] ?? '') ?></p>
                <?php if ($tags): ?>
                <div class="card-tags">
                    <?php foreach (array_slice($tags, 0, 3) as $t): ?>
                    <span class="tag"><?= htmlspecialchars(trim($t)) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </a>
        <?php
            endforeach;
        } catch(Exception $e) {
            // Show demo cards if DB not setup
        ?>
        <?php foreach (['Cyberpunk Street', 'Mecha Warrior', 'Ancient Temple'] as $i => $title): ?>
        <div class="project-card reveal">
            <div class="card-image">
                <div class="card-placeholder"><div class="placeholder-icon">3D</div></div>
                <div class="card-overlay">
                    <div class="card-category">Environment</div>
                    <div class="card-title"><?= $title ?></div>
                    <div class="card-meta">
                        <span class="card-year">2024</span>
                        <div class="card-arrow">→</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="card-desc">A stunning 3D render crafted with precision in Blender using Cycles rendering engine.</p>
                <div class="card-tags">
                    <span class="tag">Blender</span>
                    <span class="tag">3D</span>
                    <span class="tag">Cycles</span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php } ?>
    </div>

    <div class="view-all-wrap reveal">
        <a href="projects.php" class="btn-secondary">View All Projects</a>
    </div>
</section>

<!-- ABOUT -->
<section class="section" id="about-section">
    <div id="about" class="section" style="padding:0;">
        <div class="about-visual reveal">
            <div class="about-3d-frame">
                <canvas id="about-canvas"></canvas>
                <div class="about-corner tl"></div>
                <div class="about-corner tr"></div>
                <div class="about-corner bl"></div>
                <div class="about-corner br"></div>
            </div>
        </div>
        <div class="about-text reveal">
            <div class="section-header">
                <div class="section-label">About Me</div>
                <h2 class="section-title">Crafting<br>Digital<br>Realities</h2>
                <div class="section-line"></div>
            </div>
            <p class="about-bio">
                <?php
                try {
                    $db = getDB();
                    $about = $db->query("SELECT value FROM settings WHERE key_name='about_text'")->fetchColumn();
                    echo htmlspecialchars($about ?: 'I am Ali Afzal, a passionate 3D artist specializing in creating stunning digital worlds using Blender.');
                } catch(Exception $e) {
                    echo 'I am Ali Afzal, a passionate 3D artist specializing in creating stunning digital worlds, characters, and environments using Blender.';
                }
                ?>
            </p>
            <div class="skills-grid">
                <?php
                $skills = [
                    ['Blender', 95], ['3D Modeling', 92], ['Texturing', 88],
                    ['Lighting', 90], ['Rendering', 85], ['Animation', 80]
                ];
                foreach ($skills as $s):
                ?>
                <div class="skill-item">
                    <div class="skill-name"><?= $s[0] ?> <span><?= $s[1] ?>%</span></div>
                    <div class="skill-bar">
                        <div class="skill-fill" data-width="<?= $s[1] ?>"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div style="display:flex;gap:16px;flex-wrap:wrap;">
                <a href="projects.php" class="btn-primary">My Portfolio</a>
                <a href="#contact" class="btn-secondary">Hire Me</a>
            </div>
        </div>
    </div>
</section>

<!-- CONTACT -->
<section id="contact" class="section">
    <div class="section-header reveal">
        <div class="section-label">Get In Touch</div>
        <h2 class="section-title">Start a Project</h2>
        <div class="section-line"></div>
    </div>
    <div class="contact-grid">
        <div class="contact-info reveal">
            <p class="contact-intro">
                Have a project in mind? I'd love to bring your 3D vision to life. 
                Let's create something extraordinary together.
            </p>
            <div class="contact-links">
                <a href="mailto:ali.afzal@email.com" class="contact-link">
                    <div class="contact-link-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></div>
                    <div class="contact-link-text">
                        <strong>Email</strong>
                        ali.afzal@email.com
                    </div>
                </a>
                <a href="https://artstation.com/aliafzal" target="_blank" class="contact-link">
                    <div class="contact-link-icon"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.213 9.787a3.391 3.391 0 0 0-4.795 0l-3.425 3.426a3.39 3.39 0 0 0 4.795 4.794l.321-.304m-.321-4.49a3.39 3.39 0 0 0 4.795 0l3.424-3.426a3.39 3.39 0 0 0-4.794-4.795l-1.028.961"/>
</svg>
</div>
                    <div class="contact-link-text">
                        <strong>ArtStation</strong>
                        artstation.com/aliafzal
                    </div>
                </a>
                <a href="#" class="contact-link">
                    <div class="contact-link-icon"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
  <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd"/>
</svg>
</div>
                    <div class="contact-link-text">
                        <strong>Location</strong>
                        Pakistan
                    </div>
                </a>
            </div>
            <div class="social-bar">
                <a href="#" class="social-link" title="Instagram">IG</a>
                <a href="#" class="social-link" title="ArtStation">AS</a>
                <a href="#" class="social-link" title="LinkedIn">IN</a>
                <a href="#" class="social-link" title="YouTube">YT</a>
            </div>
        </div>

        <div class="contact-form reveal">
            <div class="form-group full">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Your Name</label>
                        <div class="form-corner tl"></div>
                        <input type="text" class="form-input" id="c-name" placeholder="Xythonix">
                        <div class="form-corner br"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Your Email</label>
                        <input type="email" class="form-input" id="c-email" placeholder="john@example.com">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:20px;">
                    <label class="form-label">Subject</label>
                    <input type="text" class="form-input" id="c-subject" placeholder="3D Project Request">
                </div>
                <div class="form-group" style="position:relative;">
                    <label class="form-label">Message</label>
                    <textarea class="form-textarea" id="c-message" placeholder="Tell me about your project..." maxlength="1000"></textarea>
                    <span class="char-count"><span id="char-num">0</span>/1000</span>
                </div>
            </div>
            <button class="btn-primary" id="send-btn" onclick="sendMessage()" style="width:100%;margin-top:20px;border:none;">
                Send Message →
            </button>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="footer-copy">
        © <?php echo date('Y'); ?> <span>Ali Afzal</span>. All rights reserved.
    </div>
    <div style="font-family:var(--font-mono);font-size:0.7rem;color:var(--text-muted);">
        Developed by <span style="color:var(--primary)">Xythonix</span>
    </div>
</footer>

<!-- Floating widgets -->
<div class="floating-widget">
    <a href="#hero" class="float-btn" title="Back to top">↑</a>
    <a href="projects.php" class="float-btn" title="All Projects">⬡</a>
</div>

<script>
// ═══════════════════════════════════════════════════════
// LOADER
// ═══════════════════════════════════════════════════════
const loader = document.getElementById('loader');
const loaderBar = document.getElementById('loader-bar');
const loaderText = document.getElementById('loader-text');
const messages = [
    'Initializing 3D Engine...', 'Loading Shaders...',
    'Building Mesh Data...', 'Rendering Polygons...', 'Welcome to Ali Afzal\'s Universe'
];
let progress = 0;
const interval = setInterval(() => {
    progress += Math.random() * 20;
    if (progress >= 100) { progress = 100; clearInterval(interval); }
    loaderBar.style.width = progress + '%';
    loaderText.textContent = messages[Math.floor(progress / 25)] || messages[4];
    if (progress >= 100) {
        setTimeout(() => {
            loader.style.opacity = '0';
            loader.style.transition = 'opacity 0.6s ease';
            setTimeout(() => { loader.style.display = 'none'; initAnimations(); }, 600);
        }, 300);
    }
}, 120);

// ═══════════════════════════════════════════════════════
// CUSTOM CURSOR
// ═══════════════════════════════════════════════════════
const cursor = document.getElementById('cursor');
const ring = document.getElementById('cursor-ring');
document.addEventListener('mousemove', e => {
    cursor.style.left = e.clientX + 'px';
    cursor.style.top = e.clientY + 'px';
    setTimeout(() => {
        ring.style.left = e.clientX + 'px';
        ring.style.top = e.clientY + 'px';
    }, 80);
});
document.querySelectorAll('a, button, .project-card, .cat-btn').forEach(el => {
    el.addEventListener('mouseenter', () => {
        cursor.style.transform = 'translate(-50%,-50%) scale(2)';
        ring.style.width = '60px'; ring.style.height = '60px';
        ring.style.borderColor = 'rgba(74,55,40,0.8)';
    });
    el.addEventListener('mouseleave', () => {
        cursor.style.transform = 'translate(-50%,-50%) scale(1)';
        ring.style.width = '40px'; ring.style.height = '40px';
        ring.style.borderColor = 'rgba(74,55,40,0.5)';
    });
});

// ═══════════════════════════════════════════════════════
// THREE.JS BACKGROUND
// ═══════════════════════════════════════════════════════
function initBgCanvas() {
    const canvas = document.getElementById('bg-canvas');
    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(60, window.innerWidth/window.innerHeight, 0.1, 1000);
    camera.position.z = 30;

    // Wireframe sphere grid
    const spheres = [];
    const geoSphere = new THREE.IcosahedronGeometry(6, 2);
    const matSphere = new THREE.MeshBasicMaterial({ color: 0x8b6a4f, wireframe: true, transparent: true, opacity: 0.08 });
    for (let i = 0; i < 4; i++) {
        const mesh = new THREE.Mesh(geoSphere, matSphere.clone());
        mesh.position.set((Math.random()-0.5)*60, (Math.random()-0.5)*40, (Math.random()-0.5)*20 - 10);
        mesh.rotation.set(Math.random()*Math.PI, Math.random()*Math.PI, Math.random()*Math.PI);
        scene.add(mesh);
        spheres.push({ mesh, speed: 0.002 + Math.random()*0.003 });
    }

    // Floating particles
    const ptGeo = new THREE.BufferGeometry();
    const pts = [];
    for (let i = 0; i < 200; i++) {
        pts.push((Math.random()-0.5)*100, (Math.random()-0.5)*80, (Math.random()-0.5)*50);
    }
    ptGeo.setAttribute('position', new THREE.Float32BufferAttribute(pts, 3));
    const ptMat = new THREE.PointsMaterial({ color: 0x8b6a4f, size: 0.15, transparent: true, opacity: 0.5 });
    scene.add(new THREE.Points(ptGeo, ptMat));

    // Grid plane
    const gridHelper = new THREE.GridHelper(100, 30, 0xede8e0, 0xf0ebe3);
    gridHelper.material.transparent = true; gridHelper.material.opacity = 0.03;
    gridHelper.position.y = -20;
    scene.add(gridHelper);

    let mouseX = 0, mouseY = 0;
    document.addEventListener('mousemove', e => {
        mouseX = (e.clientX/window.innerWidth - 0.5) * 0.5;
        mouseY = (e.clientY/window.innerHeight - 0.5) * 0.3;
    });

    function animate() {
        requestAnimationFrame(animate);
        const t = Date.now() * 0.001;
        spheres.forEach((s, i) => {
            s.mesh.rotation.x += s.speed;
            s.mesh.rotation.y += s.speed * 0.7;
            s.mesh.position.y += Math.sin(t + i) * 0.01;
        });
        camera.position.x += (mouseX * 5 - camera.position.x) * 0.02;
        camera.position.y += (-mouseY * 5 - camera.position.y) * 0.02;
        camera.lookAt(scene.position);
        renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth/window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });
}
initBgCanvas();

// ═══════════════════════════════════════════════════════
// ABOUT CANVAS — Spinning 3D Object
// ═══════════════════════════════════════════════════════
function initAboutCanvas() {
    const canvas = document.getElementById('about-canvas');
    if (!canvas) return;
    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer.setSize(canvas.offsetWidth, canvas.offsetHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(50, 1, 0.1, 100);
    camera.position.z = 5;

    // Icosahedron
    const geo = new THREE.IcosahedronGeometry(1.5, 0);
    const edges = new THREE.EdgesGeometry(geo);
    const mat = new THREE.LineBasicMaterial({ color: 0x8b6a4f, linewidth: 1 });
    const wireframe = new THREE.LineSegments(edges, mat);
    scene.add(wireframe);

    // Inner glow sphere
    const innerGeo = new THREE.IcosahedronGeometry(1.3, 1);
    const innerMat = new THREE.MeshBasicMaterial({ color: 0x7b2fff, wireframe: true, transparent: true, opacity: 0.15 });
    scene.add(new THREE.Mesh(innerGeo, innerMat));

    // Orbiting dot
    const orbitGeo = new THREE.SphereGeometry(0.06, 8, 8);
    const orbitMat = new THREE.MeshBasicMaterial({ color: 0xff006e });
    const orbit = new THREE.Mesh(orbitGeo, orbitMat);
    scene.add(orbit);

    let t = 0;
    function animate() {
        requestAnimationFrame(animate);
        t += 0.01;
        wireframe.rotation.x += 0.004;
        wireframe.rotation.y += 0.007;
        orbit.position.set(Math.cos(t) * 2.2, Math.sin(t * 0.7) * 1.5, Math.sin(t) * 2.2);
        renderer.render(scene, camera);
    }
    animate();

    const ro = new ResizeObserver(() => {
        const w = canvas.offsetWidth;
        renderer.setSize(w, w);
        camera.updateProjectionMatrix();
    });
    ro.observe(canvas.parentElement);
}

// ═══════════════════════════════════════════════════════
// PARTICLES
// ═══════════════════════════════════════════════════════
function initParticles() {
    particlesJS('particles-js', {
        particles: {
            number: { value: 60 },
            color: { value: '#4a3728' },
            opacity: { value: 0.4, random: true },
            size: { value: 2, random: true },
            move: { enable: true, speed: 0.5, random: true },
            line_linked: { enable: true, color: '#4a3728', opacity: 0.12, distance: 150 }
        },
        interactivity: {
            events: { onhover: { enable: true, mode: 'grab' } },
            modes: { grab: { distance: 140, line_linked: { opacity: 0.3 } } }
        }
    });
}

// ═══════════════════════════════════════════════════════
// GSAP ANIMATIONS
// ═══════════════════════════════════════════════════════
function initAnimations() {
    gsap.registerPlugin(ScrollTrigger);
    initParticles();
    initAboutCanvas();

    // Hero entrance
    const tl = gsap.timeline({ delay: 0.2 });
    tl.to('.hero-name', { opacity: 1, duration: 1, ease: 'power3.out' })
      .to('.hero-tagline', { opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' }, '-=0.5')
      .to('.hero-desc', { opacity: 1, y: 0, duration: 0.8 }, '-=0.5')
      .to('.hero-buttons', { opacity: 1, y: 0, duration: 0.8 }, '-=0.4')
      .to('.hero-stats', { opacity: 1, y: 0, duration: 0.8 }, '-=0.3');

    // Count up animation
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = +el.dataset.count;
        gsap.to({ val: 0 }, {
            val: target, duration: 2, delay: 1.5,
            ease: 'power2.out',
            onUpdate: function() { el.textContent = Math.round(this.targets()[0].val) + '+'; }
        });
    });

    // Scroll reveals
    const reveals = document.querySelectorAll('.reveal');
    reveals.forEach((el, i) => {
        ScrollTrigger.create({
            trigger: el, start: 'top 85%',
            onEnter: () => gsap.to(el, { opacity: 1, y: 0, duration: 0.8, delay: (i % 3) * 0.1, ease: 'power3.out' })
        });
    });

    // Skill bars
    document.querySelectorAll('.skill-fill').forEach(bar => {
        ScrollTrigger.create({
            trigger: bar, start: 'top 80%',
            onEnter: () => { bar.style.width = bar.dataset.width + '%'; }
        });
    });
}

// ═══════════════════════════════════════════════════════
// CATEGORY FILTER
// ═══════════════════════════════════════════════════════
document.querySelectorAll('.cat-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const cat = btn.dataset.cat;
        document.querySelectorAll('.project-card').forEach(card => {
            const show = cat === 'all' || card.dataset.cat === cat;
            card.style.transition = 'opacity 0.3s, transform 0.3s';
            card.style.opacity = show ? '1' : '0.2';
            card.style.transform = show ? '' : 'scale(0.95)';
            card.style.pointerEvents = show ? '' : 'none';
        });
    });
});

// ═══════════════════════════════════════════════════════
// CHARACTER COUNTER
// ═══════════════════════════════════════════════════════
document.getElementById('c-message')?.addEventListener('input', function() {
    document.getElementById('char-num').textContent = this.value.length;
});

// ═══════════════════════════════════════════════════════
// CONTACT FORM
// ═══════════════════════════════════════════════════════
function sendMessage() {
    const name = document.getElementById('c-name').value.trim();
    const email = document.getElementById('c-email').value.trim();
    const subject = document.getElementById('c-subject').value.trim();
    const message = document.getElementById('c-message').value.trim();

    if (!name || !email || !message) {
        Swal.fire({
            icon: 'warning', title: 'Missing Fields',
            text: 'Please fill in your name, email, and message.',
            background: '#f0ebe3', color: '#1a1714',
            confirmButtonColor: '#4a3728', iconColor: '#c9a96e'
        });
        return;
    }
    const emailReg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailReg.test(email)) {
        Swal.fire({ icon: 'error', title: 'Invalid Email', text: 'Please enter a valid email.', background: '#f0ebe3', color: '#1a1714', confirmButtonColor: '#4a3728' });
        return;
    }

    const btn = document.getElementById('send-btn');
    btn.textContent = 'Transmitting...'; btn.disabled = true;

    fetch('api/contact.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, email, subject, message })
    })
    .then(r => r.json())
    .then(data => {
        btn.textContent = 'Send Message →'; btn.disabled = false;
        if (data.success) {
            Swal.fire({
                icon: 'success', title: 'Message Sent!',
                html: 'Your message has been transmitted. Ali will get back to you soon.',
                background: '#f0ebe3', color: '#1a1714',
                confirmButtonColor: '#4a3728',
                customClass: { popup: 'swal-custom' }
            });
            document.getElementById('c-name').value = '';
            document.getElementById('c-email').value = '';
            document.getElementById('c-subject').value = '';
            document.getElementById('c-message').value = '';
            document.getElementById('char-num').textContent = '0';
        } else {
            Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Something went wrong.', background: '#f0ebe3', color: '#1a1714', confirmButtonColor: '#4a3728' });
        }
    })
    .catch(() => {
        btn.textContent = 'Send Message →'; btn.disabled = false;
        Swal.fire({ icon: 'error', title: 'Connection Error', text: 'Could not reach the server.', background: '#f0ebe3', color: '#1a1714', confirmButtonColor: '#4a3728' });
    });
}

// Navbar scroll effect
window.addEventListener('scroll', () => {
    const nav = document.getElementById('navbar');
    if (window.scrollY > 50) nav.style.background = 'rgba(250,248,245,0.99)';
    else nav.style.background = 'linear-gradient(180deg, rgba(250,248,245,0.95) 0%, transparent 100%)';
});
</script>

<script>
// ─── Mobile nav drawer ───────────────────────────────────────
(function(){
    var h = document.getElementById('nav-hamburger');
    var d = document.getElementById('nav-drawer');
    var b = document.getElementById('nav-backdrop');
    if (!h||!d||!b) return;
    function open(){
        d.classList.add('open'); b.style.display='block';
        requestAnimationFrame(function(){ b.classList.add('visible'); });
        h.classList.add('open'); h.setAttribute('aria-expanded','true');
        document.body.style.overflow='hidden';
    }
    function close(){
        d.classList.remove('open'); b.classList.remove('visible');
        h.classList.remove('open'); h.setAttribute('aria-expanded','false');
        document.body.style.overflow='';
        setTimeout(function(){ b.style.display='none'; },320);
    }
    h.addEventListener('click', function(){ d.classList.contains('open')?close():open(); });
    b.addEventListener('click', close);
    document.getElementById('nav-drawer-close').addEventListener('click', close);
    d.querySelectorAll('a').forEach(function(a){ a.addEventListener('click',function(){ setTimeout(close,120); }); });
    document.addEventListener('keydown',function(e){ if(e.key==='Escape') close(); });
    var tx=0;
    d.addEventListener('touchstart',function(e){ tx=e.touches[0].clientX; },{passive:true});
    d.addEventListener('touchend',function(e){ if(e.changedTouches[0].clientX-tx>60) close(); },{passive:true});
})();
</script>
</body>
</html>