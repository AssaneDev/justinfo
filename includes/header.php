<?php

$currentUser = $currentUser ?? false;
?>
<header>
        <a href="/" class="logo">Just Info Sénégal</a>

        <div class="header-mobile">
                <div class="header-mobile-icon">
                        <img src="/public/image/menu.png" alt="" srcset="" width="40px" height="40px"    >
                </div>
                        <ul class="header-nav-mobile show">
                                <?php if($currentUser) : ?>
                                        <li class="<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? "active" : '' ?>" > 
                                                <a  href="/form-article.php">Ecrire Un article</a>
                                        </li>

                                        <li > <a href="/auth-logout.php">Deconexion</a></li>

                                        <li class="<?= $_SERVER['REQUEST_URI'] === '/auth-profile.php' ? "active" : '' ?> " >
                                        <a  href="/profile.php">Mon Espace
                                        </a></li>

                                <?php else : ?>
                                        <li class="<?= $_SERVER['REQUEST_URI'] === '/auth-register.php' ? "active" : '' ?>" > 
                                        <a  href="/auth-register.php">Inscription</a>
                                </li>
                                        <li class="<?= $_SERVER['REQUEST_URI'] === '/auth-login.php' ? "active" : '' ?>" > 
                                        <a  href="/auth-login.php">Connexion</a>
                                </li>
                                <?php endif; ?>       
                                


                        </ul>
        </div>
        <ul class="header-nav">
                <?php if($currentUser) : ?>
                        <li class="<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? "active" : '' ?>" > 
                                <a  href="/form-article.php">Ecrire Un article</a>
                        </li>

                        <li > <a href="/auth-logout.php">Deconexion</a></li>

                        <li class="<?= $_SERVER['REQUEST_URI'] === '/auth-profile.php' ? "active" : '' ?> header-profile" >
                         <a  href="/profile.php"><?= $currentUser['firstname'][0].$currentUser['lastname'][0] ?>
                        </a></li>

                 <?php else : ?>
                        <li class="<?= $_SERVER['REQUEST_URI'] === '/auth-register.php' ? "active" : '' ?>" > 
                        <a  href="/auth-register.php">Inscription</a>
                       </li>
                        <li class="<?= $_SERVER['REQUEST_URI'] === '/auth-login.php' ? "active" : '' ?>" > 
                          <a  href="/auth-login.php">Connexion</a>
                       </li>
                 <?php endif; ?>       
                


        </ul>
 </header>