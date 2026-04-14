<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
require_once 'includes/config.php';
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
$project = null;
$images = [];

try {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM projects WHERE slug=? AND status='published'");
    $stmt->execute([$slug]);
    $project = $stmt->fetch();

    if ($project) {
        $db->prepare("UPDATE projects SET view_count = view_count+1 WHERE id=?")->execute([$project['id']]);
        $imgs = $db->prepare("SELECT * FROM project_images WHERE project_id=? ORDER BY is_primary DESC, sort_order ASC");
        $imgs->execute([$project['id']]);
        $images = $imgs->fetchAll();

        // Related
        $rel = $db->prepare("SELECT p.*, pi.filename as tf FROM projects p LEFT JOIN project_images pi ON p.id=pi.project_id AND pi.is_primary=1 WHERE p.category=? AND p.id!=? AND p.status='published' LIMIT 3");
        $rel->execute([$project['category'], $project['id']]);
        $related = $rel->fetchAll();
    }
} catch(Exception $e) { $project = null; }

$title = $project ? htmlspecialchars($project['title']) . ' — Ali Afzal 3D' : 'Project — Ali Afzal 3D';
?>
<title><?= $title ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<script src="https://unpkg.com/three@0.128.0/build/three.min.js"></script>
<script src="https://unpkg.com/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
<script src="https://unpkg.com/three@0.128.0/examples/js/loaders/OBJLoader.js"></script>
<script src="https://unpkg.com/three@0.128.0/examples/js/loaders/STLLoader.js"></script>
<script src="https://unpkg.com/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
<script>
// ── Loader safety check — if unpkg is slow/blocked, define stubs so page doesn't crash
window.addEventListener('DOMContentLoaded', function() {
    if (!THREE.GLTFLoader) {
        // Fallback: define minimal loaders using THREE.FileLoader so GLB/GLTF still works
        // via the built-in ObjectLoader path for simple scenes
        THREE.GLTFLoader = function() { this.setPath = function(){}; };
        THREE.GLTFLoader.prototype.load = function(url, onLoad, onProgress, onError) {
            var fl = new THREE.FileLoader();
            fl.setResponseType('arraybuffer');
            fl.load(url, function(buffer) {
                // Try native GLTF parse if available
                if (THREE.GLTFLoader._parse) {
                    THREE.GLTFLoader._parse(buffer, '', function(gltf){ onLoad(gltf); }, onError);
                } else { onError(new Error('GLTFLoader not available. Try a different browser or re-upload the model as OBJ.')); }
            }, onProgress, onError);
        };
    }
    if (!THREE.OBJLoader) {
        THREE.OBJLoader = function() {};
        THREE.OBJLoader.prototype.load = function(url, onLoad, onProgress, onError) {
            var fl = new THREE.FileLoader();
            fl.load(url, function(text) {
                // Minimal OBJ parser
                var positions=[], normals=[], uvs=[], groups=[], curGroup=[];
                var lines = text.split('\n');
                for (var i=0;i<lines.length;i++) {
                    var l=lines[i].trim(), p=l.split(/\s+/);
                    if (p[0]==='v')  positions.push(+p[1],+p[2],+p[3]);
                    else if (p[0]==='vn') normals.push(+p[1],+p[2],+p[3]);
                    else if (p[0]==='vt') uvs.push(+p[1],+p[2]);
                    else if (p[0]==='f') curGroup.push(p.slice(1));
                }
                var geo=new THREE.BufferGeometry(), verts=[], norms=[];
                curGroup.forEach(function(face){
                    for (var t=1;t<face.length-1;t++) {
                        [face[0],face[t],face[t+1]].forEach(function(token){
                            var idx=token.split('/'), vi=(+idx[0]-1)*3;
                            verts.push(positions[vi],positions[vi+1],positions[vi+2]);
                            if (idx[2]) { var ni=(+idx[2]-1)*3; norms.push(normals[ni],normals[ni+1],normals[ni+2]); }
                        });
                    }
                });
                geo.setAttribute('position',new THREE.Float32BufferAttribute(verts,3));
                if (norms.length) geo.setAttribute('normal',new THREE.Float32BufferAttribute(norms,3));
                else geo.computeVertexNormals();
                var mesh=new THREE.Mesh(geo,new THREE.MeshStandardMaterial({color:0x88aacc,side:THREE.DoubleSide}));
                var obj=new THREE.Group(); obj.add(mesh); onLoad(obj);
            }, onProgress, onError);
        };
    }
    if (!THREE.STLLoader) {
        THREE.STLLoader = function() {};
        THREE.STLLoader.prototype.load = function(url, onLoad, onProgress, onError) {
            var fl = new THREE.FileLoader();
            fl.setResponseType('arraybuffer');
            fl.load(url, function(buf) {
                // Binary STL parser
                var dv=new DataView(buf), tris=dv.getUint32(80,true);
                var verts=new Float32Array(tris*9), norms=new Float32Array(tris*9);
                var off=84;
                for (var t=0;t<tris;t++) {
                    var nx=dv.getFloat32(off,true),ny=dv.getFloat32(off+4,true),nz=dv.getFloat32(off+8,true); off+=12;
                    for (var v=0;v<3;v++) {
                        var b=t*9+v*3;
                        verts[b]=dv.getFloat32(off,true); verts[b+1]=dv.getFloat32(off+4,true); verts[b+2]=dv.getFloat32(off+8,true); off+=12;
                        norms[b]=nx; norms[b+1]=ny; norms[b+2]=nz;
                    }
                    off+=2;
                }
                var geo=new THREE.BufferGeometry();
                geo.setAttribute('position',new THREE.Float32BufferAttribute(verts,3));
                geo.setAttribute('normal',new THREE.Float32BufferAttribute(norms,3));
                onLoad(geo);
            }, onProgress, onError);
        };
    }
    if (!THREE.OrbitControls) {
        // Minimal OrbitControls implementation
        THREE.OrbitControls = function(camera, domElement) {
            this.camera = camera;
            this.domElement = domElement || document;
            this.enableDamping = false;
            this.dampingFactor = 0.1;
            this.autoRotate = false;
            this.autoRotateSpeed = 2.0;
            this.enableZoom = true;
            this.zoomSpeed = 1.0;
            this.enablePan = true;
            this.panSpeed = 1.0;
            this.minDistance = 0;
            this.maxDistance = Infinity;
            this.target = new THREE.Vector3();

            var scope = this;
            var spherical = new THREE.Spherical();
            var sphericalDelta = new THREE.Spherical();
            var scale = 1;
            var panOffset = new THREE.Vector3();
            var isDragging = false, isRightDrag = false;
            var lastX, lastY;

            // Init spherical from camera
            var offset = new THREE.Vector3();
            offset.copy(camera.position).sub(scope.target);
            spherical.setFromVector3(offset);

            function getZoomScale() { return Math.pow(0.95, scope.zoomSpeed); }

            domElement.addEventListener('contextmenu', function(e){ e.preventDefault(); });

            domElement.addEventListener('mousedown', function(e) {
                isDragging = true;
                isRightDrag = (e.button === 2);
                lastX = e.clientX; lastY = e.clientY;
            });
            document.addEventListener('mouseup', function() { isDragging = false; });
            document.addEventListener('mousemove', function(e) {
                if (!isDragging) return;
                var dx = e.clientX - lastX, dy = e.clientY - lastY;
                lastX = e.clientX; lastY = e.clientY;
                if (isRightDrag && scope.enablePan) {
                    var pSpeed = 0.005 * scope.panSpeed;
                    panOffset.x -= dx * pSpeed * offset.length();
                    panOffset.y += dy * pSpeed * offset.length();
                } else {
                    sphericalDelta.theta -= 2 * Math.PI * dx / domElement.clientWidth;
                    sphericalDelta.phi   -= 2 * Math.PI * dy / domElement.clientHeight;
                }
            });
            domElement.addEventListener('wheel', function(e) {
                if (!scope.enableZoom) return;
                e.preventDefault();
                if (e.deltaY < 0) scale /= getZoomScale();
                else scale *= getZoomScale();
            }, { passive: false });

            // Touch
            var touches = [];
            var touchStartDist = 0;
            domElement.addEventListener('touchstart', function(e) {
                touches = Array.from(e.touches);
                if (touches.length === 2) {
                    touchStartDist = Math.hypot(
                        touches[0].clientX - touches[1].clientX,
                        touches[0].clientY - touches[1].clientY
                    );
                }
                lastX = touches[0].clientX; lastY = touches[0].clientY;
            }, { passive: true });
            domElement.addEventListener('touchmove', function(e) {
                e.preventDefault();
                var t = Array.from(e.touches);
                if (t.length === 2) {
                    var d = Math.hypot(t[0].clientX-t[1].clientX, t[0].clientY-t[1].clientY);
                    if (d > touchStartDist) scale /= getZoomScale();
                    else scale *= getZoomScale();
                    touchStartDist = d;
                } else {
                    var dx = t[0].clientX - lastX, dy = t[0].clientY - lastY;
                    sphericalDelta.theta -= 2*Math.PI*dx/domElement.clientWidth;
                    sphericalDelta.phi   -= 2*Math.PI*dy/domElement.clientHeight;
                    lastX = t[0].clientX; lastY = t[0].clientY;
                }
            }, { passive: false });

            this.update = function() {
                offset.copy(camera.position).sub(scope.target);
                spherical.setFromVector3(offset);
                if (scope.autoRotate) sphericalDelta.theta -= 2*Math.PI/60/60*scope.autoRotateSpeed;
                spherical.theta += sphericalDelta.theta;
                spherical.phi   += sphericalDelta.phi;
                spherical.phi = Math.max(0.01, Math.min(Math.PI-0.01, spherical.phi));
                spherical.radius *= scale;
                spherical.radius = Math.max(scope.minDistance, Math.min(scope.maxDistance, spherical.radius));
                scope.target.add(panOffset);
                offset.setFromSpherical(spherical);
                camera.position.copy(scope.target).add(offset);
                camera.lookAt(scope.target);
                if (scope.enableDamping) {
                    sphericalDelta.theta *= (1 - scope.dampingFactor);
                    sphericalDelta.phi   *= (1 - scope.dampingFactor);
                    panOffset.multiplyScalar(1 - scope.dampingFactor);
                } else {
                    sphericalDelta.set(0,0,0);
                    panOffset.set(0,0,0);
                }
                scale = 1;
            };
            this.reset = function() {
                sphericalDelta.set(0,0,0);
                panOffset.set(0,0,0);
                scale = 1;
            };
            this.dispose = function() {};
        };
    }
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
$modelFile = !empty($project['model_3d']) ? $project['model_3d'] : null;
$modelUrl  = $modelFile ? UPLOAD_URL . 'models/' . $modelFile : null;
$modelExt  = $modelFile ? strtolower(pathinfo($modelFile, PATHINFO_EXTENSION)) : null;
// Debug: verify file actually exists on disk
$modelDiskPath = $modelFile ? (UPLOAD_PATH . 'models/' . $modelFile) : null;
$modelFileExists = $modelDiskPath && file_exists($modelDiskPath);
?>

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
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
html { scroll-behavior:smooth; }
body { background:var(--bg-deep); color:var(--text); font-family:var(--font-body); overflow-x:hidden; cursor:none; }
::-webkit-scrollbar { width:4px; } ::-webkit-scrollbar-thumb { background:var(--primary); }
body::before {
    content:''; position:fixed; inset:0; z-index:0; pointer-events:none; opacity:0.03;
    background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
    background-size:200px 200px;
}
.cursor { width:12px; height:12px; background:var(--primary); border-radius:50%; position:fixed; pointer-events:none; z-index:9999; transform:translate(-50%,-50%); box-shadow: none; transition:transform 0.1s; }
.cursor-ring { width:40px; height:40px; border:1px solid rgba(74,55,40,0.5); border-radius:50%; position:fixed; pointer-events:none; z-index:9998; transform:translate(-50%,-50%); transition:transform 0.15s ease, width 0.2s, height 0.2s; }

nav {
    position:fixed; top:0; left:0; right:0; z-index:100;
    padding:20px 60px; display:flex; align-items:center; justify-content:space-between;
    background:rgba(250,248,245,0.98); backdrop-filter:blur(20px);
    border-bottom:1px solid rgba(74,55,40,0.08);
}
.nav-logo { font-family:var(--font-display); font-size:1.4rem; font-weight:900; color:var(--primary); text-decoration:none; letter-spacing:0.2em; text-shadow: none; }
.nav-logo span { color:var(--secondary); }
.nav-back { font-family:var(--font-mono); font-size:0.8rem; color:var(--text-muted); text-decoration:none; letter-spacing:0.1em; display:flex; align-items:center; gap:10px; transition:color 0.3s; }
.nav-back:hover { color:var(--primary); }
.nav-back::before { content:'←'; }

/* ─── HERO BANNER ─── */
.project-hero {
    min-height:500px !important; position:relative; overflow:hidden;
    display:flex; align-items:flex-end; padding:120px 60px 60px;
}
.hero-bg {
    position:absolute; inset:0; z-index:0;
    background:linear-gradient(135deg, #f0ebe3 0%, #ede9f5 50%, #010d1e 100%);
}
.hero-bg::after {
    content:''; position:absolute; inset:0;
    background:linear-gradient(to top, var(--bg-deep) 0%, transparent 60%);
}
.project-hero-content { position:relative; z-index:2; max-width:800px; }
.project-breadcrumb { font-family:var(--font-mono); font-size:0.7rem; color:var(--text-muted); letter-spacing:0.2em; text-transform:uppercase; margin-bottom:20px; }
.project-breadcrumb a { color:var(--primary); text-decoration:none; }
.project-breadcrumb a:hover { text-shadow: none; }
.project-category-badge {
    display:inline-block; padding:6px 16px; margin-bottom:20px;
    background:rgba(74,55,40,0.1); border:1px solid rgba(74,55,40,0.2);
    font-family:var(--font-mono); font-size:0.7rem; color:var(--primary);
    letter-spacing:0.2em; text-transform:uppercase;
    100%, 0% 100%);
}
.project-title { font-family:var(--font-display); font-size:clamp(2.5rem,6vw,5rem); font-weight:900; line-height:1; margin-bottom:24px; }
.project-meta-row { display:flex; gap:32px; flex-wrap:wrap; }
.meta-item { }
.meta-label { font-family:var(--font-mono); font-size:0.65rem; color:var(--text-muted); letter-spacing:0.2em; text-transform:uppercase; margin-bottom:4px; }
.meta-value { font-size:1rem; color:var(--text); font-weight:600; }

/* ─── 3D VIEWER ─── */
.viewer-section { position:relative; z-index:2; padding:0 60px 80px; margin-top:-40px; }
.viewer-container {
    background:var(--bg-card); border:1px solid var(--border); border-radius:4px;
    overflow:hidden; position:relative;
}
.viewer-main {
    aspect-ratio:16/9; position:relative; overflow:hidden;
    background:radial-gradient(ellipse at center, #e8e6f0 0%, #faf8f5 70%);
    cursor:grab; user-select:none;
}
.viewer-main:active { cursor:grabbing; }
.viewer-main canvas { width:100%!important; height:100%!important; display:block; }

/* Image viewer mode */
.img-viewer {
    position:absolute; inset:0;
    display:flex; align-items:center; justify-content:center;
    overflow:hidden;
}
.img-viewer img {
    max-width:100%; max-height:100%; object-fit:contain;
    transform-origin:center; transition:transform 0.1s;
    user-select:none; -webkit-user-drag:none;
}

/* Viewer Controls */
.viewer-toolbar {
    display:flex; align-items:center; gap:12px; padding:16px 24px;
    background:rgba(250,248,245,0.92); border-top:1px solid var(--border);
    flex-wrap:wrap;
}
.viewer-btn {
    padding:8px 16px; background:rgba(74,55,40,0.05); border:1px solid rgba(74,55,40,0.15);
    color:var(--text-muted); font-family:var(--font-mono); font-size:0.72rem;
    letter-spacing:0.1em; cursor:pointer; border-radius:2px;
    transition:all 0.25s; white-space:nowrap;
    100%, 0% 100%);
}
.viewer-btn:hover, .viewer-btn.active { background:rgba(74,55,40,0.12); border-color:var(--primary); color:var(--primary); box-shadow:0 0 10px rgba(74,55,40,0.15); }
.viewer-btn.danger { border-color:rgba(201,169,110,0.15); }
.viewer-btn.danger:hover { background:rgba(201,169,110,0.08); border-color:var(--secondary); color:var(--secondary); }

.viewer-spacer { flex:1; }
.viewer-hint { font-family:var(--font-mono); font-size:0.65rem; color:var(--text-muted); letter-spacing:0.1em; }

/* Mode tabs */
.mode-tabs { display:flex; gap:0; }
.mode-tab { padding:8px 20px; background:transparent; border:1px solid rgba(74,55,40,0.12); color:var(--text-muted); font-family:var(--font-mono); font-size:0.72rem; letter-spacing:0.1em; cursor:pointer; transition:all 0.25s; }
.mode-tab:first-child { border-radius:2px 0 0 2px; }
.mode-tab:last-child { border-radius:0 2px 2px 0; border-left:none; }
.mode-tab.active { background:rgba(74,55,40,0.12); border-color:var(--primary); color:var(--primary); }

/* Thumbnails strip */
.thumb-strip {
    display:flex; gap:10px; padding:16px 24px;
    background:rgba(250,248,245,0.9); border-top:1px solid rgba(74,55,40,0.05);
    overflow-x:auto; scrollbar-width:thin; scrollbar-color:var(--primary) transparent;
}
.thumb-item {
    width:90px; height:60px; flex-shrink:0; cursor:pointer; border-radius:2px; overflow:hidden;
    border:2px solid transparent; transition:border-color 0.25s, transform 0.25s;
    background:linear-gradient(135deg, #e8e6f0, #d8d0f0);
    display:flex; align-items:center; justify-content:center;
    font-family:var(--font-display); font-size:0.6rem; color:var(--text-muted); letter-spacing:0.1em;
}
.thumb-item img { width:100%; height:100%; object-fit:cover; }
.thumb-item:hover, .thumb-item.active { border-color:var(--primary); transform:scale(1.05); box-shadow: none; }

/* Rotation angle labels */
.angle-badge {
    position:absolute; top:20px; left:24px; z-index:5;
    padding:6px 14px; background:rgba(250,248,245,0.92); border:1px solid var(--border);
    font-family:var(--font-mono); font-size:0.7rem; color:var(--primary);
    letter-spacing:0.15em; text-transform:uppercase; border-radius:2px;
    backdrop-filter:blur(10px);
}
.view-count-badge {
    position:absolute; top:20px; right:24px; z-index:5;
    padding:6px 14px; background:rgba(250,248,245,0.92); border:1px solid var(--border);
    font-family:var(--font-mono); font-size:0.7rem; color:var(--text-muted);
    letter-spacing:0.1em; border-radius:2px;
}
.zoom-controls {
    position:absolute; bottom:80px; right:24px; z-index:5;
    display:flex; flex-direction:column; gap:8px;
}
.zoom-btn {
    width:36px; height:36px; background:rgba(250,248,245,0.95); border:1px solid rgba(74,55,40,0.15);
    color:var(--primary); font-size:1.2rem; cursor:pointer; border-radius:2px;
    display:flex; align-items:center; justify-content:center;
    transition:all 0.25s; backdrop-filter:blur(10px);
}
.zoom-btn:hover { background:rgba(74,55,40,0.12); border-color:var(--primary); box-shadow: none; }

/* ─── PROJECT CONTENT ─── */
.content-grid {
    display:grid; grid-template-columns:1fr 360px; gap:60px;
    padding:60px 60px; position:relative; z-index:2; align-items:start;
}
.project-description { }
.project-description h2 { font-family:var(--font-display); font-size:1.5rem; font-weight:700; color:var(--primary); margin-bottom:20px; letter-spacing:0.05em; }
.project-description p { font-size:1.05rem; color:rgba(26,23,20,0.7); line-height:1.9; margin-bottom:20px; }
.project-description .tags-wrap { display:flex; gap:8px; flex-wrap:wrap; margin-top:24px; }
.tag { padding:5px 14px; border:1px solid rgba(74,55,40,0.15); font-family:var(--font-mono); font-size:0.7rem; color:var(--text-muted); letter-spacing:0.1em; border-radius: 0;transition:all 0.25s; }
.tag:hover { border-color:var(--primary); color:var(--primary); }

/* Project sidebar */
.project-sidebar { position:sticky; top:100px; }
.info-card {
    background:var(--bg-card); border:1px solid var(--border); border-radius:4px; overflow:hidden; margin-bottom:20px;
}
.info-card-header { padding:16px 24px; border-bottom:1px solid var(--border); font-family:var(--font-mono); font-size:0.75rem; color:var(--primary); letter-spacing:0.2em; text-transform:uppercase; }
.info-list { padding:20px 24px; display:flex; flex-direction:column; gap:16px; }
.info-row { display:flex; justify-content:space-between; align-items:center; }
.info-key { font-family:var(--font-mono); font-size:0.7rem; color:var(--text-muted); letter-spacing:0.1em; }
.info-val { font-size:0.95rem; color:var(--text); text-align:right; }
.share-btns { padding:20px 24px; display:flex; gap:10px; flex-wrap:wrap; }
.share-btn { flex:1; min-width:80px; padding:10px; background:rgba(74,55,40,0.05); border:1px solid rgba(74,55,40,0.12); color:var(--text-muted); font-family:var(--font-mono); font-size:0.65rem; letter-spacing:0.1em; cursor:pointer; border-radius:2px; text-align:center; transition:all 0.25s; text-decoration:none; }
.share-btn:hover { border-color:var(--primary); color:var(--primary); }

/* ─── RELATED PROJECTS ─── */
.related-section { padding:0 60px 80px; position:relative; z-index:2; }
.related-title { font-family:var(--font-display); font-size:1.5rem; font-weight:700; color:var(--text); margin-bottom:40px; letter-spacing:0.05em; }
.related-title span { color:var(--primary); }
.related-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
.related-card { text-decoration:none; color:var(--text); border:1px solid var(--border); border-radius:4px; overflow:hidden; background:var(--bg-card); transition:transform 0.3s, box-shadow 0.3s, border-color 0.3s; display:block; }
.related-card:hover { transform:translateY(-4px); box-shadow: none; border-color:rgba(74,55,40,0.2); }
.related-img { aspect-ratio:16/10; background:linear-gradient(135deg, #e8e6f0, #d8d0f0); overflow:hidden; }
.related-img img { width:100%; height:100%; object-fit:cover; transition:transform 0.4s; filter:brightness(0.8); }
.related-card:hover .related-img img { transform:scale(1.05); filter:brightness(1); }
.related-placeholder { width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-family:var(--font-display); font-size:1.5rem; color:rgba(74,55,40,0.12); }
.related-body { padding:16px 20px; }
.related-cat { font-family:var(--font-mono); font-size:0.65rem; color:var(--primary); letter-spacing:0.15em; text-transform:uppercase; margin-bottom:6px; }
.related-title2 { font-family:var(--font-display); font-size:0.9rem; font-weight:700; line-height:1.3; }

footer { position:relative; z-index:2; padding:30px 60px; border-top:1px solid rgba(74,55,40,0.08); display:flex; align-items:center; justify-content:space-between; background:rgba(250,248,245,0.92); }
.footer-copy { font-family:var(--font-mono); font-size:0.75rem; color:var(--text-muted); }
.footer-copy span { color:var(--primary); }

/* Rotate hint overlay */
.rotate-hint {
    position:absolute; inset:0; z-index:10;
    display:flex; align-items:center; justify-content:center;
    background:rgba(250,248,245,0.9); backdrop-filter:blur(4px);
    pointer-events:none; transition:opacity 0.4s;
}
.rotate-hint-inner { text-align:center; }
.rotate-icon { font-size:3rem; color:var(--primary); display:block; animation:rotate-spin 3s linear infinite; }
@keyframes rotate-spin { from{transform:rotate(0);} to{transform:rotate(360deg);} }
.rotate-text { font-family:var(--font-mono); font-size:0.8rem; color:var(--text-muted); letter-spacing:0.2em; margin-top:10px; display:block; }

/* ── 3D Model viewer overlays ── */
.model-loading {
    position:absolute; inset:0; z-index:20;
    display:none; flex-direction:column; align-items:center; justify-content:center;
    background:rgba(1,2,8,0.92); backdrop-filter:blur(8px);
    transition:opacity 0.4s;
}
.model-loading.visible { display:flex; }
.model-loading-spinner {
    width:54px; height:54px; border-radius:50%;
    border:2px solid rgba(74,55,40,0.12);
    border-top:2px solid var(--primary);
    animation:spin360 0.8s linear infinite;
    margin-bottom:20px;
}
@keyframes spin360 { to { transform:rotate(360deg); } }
.model-loading-label { font-family:var(--font-mono); font-size:0.7rem; color:var(--text-muted); letter-spacing:0.25em; margin-bottom:14px; }
.model-loading-track { width:200px; height:3px; background:rgba(74,55,40,0.08); border-radius:2px; overflow:hidden; }
.model-loading-bar  { height:100%; width:0%; background:linear-gradient(90deg,var(--primary),var(--accent)); border-radius:2px; transition:width 0.15s ease; }
.model-no-file {
    position:absolute; inset:0; z-index:12;
    display:none; flex-direction:column; align-items:center; justify-content:center; gap:14px;
    pointer-events:none;
}
.model-no-file.visible { display:flex; }
.model-no-file-icon  { font-size:4rem; opacity:0.12; line-height:1; }
.model-no-file-text  { font-family:var(--font-mono); font-size:0.7rem; color:var(--text-muted); letter-spacing:0.2em; text-align:center; line-height:2.2; opacity:0.5; }
.model-ctrl-hint {
    position:absolute; bottom:18px; left:50%; transform:translateX(-50%); z-index:8;
    display:none; gap:18px; padding:7px 18px;
    background:rgba(1,2,8,0.75); border:1px solid rgba(74,55,40,0.1); border-radius:20px;
    backdrop-filter:blur(10px); pointer-events:none; white-space:nowrap;
}
.model-ctrl-hint.visible { display:flex; }
.model-ctrl-hint span { font-family:var(--font-mono); font-size:0.58rem; color:var(--text-muted); letter-spacing:0.1em; }

@media (max-width:1024px) {
    nav { padding:20px 30px; }
    .viewer-section { padding:0 30px 60px; }
    .content-grid { grid-template-columns:1fr; padding:40px 30px; }
    .project-sidebar { position:static; }
    .related-section { padding:0 30px 60px; }
    .related-grid { grid-template-columns:1fr; }
    .project-hero { padding:120px 30px 60px; }
    footer { padding:30px; flex-direction:column; gap:16px; text-align:center; }
}
@media (max-width:768px) {
    nav { padding:14px 20px !important; }
    .nav-logo { font-size:1.1rem !important; letter-spacing:0.12em !important; }
    .nav-back { font-size:0.72rem !important; letter-spacing:0.06em !important; gap:6px !important; }
    .nav-back::before { content:'←' !important; }
    .project-hero { padding:100px 20px 50px !important; }
    .viewer-section { padding:0 16px 50px !important; }
    .content-grid { padding:30px 20px !important; }
    .related-section { padding:0 20px 50px !important; }
    .related-grid { grid-template-columns:1fr !important; gap:16px !important; }
    .project-title { font-size:clamp(1.6rem,6vw,3rem) !important; }
    .viewer-toolbar { flex-wrap:wrap; gap:6px; padding:10px 12px !important; }
    .mode-tab { font-size:0.65rem !important; padding:6px 10px !important; }
    .viewer-btn { font-size:0.62rem !important; padding:5px 8px !important; }
    .thumb-strip { padding:10px 12px !important; gap:6px !important; }
    .thumb-item { width:56px !important; height:40px !important; }
    .share-actions { flex-wrap:wrap; gap:8px !important; }
    .share-btn { font-size:0.65rem !important; padding:8px 12px !important; }
    footer { padding:24px 20px !important; }
}
@media (max-width:480px) {
    .project-meta-grid { grid-template-columns:1fr !important; }
    .viewer-main { min-height:280px !important; }
    .zoom-controls { right:8px !important; }
    .angle-badge { font-size:0.6rem !important; padding:4px 8px !important; }
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
.nav-cta { border: 1px solid #4a3728 !important; color: #4a3728 !important; border-radius: 0 !important; clip-path: none !important; padding: 9px 22px !important; }
.nav-cta:hover { background: #4a3728 !important; color: #faf8f5 !important; box-shadow: none !important; }
.nav-back { color: #7a6a5a !important; font-family: 'DM Mono', monospace !important; font-size: 0.78rem !important; }
.nav-back:hover { color: #4a3728 !important; }

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
<div class="cursor" id="cursor"></div>
<div class="cursor-ring" id="cursor-ring"></div>

<nav>
    <a href="index.php" class="nav-logo">ALI<span>.</span>AFZAL</a>
    <a href="projects.php" class="nav-back">All Projects</a>
</nav>

<?php if (!$project): ?>
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:20px;">
    <div style="font-family:var(--font-display);font-size:2rem;color:var(--primary);">Project Not Found</div>
    <a href="projects.php" style="color:var(--text-muted);text-decoration:none;font-family:var(--font-mono);">← Back to Projects</a>
</div>
<?php else: ?>

<!-- Project Hero -->
<section class="project-hero">
    <div class="hero-bg"></div>
    <div class="project-hero-content">
        <div class="project-breadcrumb">
            <a href="index.php">Home</a> / <a href="projects.php">Projects</a> / <?= htmlspecialchars($project['category']) ?>
        </div>
        <div class="project-category-badge"><?= htmlspecialchars($project['category']) ?></div>
        <h1 class="project-title"><?= htmlspecialchars($project['title']) ?></h1>
        <div class="project-meta-row">
            <?php if ($project['year']): ?>
            <div class="meta-item">
                <div class="meta-label">Year</div>
                <div class="meta-value"><?= htmlspecialchars($project['year']) ?></div>
            </div>
            <?php endif; ?>
            <?php if ($project['software']): ?>
            <div class="meta-item">
                <div class="meta-label">Software</div>
                <div class="meta-value"><?= htmlspecialchars($project['software']) ?></div>
            </div>
            <?php endif; ?>
            <?php if ($project['client']): ?>
            <div class="meta-item">
                <div class="meta-label">Client</div>
                <div class="meta-value"><?= htmlspecialchars($project['client']) ?></div>
            </div>
            <?php endif; ?>
            <div class="meta-item">
                <div class="meta-label">Views</div>
                <div class="meta-value"><?= number_format($project['view_count']) ?></div>
            </div>
        </div>
    </div>
</section>

<!-- 3D VIEWER -->
<section class="viewer-section">
    <div class="viewer-container">
        <div class="viewer-main" id="viewer-main">
            <!-- Rotate hint -->
            <div class="rotate-hint" style="display:none;" id="rotate-hint">
                <div class="rotate-hint-inner">
                    <span class="rotate-icon">⟳</span>
                    <span class="rotate-text">DRAG TO ROTATE • SCROLL TO ZOOM</span>
                </div>
            </div>

            <!-- Angle badge -->
            <div class="angle-badge" id="angle-badge">Perspective View</div>

            <!-- View count -->
            <div class="view-count-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg> <?= number_format($project['view_count']) ?> views
            </div>

            <!-- 3D Canvas for Three.js viewer -->
            <canvas id="viewer-canvas" style="display:none;position:absolute;inset:0;"></canvas>

            <!-- 3D model loading overlay -->
            <div class="model-loading" id="model-loading">
                <div class="model-loading-spinner"></div>
                <div class="model-loading-label" id="model-loading-label">LOADING MODEL…</div>
                <div class="model-loading-track">
                    <div class="model-loading-bar" id="model-loading-bar"></div>
                </div>
            </div>

            <!-- No model uploaded state -->
            <div class="model-no-file" id="model-no-file">
                <div class="model-no-file-icon">⬡</div>
                <div class="model-no-file-text">NO 3D MODEL UPLOADED<br>UPLOAD A .GLB · .GLTF · .OBJ · .STL<br>VIA THE ADMIN PANEL</div>
            </div>

            <!-- Controls hint bar (shown while model is active) -->
            <div class="model-ctrl-hint" id="model-ctrl-hint">
                <span><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="7"/><path d="M12 6v4"/></svg> DRAG · ROTATE</span>
                <span><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="7"/><path d="M12 6v4"/></svg> RIGHT-DRAG · PAN</span>
                <span><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg> SCROLL · ZOOM</span>
                <span>R · RESET CAMERA</span>
            </div>

            <!-- Image viewer -->
            <div class="img-viewer" id="img-viewer">
                <?php if (!empty($images)): ?>
                    <img src="<?= UPLOAD_URL ?>projects/<?= htmlspecialchars($images[0]['filename']) ?>"
                         id="main-img" alt="<?= htmlspecialchars($project['title']) ?>"
                         draggable="false">
                <?php else: ?>
                    <div style="font-family:var(--font-display);font-size:3rem;color:rgba(74,55,40,0.12);text-align:center;">
                        <div>3D</div>
                        <div style="font-size:1rem;margin-top:10px;letter-spacing:0.2em;">No images uploaded</div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Zoom controls -->
            <div class="zoom-controls">
                <button class="zoom-btn" onclick="zoomIn()" title="Zoom In">+</button>
                <button class="zoom-btn" onclick="zoomReset()" title="Reset" style="font-size:0.7rem;">⊙</button>
                <button class="zoom-btn" onclick="zoomOut()" title="Zoom Out">−</button>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="viewer-toolbar">
            <div class="mode-tabs">
                <button class="mode-tab active" onclick="setMode('image')" id="tab-image">Gallery</button>
                <button class="mode-tab" onclick="setMode('360')" id="tab-360">⬡ 360° MODEL<?php if(!$modelUrl): ?>&thinsp;<span style="font-size:0.55rem;opacity:0.4;">(none)</span><?php endif; ?></button>
            </div>

            <div style="width:1px;height:24px;background:var(--border);margin:0 4px;"></div>

            <!-- Angle buttons -->
            <div id="angle-btns" style="display:flex;gap:8px;flex-wrap:wrap;">
                <button class="viewer-btn active" onclick="setAngle('perspective','Perspective View',0)">PERSP</button>
                <button class="viewer-btn" onclick="setAngle('front','Front View',1)">FRONT</button>
                <button class="viewer-btn" onclick="setAngle('back','Back View',2)">BACK</button>
                <button class="viewer-btn" onclick="setAngle('left','Left View',3)">LEFT</button>
                <button class="viewer-btn" onclick="setAngle('right','Right View',4)">RIGHT</button>
                <button class="viewer-btn" onclick="setAngle('top','Top View',5)">TOP</button>
            </div>

            <div class="viewer-spacer"></div>
            <span class="viewer-hint" id="viewer-hint"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="7"/><path d="M12 6v4"/></svg> Drag to rotate · Scroll to zoom</span>
            <button class="viewer-btn" onclick="toggleFullscreen()"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg> FULLSCREEN</button>
        </div>

        <!-- Thumbnail strip -->
        <?php if (!empty($images)): ?>
        <div class="thumb-strip" id="thumb-strip">
            <?php foreach ($images as $i => $img): ?>
            <div class="thumb-item <?= $i===0?'active':'' ?>"
                 onclick="selectImage(<?= $i ?>, '<?= UPLOAD_URL ?>projects/<?= htmlspecialchars($img['filename']) ?>', '<?= htmlspecialchars($img['view_angle'] ?? 'View '.($i+1)) ?>')"
                 data-angle="<?= htmlspecialchars($img['view_angle'] ?? '') ?>">
                <img src="<?= UPLOAD_URL ?>projects/<?= htmlspecialchars($img['filename']) ?>" alt="View <?= $i+1 ?>" loading="lazy">
            </div>
            <?php endforeach; ?>
            <?php if (empty($images)): ?>
            <div class="thumb-item" style="color:rgba(74,55,40,0.2);">No images</div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Content -->
<div class="content-grid">
    <div class="project-description">
        <h2>// Project Overview</h2>
        <p><?= nl2br(htmlspecialchars($project['description'] ?? '')) ?></p>
        <?php if ($project['tags']): ?>
        <div class="tags-wrap">
            <?php foreach (explode(',', $project['tags']) as $t): ?>
            <span class="tag"><?= htmlspecialchars(trim($t)) ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="project-sidebar">
        <div class="info-card">
            <div class="info-card-header">// Project Info</div>
            <div class="info-list">
                <div class="info-row">
                    <span class="info-key">Category</span>
                    <span class="info-val"><?= htmlspecialchars($project['category']) ?></span>
                </div>
                <?php if ($project['year']): ?>
                <div class="info-row">
                    <span class="info-key">Year</span>
                    <span class="info-val"><?= htmlspecialchars($project['year']) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($project['software']): ?>
                <div class="info-row">
                    <span class="info-key">Software</span>
                    <span class="info-val"><?= htmlspecialchars($project['software']) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($project['client']): ?>
                <div class="info-row">
                    <span class="info-key">Client</span>
                    <span class="info-val"><?= htmlspecialchars($project['client']) ?></span>
                </div>
                <?php endif; ?>
                <div class="info-row">
                    <span class="info-key">Images</span>
                    <span class="info-val"><?= count($images) ?> views</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Views</span>
                    <span class="info-val"><?= number_format($project['view_count']) ?></span>
                </div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-card-header">// Share</div>
            <div class="share-btns">
                <a href="#" onclick="copyLink()" class="share-btn"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg> COPY LINK</a>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode(SITE_URL.'/project.php?slug='.$project['slug']) ?>&text=<?= urlencode('Check out this 3D art by Ali Afzal: '.$project['title']) ?>" target="_blank" class="share-btn">𝕏 TWITTER</a>
            </div>
        </div>

        <a href="index.php#contact" style="display:block;padding:16px 24px;background:linear-gradient(135deg,var(--primary),var(--accent));color:var(--bg-deep);font-family:var(--font-display);font-size:0.8rem;font-weight:700;letter-spacing:0.15em;text-align:center;text-decoration:none;border-radius:2px;100%,0% 100%);transition:box-shadow 0.3s;" onmouseover="this.style.boxShadow='var(--glow)'" onmouseout="this.style.boxShadow='none'">
            COMMISSION THIS STYLE
        </a>
    </div>
</div>

<!-- Related Projects -->
<?php if (!empty($related)): ?>
<div class="related-section">
    <div class="related-title">More <span><?= htmlspecialchars($project['category']) ?></span> Work</div>
    <div class="related-grid">
        <?php foreach ($related as $r): $rt = $r['tf'] ? UPLOAD_URL.'thumbnails/'.$r['tf'] : null; ?>
        <a href="project.php?slug=<?= htmlspecialchars($r['slug']) ?>" class="related-card">
            <div class="related-img">
                <?php if ($rt): ?>
                    <img src="<?= $rt ?>" alt="<?= htmlspecialchars($r['title']) ?>">
                <?php else: ?>
                    <div class="related-placeholder">3D</div>
                <?php endif; ?>
            </div>
            <div class="related-body">
                <div class="related-cat"><?= htmlspecialchars($r['category']) ?></div>
                <div class="related-title2"><?= htmlspecialchars($r['title']) ?></div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php endif; ?>

<footer>
    <div class="footer-copy">© <?php echo date('Y'); ?> <span>Ali Afzal</span>. All rights reserved. Developed by Xythonix.</div>
    <a href="projects.php" style="font-family:var(--font-mono);font-size:0.75rem;color:var(--text-muted);text-decoration:none;">← Back to All Projects</a>
</footer>

<script>
// ─── Cursor
const cursor=document.getElementById('cursor'),ring=document.getElementById('cursor-ring');
document.addEventListener('mousemove',e=>{
    cursor.style.left=e.clientX+'px';cursor.style.top=e.clientY+'px';
    setTimeout(()=>{ring.style.left=e.clientX+'px';ring.style.top=e.clientY+'px';},80);
});
document.querySelectorAll('a,button').forEach(el=>{
    el.addEventListener('mouseenter',()=>{cursor.style.transform='translate(-50%,-50%) scale(2)';ring.style.width='60px';ring.style.height='60px';});
    el.addEventListener('mouseleave',()=>{cursor.style.transform='translate(-50%,-50%) scale(1)';ring.style.width='40px';ring.style.height='40px';});
});

// ─── Image data from PHP
const images = <?= json_encode(array_map(fn($img) => [
    'url' => UPLOAD_URL . 'projects/' . $img['filename'],
    'angle' => $img['view_angle'] ?? 'View',
    'caption' => $img['caption'] ?? ''
], $images)) ?>;

// ─── 3D Model data from PHP
const MODEL_URL  = <?= json_encode($modelUrl) ?>;
const MODEL_EXT  = <?= json_encode($modelExt) ?>;
const MODEL_EXISTS_ON_DISK = <?= json_encode($modelFileExists) ?>;  // PHP confirmed file exists on server
console.log('[3D Viewer] MODEL_URL =', MODEL_URL, '| MODEL_EXT =', MODEL_EXT, '| File on disk:', MODEL_EXISTS_ON_DISK);
if (MODEL_URL && !MODEL_EXISTS_ON_DISK) {
    console.warn('[3D Viewer] WARNING: Model file does NOT exist at upload path on server! Check uploads/models/ folder.');
}

// ─── State
let currentMode = 'image';
let currentIdx = 0;
let zoom = 1, isDragging = false;
let startX, startY, imgTransX = 0, imgTransY = 0;
let rotX = 0, rotY = 0, lastMouseX, lastMouseY;

const mainImg = document.getElementById('main-img');
const imgViewer = document.getElementById('img-viewer');
const angleBadge = document.getElementById('angle-badge');
const rotateHint = document.getElementById('rotate-hint');
const viewerMain = document.getElementById('viewer-main');

// Hide hint on first interaction
viewerMain.addEventListener('mousedown', () => {
    rotateHint.style.opacity = '0';
    setTimeout(() => rotateHint.style.display = 'none', 400);
});

// ─── Select image
function selectImage(idx, url, angle) {
    currentIdx = idx;
    if (mainImg) {
        mainImg.style.opacity = '0';
        mainImg.style.transition = 'opacity 0.3s';
        setTimeout(() => {
            mainImg.src = url;
            mainImg.style.opacity = '1';
        }, 150);
    }
    zoom = 1; imgTransX = 0; imgTransY = 0;
    applyImageTransform();
    angleBadge.textContent = formatAngle(angle);
    document.querySelectorAll('.thumb-item').forEach((t,i) => t.classList.toggle('active', i===idx));
    updateAngleBtns(angle);
}

function formatAngle(a) {
    const map = { front:'Front View', back:'Back View', left:'Left View', right:'Right View', top:'Top View', perspective:'Perspective View', bottom:'Bottom View' };
    return map[a?.toLowerCase()] || (a ? a.charAt(0).toUpperCase()+a.slice(1)+' View' : 'View');
}

function updateAngleBtns(angle) {
    const a = (angle||'').toLowerCase();
    document.querySelectorAll('#angle-btns .viewer-btn').forEach(b => {
        b.classList.toggle('active', b.textContent.toLowerCase().includes(a.slice(0,4)));
    });
}

// ─── Angle navigation
function setAngle(key, label, preferIdx) {
    document.querySelectorAll('#angle-btns .viewer-btn').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
    angleBadge.textContent = label;

    // Find image with matching angle
    const match = images.findIndex(img => (img.angle||'').toLowerCase() === key.toLowerCase());
    if (match >= 0) {
        selectImage(match, images[match].url, images[match].angle);
    } else if (images[preferIdx]) {
        selectImage(preferIdx, images[preferIdx].url, images[preferIdx].angle);
    }
}

// ─── Mode switching
function setMode(mode) {
    currentMode = mode;
    document.getElementById('tab-image').classList.toggle('active', mode==='image');
    document.getElementById('tab-360').classList.toggle('active', mode==='360');
    document.getElementById('angle-btns').style.display = mode==='image' ? 'flex' : 'none';

    if (mode === '360') {
        init360Viewer();
        document.getElementById('viewer-hint').textContent = '⟳ Drag to rotate 360°';
    } else {
        document.getElementById('viewer-canvas').style.display = 'none';
        imgViewer.style.display = 'flex';
        document.getElementById('viewer-hint').textContent = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="7"/><path d="M12 6v4"/></svg> Drag to rotate · Scroll to zoom';
    }
}

// ─── IMAGE DRAG ROTATE (simulated rotation via switching images)
let imgDragStartX, imgDragStartY, imgDragActive = false;
imgViewer.addEventListener('mousedown', e => {
    imgDragActive = true;
    imgDragStartX = e.clientX;
    imgDragStartY = e.clientY;
    isDragging = false;
});
imgViewer.addEventListener('mousemove', e => {
    if (!imgDragActive) return;
    const dx = e.clientX - imgDragStartX;
    const dy = e.clientY - imgDragStartY;
    if (Math.abs(dx) > 5 || Math.abs(dy) > 5) isDragging = true;
    if (images.length > 1 && Math.abs(dx) > 60) {
        const dir = dx > 0 ? -1 : 1;
        const newIdx = (currentIdx + dir + images.length) % images.length;
        selectImage(newIdx, images[newIdx].url, images[newIdx].angle);
        imgDragStartX = e.clientX;
    }
});
imgViewer.addEventListener('mouseup', () => { imgDragActive = false; });

// Touch support
imgViewer.addEventListener('touchstart', e => {
    imgDragStartX = e.touches[0].clientX;
}, { passive:true });
imgViewer.addEventListener('touchmove', e => {
    const dx = e.touches[0].clientX - imgDragStartX;
    if (images.length > 1 && Math.abs(dx) > 70) {
        const dir = dx > 0 ? -1 : 1;
        const newIdx = (currentIdx + dir + images.length) % images.length;
        selectImage(newIdx, images[newIdx].url, images[newIdx].angle);
        imgDragStartX = e.touches[0].clientX;
    }
}, { passive:true });

// ─── ZOOM
function applyImageTransform() {
    if (mainImg) mainImg.style.transform = `translate(${imgTransX}px,${imgTransY}px) scale(${zoom})`;
}
function zoomIn() { zoom = Math.min(zoom * 1.25, 4); applyImageTransform(); }
function zoomOut() { zoom = Math.max(zoom / 1.25, 0.5); applyImageTransform(); }
function zoomReset() { zoom=1; imgTransX=0; imgTransY=0; applyImageTransform(); }

imgViewer.addEventListener('wheel', e => {
    e.preventDefault();
    if (e.deltaY < 0) zoomIn(); else zoomOut();
}, { passive:false });

// ─── 360° VIEWER — loads the real uploaded 3D model
let renderer360, scene360, camera360, controls360, animId360;
let modelLoaded = false;

function init360Viewer() {
    const canvas      = document.getElementById('viewer-canvas');
    const loadingEl   = document.getElementById('model-loading');
    const loadingBar  = document.getElementById('model-loading-bar');
    const loadingLbl  = document.getElementById('model-loading-label');
    const noFileEl    = document.getElementById('model-no-file');
    const ctrlHint    = document.getElementById('model-ctrl-hint');

    // Show canvas, hide image viewer
    canvas.style.display  = 'block';
    imgViewer.style.display = 'none';

    // If renderer already initialised, just resize and bail
    if (renderer360) {
        renderer360.setSize(viewerMain.offsetWidth, viewerMain.offsetHeight);
        camera360.aspect = viewerMain.offsetWidth / viewerMain.offsetHeight;
        camera360.updateProjectionMatrix();
        controls360.update();
        return;
    }

    // ── No model uploaded ──────────────────────────────────────
    if (!MODEL_URL) {
        noFileEl.classList.add('visible');
        return;
    }
    // ── File missing on disk — tell admin clearly ──────────────
    if (!MODEL_EXISTS_ON_DISK) {
        loadingEl.classList.remove('visible');
        noFileEl.querySelector('.model-no-file-text').innerHTML =
            'MODEL FILE MISSING ON SERVER<br>' +
            '<span style="font-size:0.7em;opacity:0.7">The model was registered in the database but the file<br>' +
            'was not found in <strong>uploads/models/</strong><br>' +
            'Please re-upload the model from the admin panel.</span>';
        noFileEl.classList.add('visible');
        return;
    }

    // ── Renderer ──────────────────────────────────────────────
    renderer360 = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer360.setSize(viewerMain.offsetWidth, viewerMain.offsetHeight);
    renderer360.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer360.setClearColor(0xf0ebe3, 1);
    renderer360.shadowMap.enabled = true;
    renderer360.shadowMap.type    = THREE.PCFSoftShadowMap;
    renderer360.outputEncoding    = THREE.sRGBEncoding;
    renderer360.toneMapping       = THREE.ACESFilmicToneMapping;
    renderer360.toneMappingExposure = 1.2;

    // ── Scene ─────────────────────────────────────────────────
    scene360 = new THREE.Scene();

    // Subtle grid floor
    const grid = new THREE.GridHelper(20, 30, 0xede8e0, 0xf0ebe3);
    grid.material.opacity = 0.04;
    grid.material.transparent = true;
    grid.position.y = -2;
    scene360.add(grid);

    // Background particle field
    const pGeo = new THREE.BufferGeometry();
    const pPos = [];
    for (let i = 0; i < 300; i++) {
        pPos.push((Math.random()-0.5)*30, (Math.random()-0.5)*30, (Math.random()-0.5)*30);
    }
    pGeo.setAttribute('position', new THREE.Float32BufferAttribute(pPos, 3));
    scene360.add(new THREE.Points(pGeo, new THREE.PointsMaterial({
        color: 0x8b6a4f, size: 0.04, transparent: true, opacity: 0.15
    })));

    // ── Lighting ──────────────────────────────────────────────
    scene360.add(new THREE.AmbientLight(0xffffff, 1.0));

    const keyLight = new THREE.DirectionalLight(0x8b6a4f, 1.4);
    keyLight.position.set(5, 8, 5);
    keyLight.castShadow = true;
    scene360.add(keyLight);

    const fillLight = new THREE.DirectionalLight(0xf0ebe3, 0.5);
    fillLight.position.set(-6, -2, 4);
    scene360.add(fillLight);

    const rimLight = new THREE.DirectionalLight(0xc9a96e, 0.4);
    rimLight.position.set(0, -5, -8);
    scene360.add(rimLight);

    var topLight = new THREE.PointLight(0x8b6a4f, 0.6, 25); topLight.position.set(0, 6, 0); scene360.add(topLight);

    // ── Camera ─────────────────────────────────────────────────
    camera360 = new THREE.PerspectiveCamera(45, viewerMain.offsetWidth / viewerMain.offsetHeight, 0.01, 1000);
    camera360.position.set(0, 0, 5);

    // ── OrbitControls ──────────────────────────────────────────
    controls360 = new THREE.OrbitControls(camera360, canvas);
    controls360.enableDamping    = true;
    controls360.dampingFactor    = 0.07;
    controls360.autoRotate       = true;
    controls360.autoRotateSpeed  = 1.5;
    controls360.enableZoom       = true;
    controls360.zoomSpeed        = 0.8;
    controls360.enablePan        = true;
    controls360.panSpeed         = 0.6;
    controls360.minDistance      = 0.5;
    controls360.maxDistance      = 50;

    // Stop auto-rotate on user interaction, resume after 3s idle
    let autoRotateTimer;
    canvas.addEventListener('pointerdown', () => {
        controls360.autoRotate = false;
        clearTimeout(autoRotateTimer);
    });
    canvas.addEventListener('pointerup', () => {
        autoRotateTimer = setTimeout(() => { controls360.autoRotate = true; }, 3000);
    });

    // R key = reset camera
    document.addEventListener('keydown', e => {
        if (e.key === 'r' || e.key === 'R') {
            if (currentMode !== '360') return;
            controls360.reset();
        }
    });

    // ── Animate ────────────────────────────────────────────────
    function animate360() {
        animId360 = requestAnimationFrame(animate360);
        controls360.update();
        renderer360.render(scene360, camera360);
    }

    // ── Load model by extension ────────────────────────────────
    loadingEl.classList.add('visible');
    loadingBar.style.width = '5%';
    loadingLbl.textContent = 'LOADING MODEL…';

    function onProgress(e) {
        if (e.lengthComputable) {
            const pct = Math.round((e.loaded / e.total) * 90) + 5;
            loadingBar.style.width = pct + '%';
            loadingLbl.textContent = 'LOADING… ' + pct + '%';
        }
    }

    function onLoaded(object) {
        loadingBar.style.width = '100%';
        loadingLbl.textContent = 'DONE';

        // Centre + scale model to fit view
        const box    = new THREE.Box3().setFromObject(object);
        const centre = new THREE.Vector3();
        box.getCenter(centre);
        const size   = box.getSize(new THREE.Vector3()).length();
        const scale  = 3.5 / size;

        object.position.sub(centre);
        object.scale.setScalar(scale);
        object.position.y -= box.getSize(new THREE.Vector3()).y * scale * 0.5 - 0.1;

        // Apply standard material to meshes that have none / basic material
        object.traverse(child => {
            if (child.isMesh) {
                child.castShadow    = true;
                child.receiveShadow = true;
                if (!child.material || child.material.type === 'MeshBasicMaterial') {
                    child.material = new THREE.MeshStandardMaterial({
                        color: 0x88ccdd,
                        metalness: 0.3,
                        roughness: 0.5,
                    });
                }
                // Ensure double-side for thin meshes (STL etc.)
                if (child.material) child.material.side = THREE.DoubleSide;
            }
        });

        scene360.add(object);

        // Fit camera
        const camDist = size * scale * 1.8;
        camera360.position.set(0, camDist * 0.4, camDist);
        controls360.target.set(0, 0, 0);
        controls360.minDistance = camDist * 0.1;
        controls360.maxDistance = camDist * 8;
        controls360.update();

        // Fade out loader
        setTimeout(() => {
            loadingEl.style.opacity = '0';
            setTimeout(() => {
                loadingEl.classList.remove('visible');
                loadingEl.style.opacity = '';
                ctrlHint.classList.add('visible');
            }, 400);
        }, 300);

        modelLoaded = true;
        animate360();
    }

    // ── Show a rich error and allow retry ──────────────────────
    function showError(headline, detail) {
        loadingEl.classList.remove('visible');
        console.error('[3D Viewer]', headline, detail);
        Swal.fire({
            icon: 'error',
            title: headline,
            html: '<small style="color:#7a6a5a;font-family:monospace;font-size:0.72rem;word-break:break-all;">' + (detail||'Unknown error') + '</small>',
            background: '#f0ebe3',
            color: '#1a1714',
            confirmButtonText: 'Retry',
            showCancelButton: true,
            cancelButtonText: 'Close',
            confirmButtonColor: '#4a3728',
        }).then(function(r) {
            if (r.isConfirmed) {
                modelLoaded = false;
                if (renderer360) { try { renderer360.dispose(); } catch(e){} renderer360 = null; }
                scene360 = null; camera360 = null; controls360 = null;
                if (animId360) cancelAnimationFrame(animId360);
                init360Viewer();
            }
        });
    }

    function onError(err) {
        // Extract as much info as possible from the THREE.js error event
        var status  = (err && err.target && err.target.status)  || 0;
        var resUrl  = (err && err.target && err.target.responseURL) || MODEL_URL;
        var msg     = (err && err.message) || '';
        if (status === 404) {
            showError('Model File Not Found (404)', 'The file was not found at:<br>' + resUrl + '<br><br>Check that the model was uploaded correctly in the admin panel.');
        } else if (status === 403) {
            showError('Access Denied (403)', 'The server refused access to the model file.<br>Check file permissions on the uploads/models/ folder (should be 755).');
        } else if (status >= 400) {
            showError('HTTP Error ' + status, 'Server returned error ' + status + ' for:<br>' + resUrl);
        } else if (msg) {
            showError('Model Load Failed', msg);
        } else {
            showError('Model Load Failed', 'Could not fetch the model file. This usually means:<br>1. The model was not uploaded yet<br>2. The uploads/models/ folder is missing<br>3. File permissions are wrong (needs 755)<br><br>URL attempted:<br>' + MODEL_URL);
        }
    }

    // ── Pre-flight fetch check before handing to THREE loader ──
    // This catches 404 / 403 / server errors with a clear message
    // instead of the cryptic THREE.js XHR failure
    function loadModelWithCheck(ext) {
        fetch(MODEL_URL, { method: 'HEAD' })
            .then(function(res) {
                if (!res.ok) {
                    // Fake a THREE-style error object so onError can read status
                    onError({ target: { status: res.status, responseURL: MODEL_URL } });
                    return;
                }
                // File exists — now load with THREE
                dispatchLoader(ext);
            })
            .catch(function(fetchErr) {
                // fetch itself failed (CORS, network down, etc.)
                // Fall through to THREE loader which might still work (same-origin)
                console.warn('[3D Viewer] HEAD check failed, trying loader anyway:', fetchErr.message);
                dispatchLoader(ext);
            });
    }

    function dispatchLoader(ext) {
        if (ext === 'glb' || ext === 'gltf') {
            const loader = new THREE.GLTFLoader();
            loader.load(MODEL_URL, function(gltf) { onLoaded(gltf.scene); }, onProgress, onError);

        } else if (ext === 'obj') {
            const loader = new THREE.OBJLoader();
            loader.load(MODEL_URL, function(obj) { onLoaded(obj); }, onProgress, onError);

        } else if (ext === 'stl') {
            const loader = new THREE.STLLoader();
            loader.load(MODEL_URL, function(geometry) {
                geometry.computeVertexNormals();
                const mat  = new THREE.MeshStandardMaterial({ color: 0x8b6a4f, metalness: 0.25, roughness: 0.6, side: THREE.DoubleSide });
                const mesh = new THREE.Mesh(geometry, mat);
                onLoaded(mesh);
            }, onProgress, onError);

        } else {
            loadingEl.classList.remove('visible');
            noFileEl.querySelector('.model-no-file-text').innerHTML =
                'FORMAT <strong style="color:var(--primary)">.' + (ext||'?').toUpperCase() + '</strong> CANNOT BE PREVIEWED IN BROWSER<br>SUPPORTED: GLB · GLTF · OBJ · STL';
            noFileEl.classList.add('visible');
        }
    }

    const ext = MODEL_EXT;

    if (ext === 'glb' || ext === 'gltf' || ext === 'obj' || ext === 'stl') {
        loadModelWithCheck(ext);
    } else {
        loadingEl.classList.remove('visible');
        noFileEl.querySelector('.model-no-file-text').innerHTML =
            'FORMAT <strong style="color:var(--primary)">.' + (ext||'?').toUpperCase() + '</strong> CANNOT BE PREVIEWED IN BROWSER<br>SUPPORTED: GLB · GLTF · OBJ · STL';
        noFileEl.classList.add('visible');
    }
}

// ─── Fullscreen
function toggleFullscreen() {
    const el = document.getElementById('viewer-main');
    if (!document.fullscreenElement) el.requestFullscreen().catch(err => {});
    else document.exitFullscreen();
}
document.addEventListener('fullscreenchange', () => {
    if (renderer360) {
        renderer360.setSize(viewerMain.offsetWidth, viewerMain.offsetHeight);
        camera360.aspect = viewerMain.offsetWidth / viewerMain.offsetHeight;
        camera360.updateProjectionMatrix();
        if (controls360) controls360.update();
    }
});

// ─── Copy link
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        Swal.fire({ icon:'success', title:'Link Copied!', text:'Share it with the world.', timer:1500, showConfirmButton:false, background:'#f0ebe3', color:'#1a1714' });
    });
    return false;
}

// Keyboard shortcuts
document.addEventListener('keydown', e => {
    if (e.key === 'ArrowRight' && images.length > 1) selectImage((currentIdx+1)%images.length, images[(currentIdx+1)%images.length].url, images[(currentIdx+1)%images.length].angle);
    if (e.key === 'ArrowLeft' && images.length > 1) { const p=(currentIdx-1+images.length)%images.length; selectImage(p,images[p].url,images[p].angle); }
    if (e.key === 'f' || e.key === 'F') toggleFullscreen();
    if (e.key === '+' || e.key === '=') zoomIn();
    if (e.key === '-') zoomOut();
    if (e.key === '0') zoomReset();
});
</script>
</body>
</html>