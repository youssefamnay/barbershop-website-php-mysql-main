<!-- PHP INCLUDES -->

<?php
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - Barbershop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
            color: #343a40; /* Dark text color */
            font-family: 'Arial', sans-serif;
        }
        .barbershop-form {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        .barbershop-form h1 {
            text-align: center;
            color: #9e8a78; /* Barbershop red color */
        }
        .barbershop-form p {
            text-align: center;
            margin-bottom: 20px;
        }
        .barbershop-form label {
            font-weight: bold;
        }
        .barbershop-form button[type="submit"] {
            background-color: #9e8a78; /* Barbershop red color */
            border: none;
        }
        .barbershop-form button[type="submit"]:hover {
            background-color: #9e8a78; /* Darker shade of red on hover */
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="barbershop-form">
            <h1>Inscription - Barbershop</h1>
            <p>Vous êtes déjà un utilisateur ? <a href="login.php">Connectez-vous ici</a></p>
            <form action="process_signup.php" method="post" name="signupForm">
                <div class="mb-3">
                    <label for="first_name" class="form-label">Prénom :</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Nom :</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Numéro de téléphone :</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                </div>
                <div class="mb-3">
                    <label for="client_email" class="form-label">Email :</label>
                    <input type="email" class="form-control" id="client_email" name="client_email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe :</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </form>
        </div>
    </div>
</body>
</html>

<!-- FOOTER  -->

<?php include "Includes/templates/footer.php"; ?>
