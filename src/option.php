<?php
// if (isset($_COOKIE['auth']) && !isset($_SESSION['connect'])) {
//     //bdd
//     require_once 'connexion.php';
//     //variable 
//     $secret = htmlspecialchars($_COOKIE['auth']);

//     //le secret exist-il
//     $req = $bdd->prepare("SELECT COUNT(*) AS secretNumber FROM  user WHERE  secret = ?");
//     $req->execute([$secret]);
//     while ($user = $req->fetch()) {
//         if ($user["secretNumber"] == 1) {
//             //lire cequi conserne l'utilisateur
//             $information = $bdd->prepare("SELECT * FROM user WHERE secret = ?");
//             $information->execute([$secret]);
//             while ($userInfo = $information->fetch()) {
//                 $_SESSION["connect"] = 1;
//                 $_SESSION['email'] = $userInfo['email'];
//             }
//         }
//     }
// }


if (isset($_COOKIE['auth']) && !isset($_SESSION['connect'])) {
    require_once 'connexion.php';

    $secret = $_COOKIE['auth'];

    // Une seule requête suffit
    $req = $bdd->prepare("SELECT * FROM user WHERE secret = ? LIMIT 1");
    $req->execute([$secret]);
    $user = $req->fetch();

    if ($user) {
        $_SESSION['connect'] = 1;
        $_SESSION['email'] = $user['email'];
    }
}