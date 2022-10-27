<?php 

require 'model.php';

class UserController {
    public $usermodel;

    function __construct() {
        $this->usermodel = new UserModel();
    }

    private function grant_session() {
        $_SESSION['user_id'] = $this->usermodel->id;
    }

    function check_auth() {
        if ($id = $_SESSION['user_id']) {
            $this->usermodel->get_byid($id);
            return true;
        } else {
            redirect('user/signin');
        }
    }

    function signin() {
        if (!is_post()) {
            render('signin');
        }

        $username = post('username');
        $password = post('password');

        if (!$this->usermodel->get($username) || !$this->usermodel->check_password($password)) {
            http_response_code(403);
            render('signin', array('error' => 'Invalid username or password'));
        }

        $this->grant_session();
        redirect('user/profile/' . $username);
    }

    function signup() {
        if (!is_post()) {
            render('signup');
        }

        $username = post('username');
        $email = post('email');
        $password = post('password');
    
        try {
            $this->usermodel->create($username, $email, $password);
        } catch(BadRequestException $e) {
            render('signup', array('error' => $e->getMessage()));
        }
        
        $this->grant_session();
        redirect('user/profile/' . $username);
    }

    function profile() {
        if (!$this->usermodel->get(uri(2))) {
            http_response_code(404);
            render('profilenotfound');
        }
        
        render('profile', $this->usermodel);
    }
};


?>