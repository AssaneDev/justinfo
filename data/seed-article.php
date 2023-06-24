<?php
$articles = json_decode(file_get_contents('./data.json'),true);

$dns = 'mysql:host=localhost;dbname=blog';
$usr = 'root';
$password ='**Ordinateur12';

$pdo = new PDO($dns,$usr,$password);

$statement = $pdo->prepare('INSERT INTO 
article(
   title,
   image,
   category,
   content 
) VALUES (
    :title,
    :image,
    :category,
    :content
)'
);

foreach($articles as $article){
    $statement->bindValue('title',$article['title']);
    $statement->bindValue('image',$article['image']);
    $statement->bindValue('category',$article['category']);
    $statement->bindValue('content',$article['content']);
    $statement->execute();

}