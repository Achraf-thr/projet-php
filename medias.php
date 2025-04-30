<?php
session_start();
require_once '../includes/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Fonction pour uploader un fichier
function uploadFile($file, $targetDir) {
    $targetFile = $targetDir . basename($file["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Vérifier si le fichier est une image
    if (isset($_POST["submit"])) {
        $check = getimagesize($file["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }

    // Vérifier la taille du fichier
    if ($file["size"] > 500000) {
        $uploadOk = 0;
    }

    // Autoriser certains formats de fichiers
    if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif") {
        $uploadOk = 0;
    }

    // Vérifier si $uploadOk est défini à 0 par une erreur
    if ($uploadOk == 0) {
        return false;
    } else {
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return true;
        } else {
            return false;
        }
    }
}

// Uploader une image
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $uploadSuccess = uploadFile($_FILES['image'], '../uploads/images/');
}

// Uploader un fichier audio
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['audio'])) {
    $uploadSuccess = uploadFile($_FILES['audio'], '../uploads/audio/');
}

// Uploader une vidéo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['video'])) {
    $uploadSuccess = uploadFile($_FILES['video'], '../uploads/videos/');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Upload de Médias</title>
</head>
<body>
    <h1>Upload de Médias</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="image">Uploader une image:</label>
        <input type="file" name="image" id="image">
        <button type="submit">Uploader</button>
    </form>
    <form method="POST" enctype="multipart/form-data">
        <label for="audio">Uploader un fichier audio:</label>
        <input type="file" name="audio" id="audio">
        <button type="submit">Uploader</button>
    </form>
    <form method="POST" enctype="multipart/form-data">
        <label for="video">Uploader une vidéo:</label>
        <input type="file" name="video" id="video">
        <button type="submit">Uploader</button>
    </form>
    <?php if (isset($uploadSuccess) && $uploadSuccess): ?>
        <p>Fichier uploadé avec succès!</p>
    <?php elseif (isset($uploadSuccess) && !$uploadSuccess): ?>
        <p>Erreur lors de l'upload du fichier.</p>
    <?php endif; ?>
</body>
</html>
