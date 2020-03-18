<?php
require_once "../Includes/functions.php";
require_once "../Includes/head.php";
session_start();

$ID_THEME = $_GET['id'];
$stmt = getDb()->prepare('select * from theme where ID_THEME=?');
$stmt->execute(array($ID_THEME));
$theme = $stmt->fetch(); // Access first (and only) result line
?>


<html>
    <body>
        <?php require_once "../Includes/header.php"; ?>
        <div class="container">
            <br/><div class="jumbotron">
                <div class="row">
                    <?php if( $theme['PHOTOS']!=NULL)
                    {?>
                        <div class="col-md-5 col-sm-7">
                            <img class="img-fluid" src="../Images/<?= $theme['PHOTOS'] ?>" title="<?= $theme['NOM_THEME'] ?>" />
                        </div>
                    <?php } ?>
                    <div class="col-md-7 col-sm-5">
                        <h2 class= "text-center"><?= $theme['NOM_THEME'] ?></h2>
                        <p>Nombre de questions: <?= $theme['NB_QUESTIONS'] ?> </p>
                        <p>Temps imparti: <?= $theme['TIMER']/60 ?> minutes et <?= $theme['TIMER']%60 ?> secondes </p>
                        <p><small><?= $theme['DESC_THEME'] ?></small></p>

                        <?php if($_SESSION['mode']=="joueur")
                        {?>
                            <h4 class= "text-center">Meilleur score: <?= $theme['BEST_SCORE'] ?></h4>

                            <br/>
                            <br/>
                            <h5 class= "text-center">Choix de la difficulté:</h5>
                            <div class='row'>
                                <div class="col"> <p class="text-center"> <a href="PartieQuizz.php?id=<?= $theme['ID_THEME'] ?>?diff=1" class="btn btn-primary btn-lg"> Facile</a> </p> </div>
                                <div class="col"> <p class="text-center"> <a href="PartieQuizz.php?id=<?= $theme['ID_THEME'] ?>?diff=2" class="btn btn-warning btn-lg"> Moyen </a> </p> </div>
                                <div class="col"> <p class="text-center"> <a href="PartieQuizz.php?id=<?= $theme['ID_THEME'] ?>?diff=3" class="btn btn-danger btn-lg"> Difficile </a> </p> </div>
                            </div>
                        <?php }?>
                    </div>
                </div>            
            </div>
            <?php if($_SESSION['mode']=="admin")
            {
                $questions = getDb()->prepare('select * from question where ID_THEME=? order by ID_QUEST');
                $questions->execute(array($ID_THEME));
                ?>
                <br/><div class="jumbotron">
                    <h3 class="text-center"> Liste des questions </h3>
                    <?php foreach($questions as $question)
                    {?>
                        <hr/>
                        <h4>Question n°<?= $question['ID_QUEST']?>: </h4>
                        <p>Intitulé: <?= $question['INTITULE'] ?> </p>
                        <p>Type de Question: 
                        <?php if($question['TYPE_QUEST']==0)
                        {
                            print " Vrai/Faux";
                        }
                        elseif($question['TYPE_QUEST']==1)
                        {
                            print " Question ouverte";
                        }
                        elseif($question['TYPE_QUEST']==2)
                        {
                            print " QCM";
                        }
                        ?> </p>
                        <h5>Réponses: </h5>
                        <?php $reponses = getDb()->prepare('select * from reponse where ID_QUEST=? order by ID_REPONSE');
                        $reponses->execute(array($question['ID_QUEST']));
                        foreach($reponses as $reponse)
                        {
                            if($reponse['IS_TRUE']==0)
                            {?>
                                <div class="alert alert-success" role="alert">
                            <?php }
                            else
                            {?>
                                <div class="alert alert-danger" role="alert">
                            <?php } 
                                print ''. $reponse['INTITULE']; ?>
                            </div>
                        <?php }
                    } ?>
                </div>
            <?php } ?>
            
        </div>
		<?php require_once "../Includes/footer.php"; ?> 
		<?php require_once "../Includes/scripts.php"; ?> 
    </body>

</html>