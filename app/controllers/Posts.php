<?php
class Posts extends Controller
{
    private $postModel;
    private $userModel;

    public function __construct()
    {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        //Get Posts
        $posts = $this->postModel->getPosts();

        $data = [
            'posts' => $posts,
        ];
        $this->view('posts/index', $data);
    }

    public function add(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => ''
            ];
            //validation
            if (empty($data['title'])) {
                $data['title_err'] = 'Please Enter Title';
            }
            if (empty($data['body'])) {
                $data['body_err'] = 'Please Enter Some Text';
            }
            //make sure No errors
            if (empty($data['title_err']) && empty($data['body_err'])) {
                //validate
                if ($this->postModel->addPost($data)) {
                    flash('post_message', 'Post Added');
                    redirect('posts');
                } else {
                    die('Something Went Wrong');
                }
            } else {
                //load view with errors
                $this->view('posts/add', $data);
            }

        } else {
            $data = [
                'title' => '',
                'body' => '',
            ];
        }


        $this->view('posts/add', $data);
    }
//posts/show/1::::here posts,show will be page and 3will be parameter(In core.php)
//$id is coming from the URL
    public function show($id)
    {
        $post = $this->postModel->getPostById($id);
        $user = $this->userModel->getUserById($post->user_id);
        $data = [
            'post' => $post,
            'user' => $user,
        ];
        $this->view('posts/show', $data);
    }


    public function edit($id){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'id' => $id,
                'title_err' => '',
                'body_err' => ''
            ];
            //validation
            if (empty($data['title'])) {
                $data['title_err'] = 'Please Enter Title';
            }
            if (empty($data['body'])) {
                $data['body_err'] = 'Please Enter Some Text';
            }
            //make sure No errors
            if (empty($data['title_err']) && empty($data['body_err'])) {
                //validate
                if ($this->postModel->updatePost($data)) {
                    flash('post_message', 'Post Updated');
                    redirect('posts');
                } else {
                    die('Something Went Wrong');
                }
            } else {
                //load view with errors
                $this->view('posts/edit', $data);
            }

        } else {

            //get existing post from model
            $post=$this->postModel->getPostbyId($id);

            //check for owner
            if ($post->user_id != $_SESSION['user_id']){
                redirect('posts');
            }
            $data = [
                'id'=>$id,
                'title' => $post->title,
                'body' => $post->body,
            ];
        }


        $this->view('posts/edit', $data);
    }

    public function delete($id){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //get existing post from model
            $post=$this->postModel->getPostbyId($id);

            //check for owner
            if ($post->user_id != $_SESSION['user_id']){
                redirect('posts');
            }
            if ($this->postModel->deletePost($id)){
                flash('post_message','Post Removed');
            }else{
                echo 'Something Went Wrong';
            }
        }else{
            redirect('posts');
        }

        $this->index();
    }
}