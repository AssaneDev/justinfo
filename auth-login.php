<?php
  require __DIR__.'/database/database.php';
  $authDB = require_once __DIR__.'/database/security.php';
  Const ERROR_REQUIRED = "Veuillez Remplir le champs";
  Const ERROR_EMAIL_INVALIDE = "l\'email est invalide";
  Const ERROR_EMAIL_UKNOW = "l\'email est N\'est pas enrigistre";
  Const ERROR_PASSWORD_INCORRECT= "le mot de pass est incorecte";

  $error = [
     'email'=>"",
     'password'=>"",
 
  ];
 
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $input = filter_input_array(INPUT_POST,[
        'email'=>FILTER_SANITIZE_EMAIL,
       ]);
    $email = $input['email'] ?? ''; 
    $password = $_POST['password'] ?? '';

    if (!$email) {
        $error['email'] = ERROR_REQUIRED;
    }elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $error['email'] = ERROR_EMAIL_INVALIDE;
    }
    if (!$password) {
        $error['password'] = ERROR_REQUIRED;
    }

     if (empty(array_filter($error,fn($e)=>$e !== ''))) {
     $user = $authDB->getUserFromEmail($email);

       if (!$user) {
          $error['email'] = ERROR_EMAIL_UKNOW; 
       }else{
        if (!password_verify($password,$user['password'])) {
            $error['password'] = ERROR_PASSWORD_INCORRECT; 
        }else{
           $authDB->Login($user['id']);
            header('Location: /');
        }
       }
    
    
    }
  }
 
 

?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'includes/head.php'?>
<link rel="stylesheet" href="../public/css/auth-login.css">
<title>Connexion</title>
</head>
<body>
  
    <div class="container">
    <?php include 'includes/header.php'?>
    <div class="content">
        <div class="block form-container">
                <h1 class="title">Connexion</h1>
                <form action="/auth-login.php" method="POST">
                    
                    
                    <div class="form-controle">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="<?= $email ?? '' ?>">
                        <?php if($error['email']) : ?>
                        <p class="text-error"><?= $error['email'] ?></p>
                        <?php endif ?>
                    </div>
                    
                    <div class="form-controle">
                        <label for="password">Mot De Pass</label>
                        <input type="password" name="password" id="password" >
                        <?php if($error['password']) : ?>
                        <p class="text-error"><?= $error['password'] ?></p>
                        <?php endif ?>
                    </div>

                    
                    
                    <div class="form-action">
                        <button type="submit" class="btn btn-secondary">Connexion</button>
                    </div>
                </form>
                </div>
                </div> 
    </div>
    <?php include 'includes/footer.php'?>
</body>
</html>