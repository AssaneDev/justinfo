<?php
class AuthDB{
  private PDOStatement $statementRegister;
  private PDOStatement $statementReadSession;
  private PDOStatement $statementReadUser;
  private PDOStatement $statementReadUserFrmEmail;
  private PDOStatement $statementCreateSession;
  private PDOStatement $statementDeleteSession;

  function __construct(private PDO $pdo){
    $this->statementRegister = $pdo->prepare('INSERT INTO user VALUES(
      DEFAULT,
      :firstname,
      :lastname,
      :email,
      :password
  )');

    $this->statementReadSession = $pdo->prepare('SELECT * FROM session WHERE id=:idsession ');
    $this->statementReadUser = $pdo->prepare('SELECT * FROM user WHERE id=:id');
    $this->statementReadUserFrmEmail= $pdo->prepare('SELECT * FROM user WHERE email = :email');
    $this->statementCreateSession = $pdo->prepare('INSERT INTO session VALUES(
      :idsession,
      :iduser
  )');

      $this->statementDeleteSession =  $pdo->prepare('DELETE FROM session WHERE id=:id');

  }
 
  function Login(string $idUser):void{
    $idSession = bin2hex(random_bytes(32));
    $this->statementCreateSession->bindvalue(':iduser',$idUser);
    $this->statementCreateSession->bindvalue(':idsession',$idSession);
    $this->statementCreateSession->execute();
      $signature = hash_hmac('sha256',$idSession,'le matin va arrivé');
      setcookie('session',$idSession,time() + 60 * 60 * 24 *14,'','',false,true);
      setcookie('signature',$signature,time() + 60 * 60 * 24 *14,'','',false,true);

      return;
  }
  function Register(array $user):void{
          
        $passwordhash = password_hash($user['password'],PASSWORD_ARGON2I);
        $this->statementRegister->bindvalue(':firstname', $user['firstname']);
        $this->statementRegister->bindvalue(':lastname', $user['lastname']);
        $this->statementRegister->bindvalue(':email', $user['email']);
        $this->statementRegister->bindvalue(':password', $passwordhash);
        $this->statementRegister->execute();
        return;
  }
  function isloggedin():array|false{
        $sessionId = $_COOKIE['session'] ?? '';
        $signature = $_COOKIE['signature'] ?? '';
        if ($sessionId && $signature) {
         $hash = hash_hmac('sha256',$sessionId,'le matin va arrivé');
            if (hash_equals($hash,$signature)) {
                  $this->statementReadSession->bindvalue(':idsession',$sessionId);
                  $this->statementReadSession->execute();
                  $session = $this->statementReadSession->fetch();
              
                  if ($session) {
              
                    $this->statementReadUser->bindvalue(':id',$session['userid']);
                    $this->statementReadUser->execute();
                    $user = $this->statementReadUser->fetch();
              }
         }
       
        }
    
        return $user ?? false;
   }
  function LogOut($sessionId):void{
    $this->statementDeleteSession->bindvalue(':id',$sessionId);
    $this->statementDeleteSession->execute();
    setcookie('session','',time() - 1);
    setcookie('signature','',time() - 1);

    return;
  }
  function getUserFromEmail($email):array{
      $this->statementReadUserFrmEmail->bindValue(':email',$email);
      $this->statementReadUserFrmEmail->execute();
      return $this->statementReadUserFrmEmail->fetch();
  }

}
return new AuthDB($pdo);
