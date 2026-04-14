<?php
require_once '../includes/config.php';
requireAdmin();
header('Content-Type: application/json');

// Support both multipart/form-data (file uploads) and application/json
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (str_contains($contentType, 'application/json')) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
} else {
    $data = $_POST;
}

$action = $data['action'] ?? '';

switch ($action) {
    case 'create_project':    createProject($data);    break;
    case 'update_project':    updateProject($data);    break;
    case 'delete_project':    deleteProject($data);    break;
    case 'delete_image':      deleteImage($data);      break;
    case 'set_primary_image': setPrimaryImage($data);  break;
    case 'create_category':   createCategory($data);   break;
    case 'update_category':   updateCategory($data);   break;
    case 'delete_category':   deleteCategory_($data);  break;
    case 'toggle_featured':   toggleFeatured($data);   break;
    case 'toggle_status':     toggleStatus($data);     break;
    case 'save_settings':     saveSettings($data);     break;
    case 'delete_message':    deleteMessage($data);    break;
    case 'mark_read':         markRead($data);         break;
    case 'toggle_star':       toggleStar($data);       break;
    case 'delete_model_3d':   deleteModel3d($data);    break;
    default: jsonResponse(['success' => false, 'message' => 'Unknown action: ' . $action]);
}

/* ============================================================
   IMAGE UPLOAD HELPER
   ============================================================ */
function getUploadPath(): string {
    // Use UPLOAD_PATH constant if defined in config, else fallback
    if (defined('UPLOAD_PATH')) return rtrim(UPLOAD_PATH, '/') . '/';
    return dirname(__DIR__) . '/uploads/';
}

function handleImageUploads(int $projectId): array {
    $errors = [];

    // Nothing uploaded — perfectly fine
    if (empty($_FILES['images']) || empty($_FILES['images']['name'][0])) {
        return $errors;
    }

    $baseDir  = getUploadPath();
    $uploadDir = $baseDir . 'projects/';
    $thumbDir  = $baseDir . 'thumbnails/';

    // Create directories if missing
    foreach ([$uploadDir, $thumbDir] as $dir) {
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                $errors[] = "Cannot create directory: $dir — check server permissions.";
                return $errors;
            }
        }
        // Verify directory is writable
        if (!is_writable($dir)) {
            $errors[] = "Directory not writable: $dir — check server permissions.";
            return $errors;
        }
    }

    $db = getDB();

    // Does a primary image already exist for this project?
    $stmt = $db->prepare("SELECT COUNT(*) FROM project_images WHERE project_id=? AND is_primary=1");
    $stmt->execute([$projectId]);
    $primaryExists = (bool) $stmt->fetchColumn();

    // Which file index should be primary (0 = first image by default)
    $primaryIndex = intval($_POST['primary_index'] ?? 0);

    // Angles sent as angles[0], angles[1], …
    $angles = $_POST['angles'] ?? [];

    // Current max sort_order for this project
    $sortStmt = $db->prepare("SELECT COALESCE(MAX(sort_order), 0) FROM project_images WHERE project_id=?");
    $sortStmt->execute([$projectId]);
    $sortOrder = (int) $sortStmt->fetchColumn();

    $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $maxSize     = 10 * 1024 * 1024; // 10 MB

    $fileCount = count($_FILES['images']['name']);

    for ($i = 0; $i < $fileCount; $i++) {
        $origName = $_FILES['images']['name'][$i]     ?? '';
        $tmpPath  = $_FILES['images']['tmp_name'][$i] ?? '';
        $errCode  = $_FILES['images']['error'][$i]    ?? UPLOAD_ERR_NO_FILE;
        $size     = $_FILES['images']['size'][$i]     ?? 0;

        // Skip empty slots
        if ($errCode === UPLOAD_ERR_NO_FILE) continue;

        // Report real upload errors
        if ($errCode !== UPLOAD_ERR_OK) {
            $errors[] = "'{$origName}' upload error (code {$errCode}).";
            continue;
        }

        // Verify it is actually an uploaded file
        if (!is_uploaded_file($tmpPath)) {
            $errors[] = "'{$origName}' failed security check.";
            continue;
        }

        // Size check
        if ($size > $maxSize) {
            $errors[] = "'{$origName}' exceeds 10 MB limit.";
            continue;
        }

        // Real MIME check via finfo
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tmpPath);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMime)) {
            $errors[] = "'{$origName}' is not an allowed image type ({$mimeType}).";
            continue;
        }

        // Safe unique filename
        $safeExt = match($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
            default      => 'jpg',
        };
        $filename = 'proj_' . $projectId . '_' . uniqid('', true) . '.' . $safeExt;
        $destPath = $uploadDir . $filename;

        if (!move_uploaded_file($tmpPath, $destPath)) {
            $errors[] = "Could not save '{$origName}'. Check permissions on: {$uploadDir}";
            continue;
        }

        // Generate thumbnail
        generateThumbnail($destPath, $thumbDir . $filename, 400, 400);

        // Only the file at $primaryIndex gets is_primary=1 (and only if none exists yet)
        $isPrimary = (!$primaryExists && $i === $primaryIndex) ? 1 : 0;
        if ($isPrimary) $primaryExists = true;

        $sortOrder++;
        $angle = sanitize($angles[$i] ?? '');

        $db->prepare("
            INSERT INTO project_images
                (project_id, filename, original_name, view_angle, sort_order, is_primary)
            VALUES (?, ?, ?, ?, ?, ?)
        ")->execute([$projectId, $filename, sanitize($origName), $angle, $sortOrder, $isPrimary]);

        // Keep projects.thumbnail in sync with the primary image
        if ($isPrimary) {
            $db->prepare("UPDATE projects SET thumbnail=? WHERE id=?")
               ->execute([$filename, $projectId]);
        }
    }

    return $errors;
}

