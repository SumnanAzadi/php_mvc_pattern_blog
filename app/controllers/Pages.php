
+<?php
//This is default Class
class Pages extends Controller {

    public function __construct(){

    }


    //this is default Page/method
    public function index(){

        //ifn logged in then posts page otherwise home page
        if (isLoggedIn()){
            redirect('posts');
        }

        $data =['title' => 'SharedPosts',
                'description' => 'Simple Social Network built on Traversy MVC Framework ,As to grab basic knowledge of MVC framework',
                ];
        $this->view('pages/index',$data);

    }

    public function about(){
        $data =['title' => 'About Us',
            'description' => 'App to shared posts to other User',
        ];
        $this->view('pages/about',$data);
    }
}