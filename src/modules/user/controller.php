<?php 

require 'model.php';

class UserController {
    public $user;

    function __construct() {
        $this->user = new UserModel();
    }

    private function grant_session() {
        $_SESSION['user_id'] = $this->user->id;
    }

    function check_auth($redirect = true) {
        if ($id = session('user_id')) {
            $this->user->get_byid($id);
            return true;
        } else {
            if ($redirect) redirect('user/signin');
        }
    }

    function signin() {
        if (!is_post()) {
            render('signin');
        }

        $username = post('username');
        $password = post('password');

        if (!$this->user->get($username) || !$this->user->check_password($password)) {
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
            $this->user->create($username, $email, $password);
        } catch(BadRequestException $e) {
            render('signup', array('error' => $e->getMessage()));
        }
        
        $this->grant_session();
        redirect('user/profile/' . $username);
    }

    function update() {
        $this->check_auth();

        $this->user->name = post('name');
        $this->user->email = post('email');
        if ($pw = post('password')) {
            $this->user->set_password($pw);
        }
        $this->user->update();
        if ($file = getfile('pfp')) {
            move_uploaded_file($file['tmp_name'], PROFILES_DIR . $this->user->id);
        }

        redirect('user/profile/' . $this->user->name);
    }

    function profile() {
        $user = new UserModel();
        if (!$user->get(uri(2))) {
            http_response_code(404);
            render('profilenotfound');
        }

        $this->check_auth(false);
        
        render('profile', (object) array(
            'perms' => $this->user->id == $user->id,
            'user' => $user
        ));
    }

    function pfp() {
        $user = new UserModel();
        $user->get_byid(uri(2));
        $path = PROFILES_DIR . $user->id;
        $mime_type = mime_content_type($path);
        header('Content-type: ' . $mime_type);
        readfile($path);
    }
};


?>