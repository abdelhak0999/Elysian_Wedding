<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isAdmin($conn, $_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Check if user ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../dashboard.php");
    exit();
}

$user_id = $_GET['id'];
$user = getUserById($conn, $user_id);

// Check if user exists
if (!$user) {
    header("Location: ../dashboard.php");
    exit();
}

// Handle form submission
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    if (empty($firstname) || empty($lastname) || empty($email)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // Check if email already exists (excluding current user)
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Cet email est déjà associé à un autre compte.";
        } else {
            // Update user
            if (empty($password)) {
                // Update without changing password
                $stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, role = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $firstname, $lastname, $email, $role, $user_id);
            } else {
                // Update with new password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, password = ?, role = ? WHERE id = ?");
                $stmt->bind_param("sssssi", $firstname, $lastname, $email, $hashed_password, $role, $user_id);
            }
            
            if ($stmt->execute()) {
                $success = "Les informations de l'utilisateur ont été mises à jour avec succès.";
                // Refresh user data
                $user = getUserById($conn, $user_id);
            } else {
                $error = "Une erreur est survenue lors de la mise à jour de l'utilisateur.";
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
  <title>Modifier un utilisateur - Planification de Mariage</title>
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
        <h2 class="login-title">Modifier l'utilisateur</h2>
        <p class="login-description">Modifier les informations de l'utilisateur</p>

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
              <input type="text" id="firstname" name="firstname" class="form-input" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
            </div>

            <div class="form-group">
              <label for="lastname" class="form-label"><i class="fas fa-user"></i> Nom</label>
              <input type="text" id="lastname" name="lastname" class="form-input" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
            </div>
          </div>

          <div class="form-group">
            <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($user['email']); ?>" required>
          </div>

          <div class="form-group">
            <label for="password" class="form-label"><i class="fas fa-lock"></i> Mot de passe</label>
            <input type="password" id="password" name="password" class="form-input" placeholder="Laisser vide pour ne pas modifier">
            <small class="form-help">Laissez ce champ vide si vous ne souhaitez pas modifier le mot de passe.</small>
          </div>

          <div class="form-group">
            <label for="role" class="form-label"><i class="fas fa-user-tag"></i> Rôle</label>
            <select id="role" name="role" class="form-input">
              <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>Utilisateur</option>
              <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Administrateur</option>
            </select>
          </div>

          <div class="form-buttons">
            <a href="../dashboard.php" class="button button-secondary">Annuler</a>
            <button type="submit" name="edit_user" class="login-button">
              <i class="fas fa-save"></i>
              Enregistrer les modifications
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