/* ============================================================
   3D MODEL UPLOAD HELPER
   ============================================================ */
function handleModelUpload(int $projectId, ?string $oldModelFile = null): ?string {
    // No file submitted — nothing to do
    if (
        empty($_FILES['model_3d']) ||
        ($_FILES['model_3d']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE
    ) {
        return null;
    }

    $errCode  = $_FILES['model_3d']['error']    ?? UPLOAD_ERR_NO_FILE;
    $tmpPath  = $_FILES['model_3d']['tmp_name'] ?? '';
    $origName = $_FILES['model_3d']['name']     ?? '';
    $size     = $_FILES['model_3d']['size']     ?? 0;

    // Real PHP upload error
    if ($errCode !== UPLOAD_ERR_OK) {
        throw new RuntimeException("3D model upload error (code {$errCode}).");
    }

    // Security: must be a genuine uploaded file
    if (!is_uploaded_file($tmpPath)) {
        throw new RuntimeException("3D model failed security check.");
    }

    // Size limit — 200 MB
    $maxSize = 200 * 1024 * 1024;
    if ($size > $maxSize) {
        throw new RuntimeException("3D model '{$origName}' exceeds 200 MB limit.");
    }

    // Allowed extensions (no executable types)
    $allowedExts = [
        'glb', 'gltf', 'fbx', 'obj', 'stl', 'blend',
        'dae', '3ds', 'ply', 'abc',
        'usd', 'usda', 'usdc', 'usdz',
        'x3d', 'wrl',
    ];

    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExts, true)) {
        throw new RuntimeException(
            "'{$origName}' is not an allowed 3D model format. " .
            "Allowed: " . implode(', ', array_map('strtoupper', $allowedExts)) . "."
        );
    }

    // Build destination directory
    $baseDir  = getUploadPath();
    $modelDir = $baseDir . 'models/';

    if (!is_dir($modelDir)) {
        if (!mkdir($modelDir, 0755, true)) {
            throw new RuntimeException("Cannot create models directory: {$modelDir}");
        }
    }
    if (!is_writable($modelDir)) {
        throw new RuntimeException("Models directory not writable: {$modelDir}");
    }

    // Unique, sanitised filename — keep original extension
    $filename = 'model_' . $projectId . '_' . uniqid('', true) . '.' . $ext;
    $destPath = $modelDir . $filename;

    if (!move_uploaded_file($tmpPath, $destPath)) {
        throw new RuntimeException("Could not save 3D model. Check permissions on: {$modelDir}");
    }

    // Delete the previous model file for this project (on update)
    if ($oldModelFile) {
        $oldPath = $modelDir . $oldModelFile;
        if (is_file($oldPath)) {
            @unlink($oldPath);
        }
    }

    return $filename;
}

