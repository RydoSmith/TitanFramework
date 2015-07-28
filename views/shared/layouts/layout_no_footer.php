<!DOCTYPE html>
<html lang="en">
    <head>

        <?php require('views/shared/header.php'); ?>
    </head>
    <body>
        <?php require('views/shared/nav.php'); ?>
        <?php
            if(isset($model) && isset($model->message))
            {
                echo $model->message;
            }
        ?>
        <?php require($location);  ?>
    </body>
</html>