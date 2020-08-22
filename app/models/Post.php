<?php
class Post{
    private $db;

    public function __construct(){
        $this->db =new Database();
    }

    public function getPosts(){
        $this->db->query('Select *,
                              posts.id as postId,
                              users.id as UserId,
                              posts.created_at as postCreated,
                              users.created_at as userCreated from posts 
                              inner join users on posts.user_id=users.id order by posts.created_at desc');

        return $this->db->resultSet();
    }
    public function addPost($data){
        $this->db->query('insert into posts(title,user_id,body) values (:title,:user_id,:body)');
        $this->db->bind(':title',$data['title']);
        $this->db->bind(':user_id',$data['user_id']);
        $this->db->bind(':body',$data['body']);

        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }
    public function getPostById($id){
        $this->db->query('select * from posts where id =:id');
        $this->db->bind(':id',$id);

        $row =$this->db->single();

        return $row;
    }
    public function updatePost($data){
        $this->db->query('update posts set title =:title,body =:body where id =:id');
        $this->db->bind(':id',$data['id']);
        $this->db->bind(':title',$data['title']);
        $this->db->bind(':body',$data['body']);

        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }
    public function deletePost($id){
        $this->db->query('delete from posts where id =:id');
        $this->db->bind(':id',$id);

        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }
}