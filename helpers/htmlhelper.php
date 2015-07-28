<?php

class HTMLHelper
{
    public function __construct()
    {

    }

    public function DisplayErrorFor($m, $field)
    {
        if(isset($m) && isset($m->modelErrors[$field]))
        {
            echo '<p class="alert alert-danger" style="padding: 5px 5px; margin: 5px 0;">'.$m->modelErrors[$field]->message.'</p>';
        }
    }

    public function DisplayValueFor($m, $field)
    {
        if(isset($m) && isset($m->$field) && $m->$field != null)
        {
            echo 'value="'.$m->$field.'"';
        }
    }

    public function GetAssociativeArrayByIndex($array, $index)
    {
        if(isset($array))
        {
            $keys = array_keys($array);
            if(isset($array[$keys[$index]]) && isset($array[$keys[$index]]->wizardPage))
            {
                echo $array[$keys[$index]]->wizardPage;
            }
        }
    }

}