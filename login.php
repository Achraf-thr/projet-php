<?php
session_start();

// Inclure la configuration de la base de données
require_once '../includes/config.php';

// Vérifier si la table admins existe, sinon la créer
$table_check = $conn->query("SHOW TABLES LIKE 'admins'");
if ($table_check->num_rows == 0) {
    $create_table = "CREATE TABLE admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL COMMENT 'Mot de passe crypté',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($create_table) === FALSE) {
        die("Erreur lors de la création de la table admins: " . $conn->error);
    }
    
    // Ajouter un admin par défaut (à supprimer ou modifier en production)
    $default_username = "admin";
    $default_password = password_hash("admin123", PASSWORD_DEFAULT);
    
    $insert_query = "INSERT INTO admins (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param('ss', $default_username, $default_password);
    
    if (!$stmt->execute()) {
        die("Erreur lors de la création de l'admin par défaut: " . $conn->error);
    }
}

// Traitement du formulaire
$error = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = "Tous les champs sont obligatoires";
    } else {
        $query = "SELECT id, username, password FROM admins WHERE username = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $username);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $admin = $result->fetch_assoc();
                
                if (password_verify($password, $admin['password'])) {
                    $_SESSION['admin'] = [
                        'id' => $admin['id'],
                        'username' => $admin['username']
                    ];
                    header('Location: categories.php');
                    exit;
                } else {
                    $error = "Identifiants incorrects";
                }
            } else {
                $error = "Identifiants incorrects";
            }
        } else {
            $error = "Erreur de base de données";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1rem;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: #f44336;
            text-align: center;
            margin-top: 1rem;
            padding: 0.5rem;
            background-color: #ffebee;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Connexion Admin</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Connexion</button>
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>