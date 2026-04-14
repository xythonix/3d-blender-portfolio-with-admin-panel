<?php
require_once '../includes/config.php';
requireAdmin();
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'upload_image':
        uploadProjectImage();
        break;
    case 'delete_image':
        deleteProjectImage();
        break;
    case 'set_primary':
        setPrimaryImage();
        break;
    case 'reorder_images':
        reorderImages();
        break;
    default:
        jsonResponse(['success' => false, 'message' => 'Unknown action.']);
}

function uploadProjectImage() {
    $projectId = intval($_POST['project_id'] ?? 0);
    $viewAngle = sanitize($_POST['view_angle'] ?? '');
    $caption   = sanitize($_POST['caption'] ?? '');
    $isPrimary = intval($_POST['is_primary'] ?? 0);

    if (!$projectId) {
        jsonResponse(['success' => false, 'message' => 'Invalid project ID.']);
    }
    if (empty($_FILES['image'])) {
        jsonResponse(['success' => false, 'message' => 'No image uploaded.']);
    }

    $file = $_FILES['image'];
    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed)) {
        jsonResponse(['success' => false, 'message' => 'Only JPG, PNG, WebP, GIF allowed.']);
    }
    if ($file['size'] > 15 * 1024 * 1024) {
        jsonResponse(['success' => false, 'message' => 'File too large. Max 15MB.']);
    }

    $ext = match($mime) {
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
        default      => 'jpg',
    };

    $filename = uniqid('img_', true) . '.' . $ext;
    $dest     = UPLOAD_PATH . 'projects/' . $filename;
    $thumbDest = UPLOAD_PATH . 'thumbnails/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        jsonResponse(['success' => false, 'message' => 'Failed to save image.']);
    }

    // Create thumbnail using GD
    createThumbnail($dest, $thumbDest, 600, 400);

    // If first image or set as primary, unset others
    try {
        $db = getDB();
        $existing = $db->prepare("SELECT COUNT(*) FROM project_images WHERE project_id=?")->execute([$projectId]);
        if ($isPrimary) {
            $db->prepare("UPDATE project_images SET is_primary=0 WHERE project_id=?")->execute([$projectId]);
        }
        // If it's the first image, make it primary
        $cnt = $db->prepare("SELECT COUNT(*) FROM project_images WHERE project_id=?");
        $cnt->execute([$projectId]);
        $isFirst = ($cnt->fetchColumn() == 0);

        $stmt = $db->prepare("INSERT INTO project_images (project_id, filename, original_name, caption, view_angle, is_primary, sort_order) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$projectId, $filename, basename($file['name']), $caption, $viewAngle, ($isPrimary || $isFirst) ? 1 : 0, time()]);

        // Update project thumbnail if primary
        if ($isPrimary || $isFirst) {
            $db->prepare("UPDATE projects SET thumbnail=? WHERE id=?")->execute([$filename, $projectId]);
        }

        jsonResponse([
            'success'   => true,
            'filename'  => $filename,
            'url'       => UPLOAD_URL . 'projects/' . $filename,
            'thumb_url' => UPLOAD_URL . 'thumbnails/' . $filename,
            'id'        => $db->lastInsertId(),
            'message'   => 'Image uploaded successfully!'
        ]);
    } catch (Exception $e) {
        @unlink($dest);
        jsonResponse(['success' => false, 'message' => 'DB error: ' . $e->getMessage()]);
    }
}

function createThumbnail($src, $dest, $maxW, $maxH) {
    if (!extension_loaded('gd')) {
        copy($src, $dest);
        return;
    }
    $info = getimagesize($src);
    if (!$info) { copy($src, $dest); return; }
    [$w, $h] = $info;
    $ratio = min($maxW/$w, $maxH/$h);
    if ($ratio >= 1) { copy($src, $dest); return; }
    $nw = intval($w * $ratio);
    $nh = intval($h * $ratio);
    $dst = imagecreatetruecolor($nw, $nh);
    $src_img = match($info['mime']) {
        'image/png' => imagecreatefrompng($src),
        'image/webp'=> imagecreatefromwebp($src),
        'image/gif' => imagecreatefromgif($src),
        default     => imagecreatefromjpeg($src),
    };
    if (!$src_img) { copy($src, $dest); return; }
    imagecopyresampled($dst, $src_img, 0,0,0,0, $nw,$nh,$w,$h);
    match($info['mime']) {
        'image/png'  => imagepng($dst, $dest),
        'image/webp' => imagewebp($dst, $dest),
        'image/gif'  => imagegif($dst, $dest),
        default      => imagejpeg($dst, $dest, 85),
    };
    imagedestroy($src_img);
    imagedestroy($dst);
}

function deleteProjectImage() {
    $id = intval($_POST['id'] ?? 0);
    if (!$id) jsonResponse(['success' => false, 'message' => 'Invalid ID.']);
    try {
        $db = getDB();
        $img = $db->prepare("SELECT * FROM project_images WHERE id=?")->execute([$id]);
        $img = $db->prepare("SELECT * FROM project_images WHERE id=?");
        $img->execute([$id]);
        $row = $img->fetch();
        if (!$row) jsonResponse(['success' => false, 'message' => 'Image not found.']);

        @unlink(UPLOAD_PATH . 'projects/' . $row['filename']);
        @unlink(UPLOAD_PATH . 'thumbnails/' . $row['filename']);
        $db->prepare("DELETE FROM project_images WHERE id=?")->execute([$id]);

        // If it was primary, set next one as primary
        if ($row['is_primary']) {
            $next = $db->prepare("SELECT id FROM project_images WHERE project_id=? ORDER BY sort_order LIMIT 1");
            $next->execute([$row['project_id']]);
            $nid = $next->fetchColumn();
            if ($nid) $db->prepare("UPDATE project_images SET is_primary=1 WHERE id=?")->execute([$nid]);
        }
        jsonResponse(['success' => true, 'message' => 'Image deleted.']);
    } catch (Exception $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function setPrimaryImage() {
    $id = intval($_POST['id'] ?? 0);
    $projectId = intval($_POST['project_id'] ?? 0);
    if (!$id || !$projectId) jsonResponse(['success' => false, 'message' => 'Invalid parameters.']);
    try {
        $db = getDB();
        $db->prepare("UPDATE project_images SET is_primary=0 WHERE project_id=?")->execute([$projectId]);
        $db->prepare("UPDATE project_images SET is_primary=1 WHERE id=?")->execute([$id]);
        $fname = $db->prepare("SELECT filename FROM project_images WHERE id=?");
        $fname->execute([$id]);
        $f = $fname->fetchColumn();
        $db->prepare("UPDATE projects SET thumbnail=? WHERE id=?")->execute([$f, $projectId]);
        jsonResponse(['success' => true, 'message' => 'Primary image updated.']);
    } catch (Exception $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function reorderImages() {
    $order = $_POST['order'] ?? [];
    if (!is_array($order)) jsonResponse(['success' => false, 'message' => 'Invalid data.']);
    try {
        $db = getDB();
        foreach ($order as $pos => $imgId) {
            $db->prepare("UPDATE project_images SET sort_order=? WHERE id=?")->execute([$pos, intval($imgId)]);
        }
        jsonResponse(['success' => true, 'message' => 'Order saved.']);
    } catch (Exception $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
