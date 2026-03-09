<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Handle login form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        // Attempt login
        $result = loginUser($conn, $email, $password);
        
        if ($result['success']) {
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - Planification de Mariage</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
   .header-logo {
  /* shrink it just slightly */
  width: 130px;      /* or whatever feels right */
  height: auto;

  /* keep your border if you like */
  /* border-radius: 50%;
  border: 2px solid #c29a76; */
  padding: 2px;

  /* ensure drop-shadow hugs the opaque parts */
  display: inline-block;

  /* layer a small, subtle neon glow */
  /* filter:
    drop-shadow(0 0 1px #c29a76)
    drop-shadow(0 0 2px #c29a76)
    drop-shadow(0 0 4px rgba(194,154,118,0.8))
    drop-shadow(0 0 6px rgba(194,154,118,0.6)); */
}




  </style>
</head>
<body>
  <header id="header">
    <div class="container header-container">
      <a href="index.php" class="logo">
        <img src="logo.png" alt="Planification de Mariage" class="header-logo">
      </a>

      <nav class="nav-desktop">
        <a href="index.php">Accueil</a>
        <a href="services.php">Services</a>
        <a href="about.php">À propos</a>
        <a href="contact.php">Contact</a>
      </nav>

      <button id="theme-toggle" class="theme-toggle">
        <i id="theme-icon" class="fas fa-moon"></i>
      </button>
    </div>
  </header>

  <main>
    <div class="container">
      <section id="login-section" class="login-section glass">
        <h2 class="login-title">Connexion</h2>
        <p class="login-description">Connectez-vous pour accéder à votre espace personnel</p>

        <?php if (!empty($error)): ?>
          <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $error; ?>
          </div>
        <?php endif; ?>

        <form id="login-form" class="login-form" method="POST" action="">
          <div class="form-group">
            <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="email" name="email" class="form-input" placeholder="Votre adresse email" required>
          </div>

          <div class="form-group">
            <label for="password" class="form-label"><i class="fas fa-lock"></i> Mot de passe</label>
            <input type="password" id="password" name="password" class="form-input" placeholder="Votre mot de passe" required>
          </div>

          <button type="submit" name="login" class="login-button">
            <i class="fas fa-sign-in-alt"></i>
            Se connecter
          </button>
        </form>

        <p class="register-link">
          Vous n'avez pas de compte ? <a href="register.php" id="register-link">Inscrivez-vous</a>
        </p>
      </section>
    </div>
  </main>

  <footer>
    <div class="container footer-content">
      <div class="footer-links">
        <a href="terms.php">Conditions générales</a>
        <a href="privacy.php">Politique de confidentialité</a>
        <a href="help.php">Aide</a>
        <a href="contact.php">Contact</a>
      </div>
      <div class="footer-copyright">
        &copy; <span id="current-year"></span> Planification de Mariage. Tous droits réservés.
      </div>
    </div>
  </footer>

  <div id="toast-container" class="toast-container"></div>

  <script src="assets/js/main.js"></script>
</body>
</html>
