<?php
/*
 * base Controller
 * Loads models and views
 */

class Controller{
    //load Model
    public function model($model){
        //require model file
        require_once ('../app/models/'.$model. '.php');

        //Instantiate model
        return new $model(); //new Post(),new user()
    }

    //load view
    public function view($view, $data =[]){
        //check for the view file
        if (file_exists('../app/views/'.$view. '.php')){
            require_once ('../app/views/'.$view. '.php');
        }else{
            //View doesn't exist
            die('View Does not Exist!!!');
        }
    }
}