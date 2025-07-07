<?php
session_start();
require_once 'src/option.php';
if (isset($_SESSION["connect"])) {
	header("location:index.php");
	exit();
}
if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_two'])) {
	// connexion a la bdd
	require_once 'src/connexion.php';
	// recuperation des varibles
	$email			= htmlspecialchars($_POST['email']);
	$password		= htmlspecialchars($_POST['password']);
	$passwordTwo	= htmlspecialchars($_POST['password_two']);

	// le mots de passe est-il differents ?
	if ($password != $passwordTwo) {
		header("location: inscription.php?error=1&message=Vos mots de passe ne sont pas identique !");
		exit();
	}

	// l'adresse email est-elle correct 

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("location: inscription.php?error=1&message=Votre adresse email est invalide .");
		exit();
	}

	// Adresse email est-elle un doublon 

	$req = $bdd->prepare("SELECT COUNT(*) AS numberEmail FROM user  WHERE email = ?");
	$req->execute([$email]);

	while ($resulte = $req->fetch()) {
		if ($resulte['numberEmail'] != 0) {
			header("location: inscription.php?error=1&message=cette adresse email  est déja  utiliser par un autre .");
			exit();
		}
	}

	//chiffrement du mots de passe 

	$password = "aqui" . sha1($password . "123") . "25";  //OR   $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
	// Secret
	$secret = sha1($email) . time();					// OR $secret = bin2hex(random_bytes(32)); // 64 caractères aléatoires sécurisés

	$secret = sha1($secret) . time();

	// Ajouter les utilisateur
	$req = $bdd->prepare(" INSERT INTO user (email,password,secret) VALUES (?,?,?)");
	$req->execute([$email, $password, $secret]);
	header("location:inscription.php?succes=1");
	exit();
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

	<?php require_once 'src/header.php'; ?>

	<section>
		<div id="login-body">
			<h1>S'inscrire</h1>
			<?php if (isset($_GET["error"]) && isset($_GET["message"])) : ?>
				<div class="alert error"><?= htmlspecialchars($_GET["message"]) ?></div>
			<?php elseif (isset($_GET["succes"])) : ?>
				<div class="success alert">Vous etes désormais inscript <a href="index.php">Connectez-vous</a> </div>
			<?php endif ?>
			<form method="post" action="inscription.php">
				<input type="email" name="email" placeholder="Votre adresse email" required />
				<input type="password" name="password" placeholder="Mot de passe" required />
				<input type="password" name="password_two" placeholder="Retapez votre mot de passe" required />
				<button type="submit">S'inscrire</button>
			</form>

			<p class="grey">Déjà sur Netflix ? <a href="index.php">Connectez-vous</a>.</p>
		</div>
	</section>

	<?php require_once 'src/footer.php'; ?>
</body>

</html>