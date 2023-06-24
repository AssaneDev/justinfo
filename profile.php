<?php 
require __DIR__.'/database/database.php';
$authDB = require __DIR__.'/database/security.php';
$currentUser = $authDB->isloggedin();
if(!$currentUser){
    header('Location:/');
}

$articleDB = require __DIR__.'/database/models/articleDB.php';
$articles = [];

$articles = $articleDB->fetchUserArticles($currentUser['id']);




?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'includes/head.php'?>
<link rel="stylesheet" href="../public/css/profile.css">
<title>Profile</title>
</head>
<body>
  
    <div class="container">
    <?php include 'includes/header.php'?>
       <div class="content">
          <h1>Mon espace</h1>
          <h2>Mes Informations</h2>
          <div class="info-container">
            <ul>
                <li>
                    <strong>Prénom :</strong>
                    <p><?= $currentUser['firstname'] ?></p>

                </li>

                <li>
                    <strong>Nom :</strong>
                    <p><?= $currentUser['lastname'] ?></p>
                    
                </li>

                <li>
                    <strong>Email :</strong>
                    <p><?= $currentUser['email'] ?></p>
                    
                </li>
                
            </ul>
          </div>
           <div class="articles-list">
              <h2>Vos Articles </h2>
            <ul>
                <?php foreach($articles as $a) :?>
                    <li>
                        <span><?=$a['title']?></span>
                        <div class="article-actions">
                           <a href="/form-article.php?id=<?=$a['id']?>" class="btn btn-primary small">Modifier</a>
                           <a href="/delete-article.php?id=<?=$a['id']?>" class="btn btn-secondary red small">Supprimer</a>
                        </div>
                    </li>
                <?php endforeach; ?>

            </ul>
           </div>

       </div>
        
    </div>
    <?php include 'includes/footer.php'?>
</body>
</html>