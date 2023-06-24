<?php 
require __DIR__.'/database/database.php';
$authDB = require __DIR__.'/database/security.php';

$currentUser = $authDB->isloggedin();

if(!$currentUser){
    header('Location:/');
}

$articleDB = require_once __DIR__ .('/database/models/articleDB.php');

 Const ERROR_REQUIRED = "Veuillez Remplir le champs";
 Const ERROR_CONTENT_TO_SHOORT = "Article trop court";
 Const ERROR_URL = "Veuillez Remplire Une Url";
 Const ERROR_TITLE_SHOORT = "Titre Trop court";
 

 $error = [
    'title'=>"",
    'image'=>"",
    'category'=>"",
    'content'=>"",
 ];

$_GET = filter_input_array(INPUT_GET,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'];
if ($id) {
    $article = $articleDB->fetch($id);
    if ($article['author'] !== $currentUser['id']) {
        header('Location:/');
    }
    $title = $article['title'];
    $image = $article['image'];
    $category = $article['category'];
    $content = $article['content'];
    
}


 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $_POST = filter_input_array(INPUT_POST,[
       'title'=>FILTER_SANITIZE_FULL_SPECIAL_CHARS,
       'image'=>FILTER_SANITIZE_URL,
       'category'=>FILTER_SANITIZE_FULL_SPECIAL_CHARS,
       'content'=>[
        'filter'=>FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'flag'=>FILTER_FLAG_NO_ENCODE_QUOTES
       ]
    ]);

    $title = $_POST['title'];
    $image = $_POST['image'];
    $category = $_POST['category'];
    $content = $_POST['content'];

    if (!$title) {
        $error['title'] = ERROR_REQUIRED;
        
    }elseif(strlen($title) < 5 ){
        $error['title'] = ERROR_TITLE_SHOORT;

    }
    if (!$image) {
        $error['image'] = ERROR_REQUIRED;
    }elseif(!filter_var($image, FILTER_VALIDATE_URL)){
        $error['image'] = ERROR_REQUIRED;
    }
    if (!$category) {
        $error['category'] = ERROR_REQUIRED;
    }
      if (!$content) {
        $error['content'] = ERROR_REQUIRED;
        
    }elseif(strlen($content) < 50 ){
        $error['content'] = ERROR_CONTENT_TO_SHOORT;

    }
    if (empty(array_filter($error,fn($e)=>$e !== ''))) {
    if ($id) {
        $article['title'] = $title;
        $article['image'] = $image;
        $article['category'] = $category;
        $article['content'] = $content;
        $article['author'] = $curentUser['id'];
        $articleDB->updateOne($article);
       

        
    }else{
        $articleDB->createOne([
            'title'=>$title,
            'image'=>$image,
            'category'=>$category,
            'content'=>$content,
            'author'=>$curentUser['id'],

        ]);
       

           
          }
     
    header('location:/');
    }

    

 }



?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'includes/head.php'?>
<!-- <link rel="stylesheet" href="../public/css/form-article.css"> -->

<title><?= $id ? 'Modifier article' : 'Creer Article' ?></title>
</head>
<body>
  
    <div class="container">
    <?php include 'includes/header.php'?>
        <div class="content">
            <div class="block form-container">
                <h1 class="title"><?= $id ? 'Modifier article' : 'Creer Article' ?></h1>
                <form action="/form-article.php<?= $id ? "/?id=$id" : '' ?> " method="POST">
                    <div class="form-controle">
                        <label for="title">Titre</label>
                        <input type="text" name="title" id="title" value="<?= $title ?? '' ?>" >
                        <?php if($error['title']) : ?>
                        <p class="text-error"> <?= $error['title'] ?> </p>
                        <?php endif ?>
                    </div>
                    <div class="form-controle">
                        <label for="image">Image</label>
                        <input type="text" name="image" id="image" value="<?= $image ?? '' ?>"" >
                        <?php if($error['image']) : ?>
                        <p class="text-error"><?= $error['image'] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="form-controle">
                        <label for="category">Categorie</label>
                        <select name="category" id="category">
                            <option <?=!$category || $category ==='politique' ? 'selected' : ''?> value="politique">Politique</option>
                            <option <?= $category ==='societe' ? 'selected' : ''?> value="societe">Societe</option>
                            <option <?= $category ==='religion' ? 'selected' : ''?> value="religion">Religion</option>
                        </select>
                        <?php if($error['category']) : ?>
                        <p class="text-error"><?= $error['category'] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="form-controle">
                        <label for="content">Contenue</label>
                        <textarea name="content" id="content">  <?= $content ?? '' ?>  </textarea>
                        <?php if($error['content']) : ?>
                        <p class="text-error"><?= $error['content'] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="form-action">
                        <a href="/" class="btn btn-primary">Annuler</a>
                        <button type="submit" class="btn btn-secondary"><?= $id ? 'Modifier' : 'Valider' ?></button>
                    </div>
                </form>
            </div>
        </div>
        <?php include 'includes/footer.php'?>
    </div>
 
</body>
</html>