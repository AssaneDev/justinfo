<?php 
class ArticleDB{

    private PDOStatement $statementCreateOne;
    private PDOStatement $statementUpdateOne;
    private PDOStatement $statementDeleteOne;
    private PDOStatement $statementReadOne;
    private PDOStatement $statementReadAll;
    private PDOStatement $statementReadUserAll;

    function __construct(private PDO $pdo){
        $this->statementCreateOne = $pdo->prepare('INSERT INTO article (
            title,
            image,
            category,
            content,
            author
         )VALUES(
            :title,
            :image,
            :category,
            :content,
            :author
         )');

        $this->statementUpdateOne = $pdo->prepare('UPDATE article SET
        title=:title,
        image=:image,
        category=:category,
        content=:content
        author=:author

        WHERE id=:id
        ');
        $this->statementDeleteOne = $pdo->prepare('DELETE FROM article WHERE id=:id');
        $this->statementReadOne = $pdo->prepare('SELECT article.*, user.firstname, user.lastname FROM article LEFT JOIN user ON article.author=user.id WHERE article.id=:id ');
        $this->statementReadAll = $pdo->prepare('SELECT article.*, user.firstname ,user.lastname FROM article LEFT JOIN user ON article.author=user.id');
        // $statement = $pdo->prepare('SELECT * FROM article WHERE id=:id');
        

        $this->statementReadUserAll = $pdo->prepare('SELECT * FROM article WHERE author=:idauthor');
            
    }
   
    public function fetchAll() :array{
        $this->statementReadAll->execute();
        return $this->statementReadAll->fetchAll();
    }

    public function fetch(string $id) :array{
        $this->statementReadOne->bindValue(':id',$id);
        $this->statementReadOne->execute();
        return $this->statementReadOne->fetch();    
    }

    public function deleteOne(int $id):string{
        $this->statementDeleteOne->bindValue(':id',$id);
        $this->statementDeleteOne->execute();
        return $id;
    }


    public function createOne($article):array{
        $this->statementCreateOne->bindValue(':title',$article['title']);
        $this->statementCreateOne->bindvalue(':image',$article['image']);
        $this->statementCreateOne->bindvalue(':category',$article['category']);
        $this->statementCreateOne->bindvalue(':content',$article['content']);
        $this->statementCreateOne->bindvalue(':author',$article['author']);


        $this->statementCreateOne->execute();
        return $this->fetch($this->pdo->lastInsertId());

    }

   
    public function updateOne($article):array{
        $this->statementUpdateOne->bindValue(':title',$article['title']);
        $this->statementUpdateOne->bindvalue(':image',$article['image']);
        $this->statementUpdateOne->bindvalue(':category',$article['category']);
        $this->statementUpdateOne->bindvalue(':content',$article['content']);
        $this->statementUpdateOne->bindvalue(':id',$article['id']);
        $this->statementUpdateOne->bindvalue(':author',$article['author']);


        $this->statementUpdateOne->execute();    
        return $article;

        
    }

    function fetchUserArticles(string $authorId) : array{
        $this->statementReadUserAll->bindValue(':idauthor', $authorId);
        $this->statementReadUserAll->execute();
        return $this->statementReadUserAll->fetchAll();
         
    }

    
}

return new ArticleDB($pdo);