<?php
class Users extends Controller{
    private $userModel;
    public function __construct(){

        //load the model for database
        $this->userModel =$this->model('User');
    }
    public function register(){

        //check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            //if we click submit then process form

            //check submit button work
            //die('submitted');


            //sanitize post data
            $_POST =filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data =[
                'name' =>trim($_POST['name']),
                'email' =>trim($_POST['email']),
                'password' =>trim($_POST['password']),
                'confirm_password' =>trim($_POST['confirm_password']),
                //error variable,to display that error if error happened
                'name_err' =>'',
                'email_err' =>'',
                'password_err' =>'',
                'confirm_password_err' =>'',

            ];

            //validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter Email';
            }else{
                //check duplicate email
                if ($this->userModel->findUserByEmail($data['email'])){
                    $data['email_err'] = 'Email is already taken';
                }
            }

            //validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter Name';
            }

            //validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter Password';
            }elseif (strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be at least 6 character';
            }

            //validate confirm_password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please Confirm Password';
            }else{
                if ($data['password'] != $data['confirm_password']){
                    $data['confirm_password_err'] = 'Password Doesn\'t match ';
                }
            }

            //Make Sure errors are empty
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                //Validated
                //die('Success');

                //Hash password
                $data['password'] =password_hash($data['password'], PASSWORD_DEFAULT);

                //Register User
                if($this->userModel->register($data)){
                    //if return value is true
                  flash('register_success', 'You are now registered & can Log In');
                  redirect('users/login');

                }else{
                    //if return value is false
                    die('something Went Wrong');
                }
            }else{
                //Load view with errors
                $this->view('users/register',$data);
            }



        }else{
            //if it is not submit,user just reload the page
            //echo 'load form';

            //INIT data
            //Blank register form
            //if user fill something but error we want that data still be in the form ,not refill.
            $data =[
                'name' =>'',
                'email' =>'',
                'password' =>'',
                'confirm_password' =>'',
                //error variable,to display that error if error happened
                'name_err' =>'',
                'email_err' =>'',
                'password_err' =>'',
                'confirm_password_err' =>'',

            ];
            //Load View
            $this->view('users/register',$data);

        }
    }
    public function login(){

        //check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            //if we click submit then process form

            //sanitize post data
            $_POST =filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data =[
                'email' =>trim($_POST['email']),
                'password' =>trim($_POST['password']),
                //error variable,to display that error if error happened
                'email_err' =>'',
                'password_err' =>'',

            ];

            //validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter Email';
            }
            //validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter Password';
            }elseif (strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be at least 6 character';
            }

            //Check for user/email
            if ($this->userModel->findUserByEmail($data['email'])){
                //User Found
            }else{
                //User Not Found
                $data['email_err']= 'No User Found,Try Again!!!';
            }

            //Make Sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])){
                //Validated
                //die('Success');

                $loggedInUser =$this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser){
                    //if the return value is true,then.............
                    //die('Logged In');
                    //create a session variable
                    $this->createUserSession($loggedInUser);
                }else{
                    $data['password_err']='Password Incorrect';
                    $this->view('users/login',$data);
                }
            }else{
                //Load view with errors
                $this->view('users/login',$data);
            }




        }else{
            $data =[
                'email' =>'',
                'password' =>'',
                //error variable,to display that error if error happened
                'email_err' =>'',
                'password_err' =>'',

            ];
            //Load View
            $this->view('users/login',$data);

        }
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;

        //redirect('pages/index');
        redirect('posts');
    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();

        redirect('users/login');
    }


}