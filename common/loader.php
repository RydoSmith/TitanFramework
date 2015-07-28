<?php

class Loader
{
    private $controller, $action, $urlValues, $urlParams;

    //store url values on object creation
    public function __construct($u)
    {
        if(isset($u['url']))
        {
            $this->urlValues = explode("/", $u['url']);
            $this->urlParams = array();

            //Controller
            if (isset($this->urlValues[0]) && $this->urlValues[0] != "") {
                $this->controller = $this->urlValues[0];
            } else {
                $this->controller = 'Home';
            }

            //Action
            if (isset($this->urlValues[1]) &&  $this->urlValues[1] != "") {
                $this->action = $this->urlValues[1];
            } else {
                $this->action = 'Index';
            }

            for($i = 2; $i < count($this->urlValues); $i++)
            {
                if(isset($this->urlValues[$i]) && $this->urlValues[$i] != "")
                {
                    array_push($this->urlParams, $this->urlValues[$i]);
                }
            }
        }
        else
        {
            $this->controller = 'Home';
            $this->action = 'Index';
        }
    }

    //Establish the requested controller as an object
    public function CreateController()
    {
        if(class_exists($this->controller))
        {
            $parents = class_parents($this->controller);

            //does the class extend the controller class
            if(in_array('BaseController', $parents))
            {
                //does the class contain the requested method
                if(method_exists($this->controller, $this->action))
                {
                    //print_r($this->urlParams); exit();
                    return new $this->controller($this->action, $this->urlParams);
                }
                else
                {
                    return new Error('BadUrl', $this->urlValues);
                }
            }
            else
            {
                return new Error('BadUrl', $this->urlValues);
            }
        }
        else
        {
            return new Error('BadUrl', $this->urlValues);
        }
    }
}