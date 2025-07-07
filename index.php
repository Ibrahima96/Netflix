<?php
session_start();

require_once 'src/option.php';
if (!empty($_POST['email']) && !empty($_POST['password'])) {

    $email    = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password']; // pas besoin de htmlspecialchars ici

    require_once('src/connexion.php');

    // Vérification de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?error=1&message=Adresse email invalide.");
        exit();
    }

    // Vérification de l'utilisateur
    $req = $bdd->prepare("SELECT * FROM user WHERE email = ?");
    $req->execute([$email]);
    $user = $req->fetch();

    if (!$user) {
        header("Location: index.php?error=1&message=Utilisateur introuvable.");
        exit();
    }

    // Vérification du mot de passe (version ancienne hash)
    $hashedInput = "aqui" . sha1($password . "123") . "25";

    if ($hashedInput === $user['password']) {
        $_SESSION["connect"] = 1;
        $_SESSION['email'] = $user['email'];
		if (isset($_POST['auto'])) {
			
			setcookie('auth',$user['secret'],time()+364*24*3600,"/","",false,true);
		}
        header("Location: index.php?success=1");
        exit();
    } else {
        header("Location: index.php?error=1&message=Mot de passe incorrect.");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Netflix</title>
	<link rel="stylesheet" type="text/css" href="design/default.css">
	<link rel="icon" type="image/png" href="assets/favicon.png">
</head>
<body>

	<?php require_once('src/header.php'); ?>
	
	<section>
		<div id="login-body">

				<?php if(isset($_SESSION['connect'])) { ?>

					<h1>Bonjour !</h1>
					<?php
					if(isset($_GET['success'])){
						echo'<div class="alert success">Vous êtes maintenant connecté.</div>';
					} ?>
					<p>Qu'allez-vous regarder aujourd'hui ?</p>
					<small><a href="logout.php">Déconnexion</a></small>

				<?php } else { ?>
					<h1>S'identifier</h1>

					<?php if(isset($_GET['error'])) {

						if(isset($_GET['message'])) {
							echo'<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';
						}

					} ?>

					<form method="post" action="index.php">
						<input type="email" name="email" placeholder="Votre adresse email" required />
						<input type="password" name="password" placeholder="Mot de passe" required />
						<button type="submit">S'identifier</button>
						<label id="option"><input type="checkbox" name="auto" checked />Se souvenir de moi</label>
					</form>
				

					<p class="grey">Première visite sur Netflix ? <a href="inscription.php">Inscrivez-vous</a>.</p>
				<?php } ?>
		</div>
	</section>

	<?php require_once('src/footer.php'); ?>
</body>
</html>