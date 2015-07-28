<?php
    //Require base classes
    require_once 'common/loader.php';
    require_once 'common/basecontroller.php';
    require_once 'common/basemodel.php';
    require_once 'common/messagetype.php';
    require_once 'common/email.php';
    require_once 'common/modelerror.php';

    //Require helpers
    require_once 'helpers/htmlhelper.php';

    //Require models
    require_once 'models/homemodel.php';
    require_once 'models/errormodel.php';
    require_once 'models/accountmodel.php';

    //Require controllers
    require_once 'controllers/error.php';
    require_once 'controllers/home.php';
    require_once 'controllers/account.php';

    //create controllers and execute the action
    $loader = new Loader($_GET);

    $controller = $loader->CreateController();
    $controller->ExecuteAction();

