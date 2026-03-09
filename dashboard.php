<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get user data
$user_id = $_SESSION['user_id'];
$user = getUserById($conn, $user_id);

// Check if user exists
if (!$user) {
    // User not found, destroy session and redirect to login
    session_destroy();
    header("Location: index.php");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de bord - Planification de Mariage</title>
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
      <section id="dashboard" class="dashboard glass">
        <div class="dashboard-header">
          <h2 class="dashboard-title">Tableau de bord</h2>
          <div class="user-info" id="user-info-dropdown">
            <div class="user-avatar"><?php echo substr($user['firstname'], 0, 1); ?></div>
            <span class="user-name"><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></span>
            <span id="role-indicator" class="role-indicator role-<?php echo $user['role']; ?>">
              <?php echo $user['role'] === 'admin' ? 'Administrateur' : 'Utilisateur'; ?>
            </span>
            <i class="fas fa-chevron-down" style="margin-left: 10px; font-size: 12px;"></i>
            
            <div class="user-dropdown">
              
              <div class="dropdown-divider"></div>
              <a href="index.html" class="dropdown-item" id="logout-button">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
              </a>
            </div>
          </div>
        </div>
        
        <!-- Dashboard tabs -->
        <div class="dashboard-tabs">
          <div class="dashboard-tab active" data-tab="home">
            <i class="fas fa-home"></i> Accueil
          </div>
          <?php if ($user['role'] === 'admin'): ?>
          <div class="dashboard-tab" data-tab="admin">
            <i class="fas fa-users-cog"></i> Administration
          </div>
          <?php endif; ?>
        </div>

        <!-- User Content -->
        <div id="tab-home" class="tab-content active">
          <div class="dashboard-categories">
            <!-- Category 1: Tenues et Accessoires -->
            <div class="category-card">
              <div class="category-header">
                <div class="category-title">
                  <i class="fas fa-tshirt category-icon"></i>
                  Tenues et Accessoires
                </div>
              </div>
              <div class="category-body">
                <div class="category-items">
                  <a href="costumes.html" class="category-item">
                    <div class="item-icon">
                      <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="item-details">
                      <div class="item-title">Costumes</div>
                      <div class="item-description">Costumes pour le marié et témoins</div>
                    </div>
                  </a>
                  <a href="robes.html" class="category-item">
                    <div class="item-icon">
                      <i class="fas fa-female"></i>
                    </div>
                    <div class="item-details">
                      <div class="item-title">Robes</div>
                      <div class="item-description">Robes de mariée et demoiselles d'honneur</div>
                    </div>
                  </a>
                  <a href="trairure.html" class="category-item">
                    <div class="item-icon">
                      <i class="fas fa-gift"></i>
                    </div>
                    <div class="item-details">
                      <div class="item-title">Trairure</div>
                      <div class="item-description">Accessoires de mariage</div>
                    </div>
                  </a>
                </div>
              </div>
            </div>

            <!-- Category 2: Services -->
            <div class="category-card">
              <div class="category-header">
                <div class="category-title">
                  <i class="fas fa-concierge-bell category-icon"></i>
                  Services
                </div>
              </div>
              <div class="category-body">
                <div class="category-items">
                  <a href="DJ.html" class="category-item">
                    <div class="item-icon">
                      <i class="fas fa-music"></i>
                    </div>
                    <div class="item-details">
                      <div class="item-title">Musique</div>
                      <div class="item-description">DJ et medahat</div>
                    </div>
                  </a>
                  <a href="estheticienne.html" class="category-item">
                    <div class="item-icon">
                      <i class="fas fa-spa"></i>
                    </div>
                    <div class="item-details">
                      <div class="item-title">Esthéticienne</div>
                      <div class="item-description">Services d'esthétique et coiffure</div>
                    </div>
                  </a>
                  <a href="phototgraphe.html" class="category-item">
                    <div class="item-icon">
                      <i class="fas fa-camera"></i>
                    </div>
                    <div class="item-details">
                      <div class="item-title">Photographe</div>
                      <div class="item-description">Services photo et vidéo</div>
                    </div>
                  </a>
                </div>
              </div>
            </div>

            <!-- Category 3: Lieux et Déplacements -->
            <div class="category-card">
              <div class="category-header">
                <div class="category-title">
                  <i class="fas fa-map-marker-alt category-icon"></i>
                  Lieux et Déplacements
                </div>
              </div>
              <div class="category-body">
                <div class="category-items">
                  <a href="salles&deco.html" class="category-item">
                    <div class="item-icon">
                      <i class="fas fa-building"></i>
                    </div>
                    <div class="item-details">
                      <div class="item-title">Salles & Décoration</div>
                      <div class="item-description">Lieux et décorations</div>
                    </div>
                  </a>
                  <a href="voitures.html" class="category-item">
                    <div class="item-icon">
                      <i class="fas fa-car"></i>
                    </div>
                    <div class="item-details">
                      <div class="item-title">Voitures</div>
                      <div class="item-description">Location de voitures de mariage</div>
                    </div>
                  </a>
                  <a href="arrive.html" class="category-item">
                    <div class="item-icon">
                      <i class="fas fa-users"></i>
                    </div>
                    <div class="item-details">
                      <div class="item-title">Arrive</div>
                      <div class="item-description">Planifiez votre arrivée</div>
                    </div>
                  </a>
                </div>
              </div>
            </div>

            <!-- Category 4: Voyage et Détente -->
            <div class="category-card">
              <div class="category-header">
                <div class="category-title">
                  <i class="fas fa-plane category-icon"></i>
                  Voyage et Détente
                </div>
              </div>
              <div class="category-body">
                <div class="category-items">
                  <a href="HoneymoonIdeas.html" class="category-item">
                    <div class="item-icon">
                      <i class="fas fa-umbrella-beach"></i>
                    </div>
                    <div class="item-details">
                      <div class="item-title">Idées Lune de Miel</div>
                      <div class="item-description">Destinations et conseils pour votre lune de miel</div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons Section -->
          <div class="dashboard-actions">
            <a href="index.html" class="action-button" id="home-button">
              <div class="action-icon">
                <i class="fas fa-home"></i>
              </div>
              <div class="action-title">Accueil</div>
              <div class="action-description">Retour à la page d'accueil</div>
            </a>
            
            <a href="panier.html" class="action-button" id="panier-button">
              <div class="action-icon">
                <i class="fas fa-shopping-cart"></i>
              </div>
              <div class="action-title">Panier</div>
              <div class="action-description">Consultez votre panier d'achats</div>
            </a>
            
            <a href="calendrier.html" class="action-button" id="calendar-button">
              <div class="action-icon">
                <i class="fas fa-calendar-alt"></i>
              </div>
              <div class="action-title">Calendrier</div>
              <div class="action-description">Planifiez vos événements</div>
            </a>
          </div>
        </div>

        <!-- Admin Content -->
        <?php if ($user['role'] === 'admin'): ?>
        <div id="tab-admin" class="tab-content">
          <div class="admin-section">
            <div class="section-header">
              <h3 class="section-title">
                <i class="fas fa-users-cog"></i>
                Gestion des Utilisateurs
              </h3>
              <span class="section-badge admin-badge">Accès Administrateur</span>
            </div>
            
            <div class="admin-tools">
              <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="user-search" class="search-input" placeholder="Rechercher un utilisateur...">
              </div>
              
              <select id="role-filter" class="filter-select">
                <option value="all">Tous les rôles</option>
                <option value="admin">Administrateurs</option>
                <option value="user">Utilisateurs</option>
              </select>
              
              <a href="admin/add-user.php" class="add-user-btn">
                <i class="fas fa-user-plus"></i>
                Ajouter un utilisateur
              </a>
            </div>
            
            <div class="table-container">
              <table class="user-management-table" id="user-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="user-table-body">
                  <?php
                  // Get all users
                  $users = getAllUsers($conn);
                  foreach ($users as $user_item):
                    // Format date
                    $created_date = new DateTime($user_item['created_at']);
                    $formatted_date = $created_date->format('d/m/Y');
                  ?>
                  <tr>
                    <td><?php echo $user_item['id']; ?></td>
                    <td><?php echo htmlspecialchars($user_item['firstname'] . ' ' . $user_item['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($user_item['email']); ?></td>
                    <td>
                      <span class="user-role role-<?php echo $user_item['role']; ?>">
                        <?php echo $user_item['role'] === 'admin' ? 'Administrateur' : 'Utilisateur'; ?>
                      </span>
                    </td>
                    <td><?php echo $formatted_date; ?></td>
                    <td class="user-actions">
                      <a href="admin/edit-user.php?id=<?php echo $user_item['id']; ?>" class="action-btn edit-btn" title="Modifier">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="admin/delete-user.php?id=<?php echo $user_item['id']; ?>" class="action-btn delete-btn" title="Supprimer" 
                         onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                        <i class="fas fa-trash-alt"></i>
                      </a>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <?php endif; ?>
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
