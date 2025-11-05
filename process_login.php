<?php
include "connect.php";

session_start(); // Start the session at the beginning of the script

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $client_email = $_POST['client_email'];
    $password = $_POST['password'];

    // Validation des données
    if (empty($client_email) || empty($password)) {
        throw new Exception("Tous les champs sont obligatoires.");
    }

    // Vérification de la présence d'un compte avec l'e-mail fourni
    $sql = "SELECT * FROM clients WHERE client_email = :client_email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':client_email', $client_email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        throw new Exception("Aucun compte n'a été trouvé avec cette adresse e-mail.");
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification du mot de passe
    if (!password_verify($password, $user['password'])) {
        throw new Exception("Le mot de passe est incorrect.");
    }

    // Authentification réussie, store the user's ID in the session
    $_SESSION['user_id'] = $user['client_id'];

    // Rediriger vers la page d'accueil ou un tableau de bord
    header("Location: barber-client/");
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
} catch (Exception $e) {
    header("Location: login.php?error=" . urlencode($e->getMessage()));
}