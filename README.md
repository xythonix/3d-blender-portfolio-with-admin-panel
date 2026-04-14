# Ali Afzal — 3D Artist Portfolio
## Complete PHP/MySQL Portfolio System with Admin Panel

---

## 📁 FILE STRUCTURE

```
ali3d/
├── index.php              ← Landing page (homepage)
├── projects.php           ← All projects with filtering
├── project.php            ← Single project with 3D viewer
├── .htaccess              ← Security & caching rules
│
├── includes/
│   └── config.php         ← Database & site config
│
├── api/
│   ├── contact.php        ← Contact form handler
│   └── upload.php         ← Image upload handler
│
├── admin/
│   ├── login.php          ← Admin login
│   ├── logout.php         ← Logout
│   ├── dashboard.php      ← Main dashboard
│   ├── projects.php       ← Manage all projects
│   ├── add-project.php    ← Add/edit project + image upload
│   ├── messages.php       ← Contact message inbox
│   ├── settings.php       ← Site settings
│   └── actions.php        ← AJAX CRUD actions
│
├── install/
│   ├── index.php          ← Web-based installer
│   └── setup.sql          ← Database schema
│
└── uploads/
    ├── projects/          ← Full-size project images
    └── thumbnails/        ← Auto-generated thumbnails
```

---

## ⚡ INSTALLATION (3 Steps)

### Step 1 — Copy to server
Copy the `ali3d/` folder to your web root:
- XAMPP: `C:/xampp/htdocs/ali3d/`
- WAMP: `C:/wamp64/www/ali3d/`
- Linux: `/var/www/html/ali3d/`

### Step 2 — Set permissions (Linux/Mac)
```bash
chmod -R 755 uploads/
chmod 644 includes/config.php
```

### Step 3 — Run Installer
Open your browser and go to:
`http://localhost/ali3d/install/`

Fill in your database credentials and click **Run Installation**.

---

## 🔐 DEFAULT LOGIN

| | |
|---|---|
| **URL** | `http://localhost/ali3d/admin/login.php` |
| **Username** | `ali_afzal` |
| **Password** | `Ali@3DArtist2024` |

**⚠ IMPORTANT: Change the password after first login!**

---

## 🌟 FEATURES

### Public Portfolio
- **Landing Page**: Animated hero with Three.js 3D background, particles, smooth scroll
- **Project Gallery**: Filter by category, search, grid/list layout
- **3D Viewer**: 
  - Drag-to-rotate between uploaded views (front/back/left/right/top/perspective)
  - Three.js 360° interactive 3D mode
  - Pinch/scroll zoom on images
  - Angle selector buttons
  - Fullscreen mode
  - Keyboard shortcuts (←/→ navigate, F fullscreen, +/- zoom)
  - Touch support for mobile
- **Contact Form**: AJAX with SweetAlert2 notifications
- **Custom cursor**, animated navigation, reveal animations

### Admin Panel
- **Dashboard**: Stats, recent projects, message inbox preview, activity log
- **Project Management**: 
  - Create/edit/delete projects
  - Set category, tags, software, year, client
  - Featured toggle, published/draft status
  - Sort order control
- **Image Upload**:
  - Multi-file drag & drop upload
  - Angle labeling (front/back/left/right/top/perspective/wireframe/texture)
  - Auto-thumbnail generation with GD
  - Set primary/thumbnail image
  - Delete individual images
  - Upload progress indicator
- **Messages Inbox**: Read/unread, star, delete, reply via mailto link
- **Site Settings**: Update bio, hero text, contact info, social links
- **Security**: PHP sessions, CSRF-ready, IP rate limiting on contact form, file type validation

---

## ⚙️ REQUIREMENTS

- PHP 8.0+
- MySQL 5.7+ or MariaDB 10.3+
- GD extension (for thumbnails)
- Apache with mod_rewrite enabled
- XAMPP / WAMP / LAMP / LEMP stack

---

## 🎨 TECH STACK

| Technology | Usage |
|---|---|
| PHP 8 | Backend |
| MySQL | Database |
| Three.js r128 | 3D background & 360° viewer |
| GSAP 3 | Animations & scroll triggers |
| Particles.js | Hero particle field |
| SweetAlert2 | Modal alerts |
| Pure CSS | All styling (no Bootstrap) |
| Vanilla JS | All interactions |
| AJAX/Fetch | Form submissions |

---

## 🚀 AFTER INSTALLATION

1. Delete the `/install/` folder
2. Go to Admin → Settings → Update all info
3. Go to Admin → Add Project → Create your first project
4. Upload multiple angles of your 3D renders
5. Publish and share your portfolio!

---

Made with 💙 for Ali Afzal's 3D Art Portfolio
