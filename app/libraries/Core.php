<?php
/*
 * App Core Class
 * Creates URL and loads Core controllers
 * URL format - /controller/method/parameters
 *
 * we are always in the 'index.php'.we have to calculate the path from 'index.php'
 *
 * All the Controllers will be classes.
*/

class Core{

    protected $currentController ='Pages';
    protected $currentMethod ='index';
    protected $params =[];

    public function __construct(){

        $url =$this ->getUrl();

        //Look in controllers for the first part of the URL
        if (file_exists('../app/controllers/'.ucwords($url[0]).'.php')){

            //IF the file exist, set it in the currentController
            $this ->currentController =ucwords($url[0]);

            //Unset the 0 index
            unset($url[0]);

        }

        //require the controller
        require_once '../app/controllers/'. $this->currentController.'.php';

        //instantiate controller class
        $this ->currentController =new $this->currentController();

        //check for the second part of the URL
        if(isset($url[1])){
            //check to see the if the method exist in the controller
            if (method_exists($this->currentController,$url[1])){
                $this->currentMethod = $url[1];

                //Unset the 1 index
                unset($url[1]);
            }
        }

        //Get Params
        $this->params =$url ?array_values($url): [];

        //Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod],$this->params);
    }

    public function getUrl(){
        //echo $_GET['url'];
        if (isset($_GET['url'])){
            $url =rtrim($_GET['url'], '/');//it will cut the last '/',if any
            $url =filter_var($url, FILTER_SANITIZE_URL);
            $url =explode('/',$url);//cut the url by diving '/',it will produce an array
            return $url;
        }
    }
}