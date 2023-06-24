<?php
  $pdo = require_once __DIR__.'/database/database.php';
  $authDB = require_once __DIR__.'/database/security.php';
  Const ERROR_REQUIRED = "Veuillez Remplir le champs";
  Const ERROR_TOO_SHORT = "le contenue du Champs Trop Court";
  Const ERROR_PASSWORD_SHORT = "le mot de pass doit faire au moins 6 carractere";
  Const ERROR_EMAIL_INVALIDE = "le contenue du Champs Trop Court";
  Const ERROR_PASSWORD_CONF_INCORECT = "les mots de pass ne sont pas simmilaire";





  $error = [
     'firstname'=>"",
     'lastname'=>"",
     'password'=>"",
     'passwordconfirm'=>"",
  ];
 

 
 
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $input = filter_input_array(INPUT_POST,[
        'firstname'=>FILTER_SANITIZE_SPECIAL_CHARS,
        'lastname'=>FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'email'=>FILTER_SANITIZE_EMAIL,
       ]);
   
    $firstname = $input['firstname'] ?? ''; 
    $lastname = $input['lastname'] ?? ''; 
    $email = $input['email'] ?? ''; 
    $password = $_POST['password'] ?? '';
    $passwordconfirm = $_POST['passwordconfirm'] ?? '';   

    if (!$firstname) {
        $error['firstname'] = ERROR_REQUIRED;
    }elseif(mb_strlen($firstname)<2){
        $error['firstname'] = ERROR_TOO_SHORT;
    }

    if (!$lastname) {
        $error['lastname'] = ERROR_REQUIRED;
    }elseif(mb_strlen($lastname)<2){
        $error['lastname'] = ERROR_TOO_SHORT;
    }

    if (!$email) {
        $error['email'] = ERROR_REQUIRED;
    }elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $error['email'] = ERROR_EMAIL_INVALIDE;
    }

    
    if (!$password) {
        $error['password'] = ERROR_REQUIRED;
    }elseif(mb_strlen($password)<6){
        $error['password'] = ERROR_PASSWORD_SHORT;
    }
    

    if (!$passwordconfirm) {
        $error['passwordconfirm'] = ERROR_REQUIRED;
    }elseif($password !== $passwordconfirm){
        $error['passwordconfirm'] = ERROR_PASSWORD_CONF_INCORECT;
    }

     if (empty(array_filter($error,fn($e)=>$e !== ''))) {

        $authDB->Register([
            'firstname'=> $firstname,
            'lastname'=> $lastname,
            'email'=> $email,
            'password'=>$password
        ]);
    }
   
     
 
  }
 
 

?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'includes/head.php'?>
<link rel="stylesheet" href="../public/css/auth-register.css">
<title>Inscription</title>
</head>
<body>
  
    <div class="container">
    <?php include 'includes/header.php'?>
    <div class="content">
        <div class="block form-container">
                <h1 class="title">Inscription</h1>
                <form action="/auth-register.php" method="POST">
                    <div class="form-controle">
                        <label for="firstname">Prenom</label>
                        <input type="text" name="firstname" id="firstname" value="<?= $firstname ?? '' ?>" >
                        <?php if($error['firstname']) : ?>
                        <p class="text-error"> <?= $error['firstname'] ?> </p>
                        <?php endif ?>
                    </div>
                    <div class="form-controle">
                        <label for="lastname">Nom</label>
                        <input type="text" name="lastname" id="lastname" value="<?= $lastname ?? '' ?>">
                        <?php if($error['lastname']) : ?>
                        <p class="text-error"><?= $error['lastname'] ?></p>
                        <?php endif ?>
                    </div>
                    
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

                    <div class="form-controle">
                        <label for="passwordconfirm">Confirmation Mot De Pass</label>
                        <input type="password" name="passwordconfirm" id="passwordconfirm">
                        <?php if($error['passwordconfirm']) : ?>
                        <p class="text-error"><?= $error['passwordconfirm'] ?></p>
                        <?php endif ?>
                    </div>
                    
                    
                    <div class="form-action">
                        <a href="/" class="btn btn-primary">Annuler</a>
                        <button type="submit" class="btn btn-secondary">Valider</button>
                    </div>
                </form>
                </div>
                </div> 
    </div>
    <?php include 'includes/footer.php'?>
</body>
</html>