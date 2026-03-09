<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isAdmin($conn, $_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Handle form submission
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Cet email est déjà associé à un compte.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $firstname, $lastname, $email, $hashed_password, $role);
            
            if ($stmt->execute()) {
                $success = "L'utilisateur a été ajouté avec succès.";
            } else {
                $error = "Une erreur est survenue lors de l'ajout de l'utilisateur.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter un utilisateur - Planification de Mariage</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <header id="header">
    <div class="container header-container">
      <a href="../index.php" class="logo">Planification de Mariage</a>

      <nav class="nav-desktop">
        <a href="../index.php">Accueil</a>
        <a href="../services.php">Services</a>
        <a href="../about.php">À propos</a>
        <a href="../contact.php">Contact</a>
      </nav>

      <button id="theme-toggle" class="theme-toggle">
        <i id="theme-icon" class="fas fa-moon"></i>
      </button>
    </div>
  </header>

  <main>
    <div class="container">
      <section class="login-section glass">
        <h2 class="login-title">Ajouter un utilisateur</h2>
        <p class="login-description">Créer un nouveau compte utilisateur</p>

        <?php if (!empty($error)): ?>
          <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $error; ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
          <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $success; ?>
          </div>
        <?php endif; ?>

        <form id="user-form" class="login-form" method="POST" action="">
          <div class="form-row">
            <div class="form-group">
              <label for="firstname" class="form-label"><i class="fas fa-user"></i> Prénom</label>
              <input type="text" id="firstname" name="firstname" class="form-input" placeholder="Prénom" required>
            </div>

            <div class="form-group">
              <label for="lastname" class="form-label"><i class="fas fa-user"></i> Nom</label>
              <input type="text" id="lastname" name="lastname" class="form-input" placeholder="Nom" required>
            </div>
          </div>

          <div class="form-group">
            <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="email" name="email" class="form-input" placeholder="Adresse email" required>
          </div>

          <div class="form-group">
            <label for="password" class="form-label"><i class="fas fa-lock"></i> Mot de passe</label>
            <input type="password" id="password" name="password" class="form-input" placeholder="Mot de passe" required>
          </div>

          <div class="form-group">
            <label for="role" class="form-label"><i class="fas fa-user-tag"></i> Rôle</label>
            <select id="role" name="role" class="form-input">
              <option value="user">Utilisateur</option>
              <option value="admin">Administrateur</option>
            </select>
          </div>

          <div class="form-buttons">
            <a href="../dashboard.php" class="button button-secondary">Annuler</a>
            <button type="submit" name="add_user" class="login-button">
              <i class="fas fa-user-plus"></i>
              Ajouter l'utilisateur
            </button>
          </div>
        </form>
      </section>
    </div>
  </main>

  <footer>
    <div class="container footer-content">
      <div class="footer-links">
        <a href="../terms.php">Conditions générales</a>
        <a href="../privacy.php">Politique de confidentialité</a>
        <a href="../help.php">Aide</a>
        <a href="../contact.php">Contact</a>
      </div>
      <div class="footer-copyright">
        &copy; <span id="current-year"></span> Planification de Mariage. Tous droits réservés.
      </div>
    </div>
  </footer>

  <div id="toast-container" class="toast-container"></div>

  <script src="../assets/js/main.js"></script>
</body>
</html>
