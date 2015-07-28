<?php

abstract class BaseModel
{
    protected $database, $config;
    public $view;

    public function __construct($config)
    {
        $this->config = $config;
        $this->database = $this->getDatabaseConnection($config);

        $this->view = new stdClass();
        $this->view->modelErrors = array();
        $this->view->hasError = false;
    }

    function getDatabaseConnection($config) {
        static $dbLink;
        if (!($dbLink instanceof PDO)) {
            try {
                $dsn = "mysql:host=".$config['db_location'].";dbname=".$config['db_name'];
                $dbLink = new PDO($dsn, $config['db_user'], $config['db_pass']);
            }
            catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        return $dbLink;
    }

    public function addModelError($field, $modelError)
    {
        $this->view->modelErrors[$field] = $modelError;
    }

    public function hasError()
    {
        return count($this->view->modelErrors) > 0 ? true : false;
    }

    public function add($property, $value)
    {
        $this->view->$property = $value;
    }

    //META DATA
    public function setPageTitle($x)
    {
        $this->view->pageTitle = $x;
    }

    public function setAlert($type, $title, $message)
    {
        $this->view->alert = new stdClass();
        $this->view->alert->type = $type;
        $this->view->alert->title = $title;
        $this->view->alert->message = $message;
    }

    //MESSAGES
    public function setMesssage($type, $title, $message)
    {
        if($type == MessageType::Success)
        {
            $this->setSuccessMessage($title, $message);
        }
        else if($type == MessageType::Error)
        {
            $this->setErrorMessage($title, $message);
        }
        else if($type == MessageType::Warning)
        {
            $this->setWarningMessage($title, $message);
        }
        else
        {
            $this->setInfoMessage($title, $message);
        }
    }

    public function setSuccessMessage($title, $message)
    {
        $this->view->message =  "<div class=\"alert alert-success\"><span class=\"title\">".$title."</span><span class=\"message\">".$message."</span><span class=\"close\">(click to close)</span></div>";
    }

    public function setErrorMessage($title, $message)
    {
        $this->view->message =  "<div class=\"alert alert-danger\"><span class=\"title\">".$title."</span><span class=\"message\">".$message." (click to close)</span><span class=\"close\">(click to close)</span></div>";
    }

    public function setWarningMessage($title, $message)
    {
        $this->view->message =  "<div class=\"alert alert-warning\"><span class=\"title\">".$title."</span><span class=\"message\">".$message." (click to close)</span><span class=\"close\">(click to close)</span></div>";
    }

    public function setInfoMessage($title, $message)
    {
        $this->view->message =  "<div class=\"alert alert-info\"><span class=\"title\">".$title."</span><span class=\"message\">".$message." (click to close)</span><span class=\"close\">(click to close)</span></div>";
    }

    //User
    public function getUserID()
    {
        if(isset($_SESSION) && $_SESSION['LoggedIn'] == 1 && isset($_SESSION['Username']))
        {
            $sql = "SELECT id FROM users WHERE email=:e LIMIT 1";
            if($stmt = $this->database->prepare($sql))
            {
                $stmt->bindParam(':e', $_SESSION['Username'], PDO::PARAM_STR);
                $stmt->execute();
                $id = $stmt->fetch()['id'];
                $stmt->closeCursor();
                return $id;
            }
        }
        else
        {
            return null;
        }
    }



}