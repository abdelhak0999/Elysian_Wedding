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

// Prevent deleting yourself
if ($user_id == $_SESSION['user_id']) {
    $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte.";
    header("Location: ../dashboard.php");
    exit();
}

// Delete user
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "L'utilisateur a été supprimé avec succès.";
} else {
    $_SESSION['error'] = "Une erreur est survenue lors de la suppression de l'utilisateur.";
}

header("Location: ../dashboard.php");
exit();