/* ── Thumbnail generator (GD) ────────────────────────────── */
function generateThumbnail(string $src, string $dest, int $maxW, int $maxH): void {
    if (!function_exists('imagecreatefromjpeg')) return;

    $info = @getimagesize($src);
    if (!$info) return;
    [$srcW, $srcH, $type] = $info;

    $image = match($type) {
        IMAGETYPE_JPEG => @imagecreatefromjpeg($src),
        IMAGETYPE_PNG  => @imagecreatefrompng($src),
        IMAGETYPE_WEBP => @imagecreatefromwebp($src),
        IMAGETYPE_GIF  => @imagecreatefromgif($src),
        default        => null,
    };
    if (!$image) return;

    $ratio = min($maxW / $srcW, $maxH / $srcH, 1.0);
    $dstW  = (int) round($srcW * $ratio);
    $dstH  = (int) round($srcH * $ratio);
    $thumb = imagecreatetruecolor($dstW, $dstH);

    if (in_array($type, [IMAGETYPE_PNG, IMAGETYPE_GIF])) {
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        imagefill($thumb, 0, 0, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
    }

    imagecopyresampled($thumb, $image, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);

    match($type) {
        IMAGETYPE_JPEG => imagejpeg($thumb, $dest, 85),
        IMAGETYPE_PNG  => imagepng($thumb, $dest, 6),
        IMAGETYPE_WEBP => imagewebp($thumb, $dest, 85),
        IMAGETYPE_GIF  => imagegif($thumb, $dest),
        default        => null,
    };

    imagedestroy($image);
    imagedestroy($thumb);
}

/* ============================================================
   PROJECT ACTIONS
   ============================================================ */
function createProject($data) {
    $title    = sanitize($data['title'] ?? '');
    $category = sanitize($data['category'] ?? '');
    if (!$title || !$category) jsonResponse(['success'=>false,'message'=>'Title and category required.']);

    $slug     = generateSlug($title, $data['slug'] ?? '');
    $desc     = sanitize($data['description'] ?? '');
    $soft     = sanitize($data['software'] ?? 'Blender');
    $year     = intval($data['year'] ?? date('Y'));
    $client   = sanitize($data['client'] ?? '');
    $tags     = sanitize($data['tags'] ?? '');
    $status   = in_array($data['status'] ?? '', ['published','draft']) ? $data['status'] : 'published';
    $featured = intval($data['featured'] ?? 0);

    try {
        $db   = getDB();
        $stmt = $db->prepare("
            INSERT INTO projects (title,slug,category,description,software,year,client,tags,status,featured)
            VALUES (?,?,?,?,?,?,?,?,?,?)
        ");
        $stmt->execute([$title,$slug,$category,$desc,$soft,$year,$client,$tags,$status,$featured]);
        $id = (int) $db->lastInsertId();

        $imgErrors = handleImageUploads($id);

        // Handle 3D model upload
        $modelErrors = [];
        try {
            $modelFile = handleModelUpload($id);
            if ($modelFile) {
                $db->prepare("UPDATE projects SET model_3d=? WHERE id=?")
                   ->execute([$modelFile, $id]);
            }
        } catch (RuntimeException $e) {
            $modelErrors[] = $e->getMessage();
        }

        $db->prepare("INSERT INTO activity_log (action,details) VALUES (?,?)")
           ->execute(['Project Created', '#'.$id.': '.$title]);

        $response = ['success'=>true, 'id'=>$id, 'slug'=>$slug, 'message'=>'Project created!'];
        $warnings = array_merge($imgErrors, $modelErrors);
        if ($warnings) $response['image_warnings'] = $warnings;
        jsonResponse($response);

    } catch (Exception $e) {
        jsonResponse(['success'=>false, 'message'=>'DB Error: '.$e->getMessage()]);
    }
}

function updateProject($data) {
    $id = intval($data['id'] ?? 0);
    if (!$id) jsonResponse(['success'=>false,'message'=>'Invalid project ID.']);

    $title    = sanitize($data['title'] ?? '');
    $category = sanitize($data['category'] ?? '');
    if (!$title || !$category) jsonResponse(['success'=>false,'message'=>'Title and category required.']);

    $slug = generateSlug($title, $data['slug'] ?? '', $id);

    try {
        $db   = getDB();

        // Fetch the existing model filename so we can delete it if replaced
        $existingModel = $db->prepare("SELECT model_3d FROM projects WHERE id=?");
        $existingModel->execute([$id]);
        $oldModelFile = $existingModel->fetchColumn() ?: null;

        $stmt = $db->prepare("
            UPDATE projects SET title=?,slug=?,category=?,description=?,software=?,
            year=?,client=?,tags=?,status=?,featured=?,sort_order=? WHERE id=?
        ");
        $stmt->execute([
            $title, $slug, $category,
            sanitize($data['description'] ?? ''),
            sanitize($data['software'] ?? 'Blender'),
            intval($data['year'] ?? date('Y')),
            sanitize($data['client'] ?? ''),
            sanitize($data['tags'] ?? ''),
            in_array($data['status'] ?? '', ['published','draft']) ? $data['status'] : 'published',
            intval($data['featured'] ?? 0),
            intval($data['sort_order'] ?? 0),
            $id
        ]);

        $imgErrors = handleImageUploads($id);

        // Handle 3D model upload — pass old filename so it gets deleted on replace
        $modelErrors = [];
        try {
            $modelFile = handleModelUpload($id, $oldModelFile);
            if ($modelFile) {
                $db->prepare("UPDATE projects SET model_3d=? WHERE id=?")
                   ->execute([$modelFile, $id]);
            }
        } catch (RuntimeException $e) {
            $modelErrors[] = $e->getMessage();
        }

        $db->prepare("INSERT INTO activity_log (action,details) VALUES (?,?)")
           ->execute(['Project Updated', '#'.$id.': '.$title]);

        $response = ['success'=>true, 'id'=>$id, 'slug'=>$slug, 'message'=>'Project updated!'];
        $warnings = array_merge($imgErrors, $modelErrors);
        if ($warnings) $response['image_warnings'] = $warnings;
        jsonResponse($response);

    } catch (Exception $e) {
        jsonResponse(['success'=>false, 'message'=>'DB Error: '.$e->getMessage()]);
    }
}

function deleteProject($data) {
    $id = intval($data['id'] ?? 0);
    if (!$id) jsonResponse(['success'=>false,'message'=>'Invalid ID.']);
    try {
        $db   = getDB();
        $imgs = $db->prepare("SELECT filename FROM project_images WHERE project_id=?");
        $imgs->execute([$id]);
        $base = getUploadPath();
        foreach ($imgs->fetchAll() as $img) {
            @unlink($base . 'projects/'   . $img['filename']);
            @unlink($base . 'thumbnails/' . $img['filename']);
        }
        // Delete associated 3D model file
        $modelRow = $db->prepare("SELECT model_3d FROM projects WHERE id=?");
        $modelRow->execute([$id]);
        $modelFile = $modelRow->fetchColumn();
        if ($modelFile) {
            @unlink($base . 'models/' . $modelFile);
        }
        $db->prepare("DELETE FROM projects WHERE id=?")->execute([$id]);
        $db->prepare("INSERT INTO activity_log (action,details) VALUES (?,?)")
           ->execute(['Project Deleted', 'ID #'.$id]);
        jsonResponse(['success'=>true, 'message'=>'Project deleted.']);
    } catch (Exception $e) {
        jsonResponse(['success'=>false, 'message'=>'DB Error: '.$e->getMessage()]);
    }
}

function deleteImage($data) {
    $id        = intval($data['id'] ?? 0);
    $projectId = intval($data['project_id'] ?? 0);
    if (!$id) jsonResponse(['success'=>false,'message'=>'Invalid image ID.']);
    try {
        $db  = getDB();
        $row = $db->prepare("SELECT filename, is_primary FROM project_images WHERE id=? AND project_id=?");
        $row->execute([$id, $projectId]);
        $img = $row->fetch();
        if (!$img) jsonResponse(['success'=>false,'message'=>'Image not found.']);

        $base = getUploadPath();
        @unlink($base . 'projects/'   . $img['filename']);
        @unlink($base . 'thumbnails/' . $img['filename']);

        $db->prepare("DELETE FROM project_images WHERE id=?")->execute([$id]);

        if ($img['is_primary']) {
            $next = $db->prepare("SELECT id, filename FROM project_images WHERE project_id=? ORDER BY sort_order LIMIT 1");
            $next->execute([$projectId]);
            $nextImg = $next->fetch();
            if ($nextImg) {
                $db->prepare("UPDATE project_images SET is_primary=1 WHERE id=?")->execute([$nextImg['id']]);
                $db->prepare("UPDATE projects SET thumbnail=? WHERE id=?")->execute([$nextImg['filename'], $projectId]);
            } else {
                $db->prepare("UPDATE projects SET thumbnail=NULL WHERE id=?")->execute([$projectId]);
            }
        }
        jsonResponse(['success'=>true]);
    } catch (Exception $e) {
        jsonResponse(['success'=>false,'message'=>$e->getMessage()]);
    }
}

function setPrimaryImage($data) {
    $id        = intval($data['id'] ?? 0);
    $projectId = intval($data['project_id'] ?? 0);
    if (!$id || !$projectId) jsonResponse(['success'=>false,'message'=>'Invalid IDs.']);
    try {
        $db = getDB();
        $db->prepare("UPDATE project_images SET is_primary=0 WHERE project_id=?")->execute([$projectId]);
        $db->prepare("UPDATE project_images SET is_primary=1 WHERE id=? AND project_id=?")->execute([$id, $projectId]);
        $row = $db->prepare("SELECT filename FROM project_images WHERE id=?");
        $row->execute([$id]);
        $filename = $row->fetchColumn();
        if ($filename) {
            $db->prepare("UPDATE projects SET thumbnail=? WHERE id=?")->execute([$filename, $projectId]);
        }
        jsonResponse(['success'=>true]);
    } catch (Exception $e) {
        jsonResponse(['success'=>false,'message'=>$e->getMessage()]);
    }
}

/* ============================================================
   CATEGORY ACTIONS
   ============================================================ */
function createCategory($data) {
    $name  = sanitize($data['name'] ?? '');
    $slug  = sanitize($data['slug'] ?? strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-')));
    $icon  = sanitize($data['icon'] ?? '');
    $order = intval($data['sort_order'] ?? 0);
    if (!$name) jsonResponse(['success'=>false,'message'=>'Name required.']);
    try {
        $db = getDB();
        $db->prepare("INSERT INTO categories (name,slug,icon,sort_order) VALUES (?,?,?,?)")
           ->execute([$name, $slug, $icon, $order]);
        jsonResponse(['success'=>true,'message'=>'Category created.']);
    } catch (Exception $e) { jsonResponse(['success'=>false,'message'=>'DB Error: '.$e->getMessage()]); }
}

function updateCategory($data) {
    $id    = intval($data['id'] ?? 0);
    $name  = sanitize($data['name'] ?? '');
    $slug  = sanitize($data['slug'] ?? '');
    $icon  = sanitize($data['icon'] ?? '');
    $order = intval($data['sort_order'] ?? 0);
    if (!$id || !$name) jsonResponse(['success'=>false,'message'=>'ID and name required.']);
    try {
        getDB()->prepare("UPDATE categories SET name=?,slug=?,icon=?,sort_order=? WHERE id=?")
               ->execute([$name, $slug, $icon, $order, $id]);
        jsonResponse(['success'=>true,'message'=>'Category updated.']);
    } catch (Exception $e) { jsonResponse(['success'=>false,'message'=>'DB Error: '.$e->getMessage()]); }
}

function deleteCategory_($data) {
    $id = intval($data['id'] ?? 0);
    if (!$id) jsonResponse(['success'=>false,'message'=>'Invalid ID.']);
    try {
        getDB()->prepare("DELETE FROM categories WHERE id=?")->execute([$id]);
        jsonResponse(['success'=>true]);
    } catch (Exception $e) { jsonResponse(['success'=>false,'message'=>$e->getMessage()]); }
}

/* ============================================================
   OTHER ACTIONS
   ============================================================ */
function toggleFeatured($data) {
    $id  = intval($data['id'] ?? 0);
    $val = intval($data['featured'] ?? 0);
    try {
        getDB()->prepare("UPDATE projects SET featured=? WHERE id=?")->execute([$val, $id]);
        jsonResponse(['success'=>true,'featured'=>$val]);
    } catch (Exception $e) { jsonResponse(['success'=>false,'message'=>$e->getMessage()]); }
}

function toggleStatus($data) {
    $id     = intval($data['id'] ?? 0);
    $status = in_array($data['status'] ?? '', ['published','draft']) ? $data['status'] : 'published';
    try {
        getDB()->prepare("UPDATE projects SET status=? WHERE id=?")->execute([$status, $id]);
        jsonResponse(['success'=>true,'status'=>$status]);
    } catch (Exception $e) { jsonResponse(['success'=>false,'message'=>$e->getMessage()]); }
}

function deleteMessage($data) {
    $id = intval($data['id'] ?? 0);
    try {
        getDB()->prepare("DELETE FROM messages WHERE id=?")->execute([$id]);
        jsonResponse(['success'=>true]);
    } catch (Exception $e) { jsonResponse(['success'=>false,'message'=>$e->getMessage()]); }
}

function markRead($data) {
    $id  = intval($data['id'] ?? 0);
    $val = intval($data['is_read'] ?? 1);
    try {
        getDB()->prepare("UPDATE messages SET is_read=? WHERE id=?")->execute([$val, $id]);
        jsonResponse(['success'=>true]);
    } catch (Exception $e) { jsonResponse(['success'=>false,'message'=>$e->getMessage()]); }
}

function toggleStar($data) {
    $id = intval($data['id'] ?? 0);
    try {
        getDB()->prepare("UPDATE messages SET is_starred = NOT is_starred WHERE id=?")->execute([$id]);
        jsonResponse(['success'=>true]);
    } catch (Exception $e) { jsonResponse(['success'=>false,'message'=>$e->getMessage()]); }
}

function saveSettings($data) {
    $allowed = [
        'site_title','site_tagline','about_text','email','phone','location',
        'instagram','artstation','linkedin','youtube',
        'hero_headline','hero_subtext','years_experience','projects_completed','clients_served'
    ];
    try {
        $db = getDB();
        foreach ($allowed as $key) {
            if (isset($data[$key])) {
                $db->prepare("INSERT INTO settings (key_name,value) VALUES (?,?) ON DUPLICATE KEY UPDATE value=?")
                   ->execute([$key, sanitize($data[$key]), sanitize($data[$key])]);
            }
        }
        jsonResponse(['success'=>true,'message'=>'Settings saved!']);
    } catch (Exception $e) { jsonResponse(['success'=>false,'message'=>$e->getMessage()]); }
}

function deleteModel3d($data) {
    $id = intval($data['id'] ?? 0);
    if (!$id) jsonResponse(['success' => false, 'message' => 'Invalid project ID.']);
    try {
        $db  = getDB();
        $row = $db->prepare("SELECT model_3d FROM projects WHERE id=?");
        $row->execute([$id]);
        $modelFile = $row->fetchColumn();
        if ($modelFile) {
            $path = getUploadPath() . 'models/' . $modelFile;
            if (is_file($path)) @unlink($path);
        }
        $db->prepare("UPDATE projects SET model_3d=NULL WHERE id=?")->execute([$id]);
        jsonResponse(['success' => true, 'message' => '3D model removed.']);
    } catch (Exception $e) {
        jsonResponse(['success' => false, 'message' => $e->getMessage()]);
    }
}

function generateSlug($title, $customSlug = '', $excludeId = 0) {
    $slug = $customSlug ?: strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    $db   = getDB();
    $base = $slug;
    $i    = 1;
    while (true) {
        $stmt = $db->prepare("SELECT id FROM projects WHERE slug=? AND id!=?");
        $stmt->execute([$slug, $excludeId]);
        if (!$stmt->fetch()) break;
        $slug = $base . '-' . $i++;
    }
    return $slug;
}
?>