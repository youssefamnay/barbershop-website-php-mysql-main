<?php 
session_start();

// Verify if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Page title
    $titreDePage = 'Tableau de bord';

    // Includes
    include 'connect.php';
    include 'functions/functions.php'; 
    include 'templates/header.php';
    $client_id = $_SESSION['user_id'];

    // Fetch client's current information
    $stmt = $con->prepare("SELECT first_name, last_name, phone_number, client_email, password FROM clients WHERE client_id = :client_id");
    $stmt->execute(['client_id' => $client_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle form submission
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone_number = $_POST['phone_number'];
        $client_email = $_POST['client_email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        $update_stmt = $con->prepare("UPDATE clients SET first_name = :first_name, last_name = :last_name, phone_number = :phone_number, client_email = :client_email, password = :password WHERE client_id = :client_id");
        $update_stmt->execute([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone_number' => $phone_number,
            'client_email' => $client_email,
            'password' => $password,
            'client_id' => $client_id
        ]);

        echo "<div class='alert alert-success'>Informations mises à jour avec succès !</div>";

        // Refresh the client data
        $stmt->execute(['client_id' => $client_id]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titreDePage; ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            background: #1b1e21;
            border: none;
        }
        .btn-primary:hover {
            background: #343a40;
        }
        h1 {
            color: #1b1e21;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .alert-success {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modifier mon profil</h1>
        <form action="" method="POST">
            <div class="form-group">
                <label for="first_name">Nom :</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($client['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Prenom :</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($client['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Numero de telephone :</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($client['phone_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="client_email">Adresse Mail :</label>
                <input type="email" class="form-control" id="client_email" name="client_email" value="<?php echo htmlspecialchars($client['client_email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
    // Include Footer
    include 'templates/footer.php';
} else {
    header('Location: login.php');
    exit();
}
?>
