<?php
require __DIR__.'/database/database.php';
$authDB = require __DIR__.'/database/security.php';
 $curentUser = $authDB->isloggedin();

$articleDB = require_once __DIR__ .('/database/models/articleDB.php');


$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if (!$id) {
    header('Location: /');
} else {
  $article = $articleDB->fetch($id);
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'includes/head.php' ?>
    <link rel="stylesheet" href="../public/css/show-article.css">
    <title>Just Info</title>
</head>

<body>
    <div class="container">
        <?php include 'includes/header.php' ?>
        <div class="contents">
            <div class="article-container">
                <a href="/" class="retour">Retour A la liste des articles</a>
                <div class="article-cover-image" style="background-image: url(<?= $article['image'] ?>);"></div>
                <div class="article-title">
                    <h1><?= $article['title'] ?></h1>
                </div>
                <div class="separator"></div>
                <div class="article-content"><?= $article['content'] ?></div>
                <div class="article-author">
                   <p><?= $article['firstname'].' '.$article['lastname'] ?> </p>
                 </div>
                <div class="action">
                 <?php if($curentUser && $curentUser['id'] == $article['author']) : ?>   
                    <a href="/form-article.php?id=<?= $article['id'] ?>" class="btn btn-primary">Modifier</a>
                    <a href="/delete-article.php/?id=<?=$article['id'] ?>" class="btn btn-secondary red">Supprimer</a>
                <?php endif; ?>

                </div>
               
               
            </div>


        </div>

    </div>
    <?php include 'includes/footer.php' ?>
</body>

</html>