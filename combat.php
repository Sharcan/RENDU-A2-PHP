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
## POUVOIR SELECTIONER UN PERSONNE DANS LE PREMIER SELECTEUR

$req = $pdo->query('SELECT * FROM personnages');

## ETAPE 2
## POUVOIR SELECTIONER UN PERSONNE DANS LE DEUXIEME SELECTEUR

## ETAPE 3
## LORSQUE LON APPPUIE SUR LE BOUTON FIGHT, RETIRER LES PV DE CHAQUE PERSONNAGE PAR RAPPORT A LATK DU PERSONNAGE QUIL COMBAT

if(!empty($_POST) && isset($_POST)){
    // dd($_POST);

    $idPerso1 = $_POST['Perso1'];
    $idPerso2 = $_POST['Perso2'];

    $dbPerso1 = getPerso($idPerso1, $pdo);
    $dbPerso2 = getPerso($idPerso2, $pdo);

    // dd($dbPerso1, $dbPerso2);

    $pvPerso1 = $dbPerso1 -> pv - $dbPerso2 -> atk;
    $pvPerso2 = $dbPerso2 -> pv - $dbPerso1 -> atk;

    $upPerso1 = updatePvPerso($idPerso1, $pvPerso1, $pdo);
    $upPerso2 = updatePvPerso($idPerso2, $pvPerso2, $pdo);

    // msgInfo($dbPerso1 -> name, $dbPerso2 -> atk);
    // msgInfo($dbPerso2 -> name, $dbPerso1 -> atk);

}

function getPerso($id, $pdo)
{
    $infoPerso = $pdo->prepare("SELECT * FROM personnages WHERE id = :id");
    $infoPerso->execute(['id' => $id]);
    
    return $infoPerso->fetch(PDO::FETCH_OBJ);
}

function updatePvPerso($id, $pv, $pdo)
{
    $infoPerso = $pdo->prepare("UPDATE personnages SET pv = :newPv WHERE id = :id");
    $infoPerso->execute(["newPv" => $pv, "id" => $id]);
    $result = $infoPerso->fetch(PDO::FETCH_OBJ);
    
    return getPerso($id, $pdo);
}

## ETAPE 4

## UNE FOIS LE COMBAT LANCER (QUAND ON APPPUIE SUR LE BTN FIGHT) AFFICHER en dessous du formulaire
# pour le premier perso PERSONNAGE X (name) A PERDU X PV (l'atk du personnage d'en face)
# pour le second persoPERSONNAGE X (name) A PERDU X PV (l'atk du personnage d'en face)

## ETAPE 5

## N'AFFICHER DANS LES SELECTEUR QUE LES PERSONNAGES QUI ONT PLUS DE 10 PV


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
<h1>Combats</h1>
<div class="w-100 mt-5">

    <form action="" method="POST">
        <div class="form-group">
        <?php $listePerso1 = $req->fetchAll(PDO::FETCH_ASSOC); ?>
            <select name="Perso1" id="">
                <option value="" selected disabled>---Selectionne le personnage 1---</option>
                <?php
                    foreach($listePerso1 as $persoName){ 
                        if($persoName['pv']>=10){                  
                ?>
                        <option value="<?= $persoName['id']?>"><?= $persoName['name']?></option>
                <?php }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <select name="Perso2" id="">
            <option value="" selected disabled>---Selectionne le personnage 2---</option>
            <?php
                    foreach($listePerso1 as $persoName){ 
                        if($persoName['pv']>=10){                  
                ?>
                        <option value="<?= $persoName['id']?>"><?= $persoName['name']?></option>
                <?php }
                }
                ?>
            </select>
        </div>

        <button class="btn">Fight</button>
    </form>
     <?php
    
    // function msgInfo($name, $atk){
    //     echo $name." a perdu ".$atk." PV <br>";
    // }
    
    ?>
</div>

</body>
</html>
<?php

if(!empty($_POST) && isset($_POST)){
    echo $dbPerso1 -> name." a perdu ".$dbPerso2 -> atk." PV <br>";
    echo $dbPerso2 -> name." a perdu ".$dbPerso1 -> atk." PV <br>";
}