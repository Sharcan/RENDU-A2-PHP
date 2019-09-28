<?php
require __DIR__ . "/vendor/autoload.php";

## ETAPE 0
## CONNECTEZ VOUS A VOTRE BASE DE DONNEE

try
{
    $pdo = new PDO('mysql:host=localhost;dbname=semaine_php;charset=utf8', 'root', '');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

## ETAPE 1
## RECUPERER TOUT LES PERSONNAGES CONTENU DANS LA TABLE personnages

$getAllPerso = $pdo->query('SELECT * FROM personnages');

## ETAPE 2
## LES AFFICHERS DANS LE HTML
## AFFICHER SON NOM, SON ATK, SES PV, SES STARS


## ETAPE 3
## DANS CHAQUE PERSONNAGE JE VEUX POUVOIR APPUYER SUR UN BUTTON OU IL EST ECRIT "STARS"
## LORSQUE L'ON APPUIE SUR LE BOUTTON "STARS"
## ON SOUMET UN FORMULAIRE QUI METS A JOURS LE PERSONNAGE CORRESPONDANT (CELUI SUR LEQUEL ON A CLIQUER) EN INCREMENTANT LA COLUMN STARS DU PERSONNAGE DANS LA BASE DE DONNEE

if(!empty($_POST) && isset($_POST)){

    $idJoueur = $_POST['id_perso'];

    $dbPerso = getPerso($idJoueur, $pdo);

    $starsPerso = $dbPerso['stars']+1;

    updatePerso($idJoueur, $starsPerso, $pdo);

    msgInfo($dbPerso['name']);
    
}


function getPerso($id, $pdo){
    
    $req = $pdo->prepare('SELECT * FROM personnages WHERE id = :id');
    $req-> execute([
        'id' => $id
    ]);

    return $req->fetch(PDO::FETCH_ASSOC);
}


function updatePerso($id, $stars, $pdo){
    $req = $pdo->prepare('UPDATE personnages SET stars = :stars WHERE id = :id');
    $req->execute([
        "stars" => $stars,
        "id" => $id
    ]);
}
#######################
## ETAPE 4
# AFFICHER LE MSG "PERSONNAGE ($name) A GAGNER UNE ETOILES"

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rendu Php</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<nav class="nav mb-3">
    <a href="./rendu.php" class="nav-link">Accueil</a>
    <a href="./personnage.php" class="nav-link">Mes Personnages</a>
    <a href="./combat.php" class="nav-link">Combats</a>
</nav>
<h1>Mes personnages</h1>
<div class="w-100 mt-5">
    <?php
        $listePerso = $getAllPerso->fetchAll(PDO::FETCH_ASSOC);
        dd($listePerso);
        foreach($listePerso as $item){?>
        
        <form action="" method="post">
            <p>
                <?php
                $idPerso = $item['id'];
                $nbStars = $item['stars'];

                echo "<input type=\"number\" name=\"id_perso\" value=\"$idPerso\" hidden>";

                echo  'Personnage : '.$item['name'].'<br>';
                echo  'Attaque : '.$item['atk'].'<br>';
                echo  'Point de vie : '.$item['pv'].'<br>';
                echo  'Personnage : '.$item['name'].'<br>';
                echo  'Stars : '.$nbStars;
                ?>
            </p>
            <button type="submit">STARS</button><br><br>
        </form>

    <?php 

    }
    function msgInfo($name){
        echo "<p>PERSONNAGE ".$name." a gagné une étoile</p>";
    }   
    ?>

</div>

</body>
</html>
