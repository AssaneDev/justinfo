<?php
require __DIR__.'/database/database.php';
$authDB = require __DIR__.'/database/security.php';

$currentUser = $authDB->isloggedin();

$articleDB = require_once __DIR__ .('/database/models/articleDB.php');


 $articles = $articleDB->fetchAll();

 $category = [];

 


 $_GET = filter_input_array(INPUT_GET,FILTER_SANITIZE_FULL_SPECIAL_CHARS);

 $SelectedCat = $_GET['cat'] ?? '';


 if (count($articles)) {
    // $articles = json_decode(file_get_contents($filename), true) ?? [];
    $cattmp = array_map(fn($a) => $a['category'],$articles);
    $category = array_reduce($cattmp,function($acc,$cat){
        if (isset($acc[$cat])) {
            $acc[$cat]++;
        }else{
            $acc[$cat] = 1;
        }
        return $acc;
    },[]);
    
    $ArticlesPerCategories = array_reduce($articles,function($acc,$article){
        if (isset($acc[$article['category']])) {
            $acc[$article['category']] = [...$acc[$article['category']],$article];
        }else{
            $acc[$article['category']] = [$article];
        }
        return $acc;
    },[]);
    
 }

//  echo count($articles);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'includes/head.php'?>
<link rel="stylesheet" href="../public/css/index.css">
<title>Just Info</title>
</head>
<body>
  
    <div class="container">
    <?php include 'includes/header.php'?>
        <div class="content">
          <div class="newsfeed-container">
            <ul class="category-container">
                <li class= <?=  $SelectedCat ? '' : 'cat-active'?>><a  href="/">Tous les articles(<?= count($articles) ?>)</a>  </li>
                    <?php foreach ($category as $catname => $catnum) :?>
                       <li class= <?=  $SelectedCat === $catname ? 'cat-active' : ''?>> <a href="/?cat=<?= $catname ?>"> <?=$catname?> <span class="small-text">(<?=$catnum?>)</span> </a> </li>     
                    <?php endforeach ?>   
             </ul>
                <div class="newsfeed-content">
                <?php if(!$SelectedCat) : ?>
                    <?php foreach($category as $cat=>$num) : ?>
                    <h2><?=$cat?></h2>
                    <div class="article-container">
                        <?php foreach($ArticlesPerCategories[$cat] as $a) : ?>
                            <a href="/show-article.php?id=<?=$a['id']?>" class="article block">
                                <div class="overflow">
                                    <div class="image-container" style="background-image: url(<?= $a['image']?>);"></div>
                                </div>
                                <h3 class="title"><?=$a['title']?></h3>

                                <?php if($a['author']) :?>
                                    <div class="article-author">
                                        <p><?= $a['firstname'].' '.$a['lastname'] ?></p>
                                    </div>
                                <?php endif;?>    
                           </a>
                        <?php endforeach ?>
                                
                      </div>
                      
                <?php endforeach; ?>  
                    
                <?php else :?>
                    <h2><?=$SelectedCat?></h2>
                    <div class="article-container">
                        <?php foreach($ArticlesPerCategories[$SelectedCat] as $a) : ?>
                            <a href="/show-article.php?id=<?=$a['id']?>" class="article block">
                                <div class="overflow">
                                    <div class="image-container" style="background-image: url(<?= $a['image']?>);"></div>
                                </div>
                                <h3 class="title"><?=$a['title']?></h3>
                            </a>
                        <?php endforeach ?>
                                
                      </div>
                    
                <?php endif;?>
          </div>
          
        </div>
        
    </div>
    <?php include 'includes/footer.php'?>
</body>
</html>