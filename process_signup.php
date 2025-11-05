<?php
    include "connect.php";


    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone_number = $_POST['phone_number'];
        $client_email = $_POST['client_email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
        // Validation des données
        if (empty($first_name) || empty($last_name) || empty($phone_number) || empty($client_email) || empty($_POST['password'])) {
            throw new Exception("Tous les champs sont obligatoires.");
        }
    
        if (!filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'adresse e-mail n'est pas valide.");
        }
    
        // Vérification de la présence d'un compte avec l'e-mail fourni
        $sql = "SELECT * FROM clients WHERE client_email = :client_email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':client_email', $client_email, PDO::PARAM_STR);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            throw new Exception("Un compte avec cette adresse e-mail existe déjà.");
        }
    
        $sql = "INSERT INTO clients (first_name, last_name, phone_number, client_email, password) VALUES (:first_name, :last_name, :phone_number, :client_email, :password)";
        $stmt = $pdo->prepare($sql);
    
        $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
        $stmt->bindParam(':client_email', $client_email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    
        $stmt->execute();
    
        header("Location: index.php?success=1");
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    } catch (Exception $e) {
        header("Location: register.php?error=" . urlencode($e->getMessage()));
    }


    