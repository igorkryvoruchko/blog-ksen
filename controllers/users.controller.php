<?php

class UsersController extends Controller
{
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new User();
    }

    public function admin_login()
    {
        if ( $_POST && isset($_POST['login']) && isset($_POST['password']) ){
            $user = $this->model->getByLogin($_POST['login']);
            $hash = md5(Config::get('salt').$_POST['password']);
            if ( $user && $user['is_active'] && $hash == $user['password'] ){
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
            }
            Router::redirect('/admin/');
        }
    }

    public function admin_logout()
    {
        Session::destroy();
        Router::redirect('/admin/');
    }

    public function register()
    {
        if ($_POST && isset($_POST['first_name']) && isset($_POST['second_name']) && isset($_POST['email']) && isset($_POST['login'])
            && isset($_POST['password']) && $_POST['password'] == $_POST['check_password']){
            $this->model->registerUser($_POST);
            $user = $this->model->getByLogin($_POST['login']);
            if ( $user && $user['is_active'] ){
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
            }
            Router::redirect('/');
        }
    }

    public function login()
    {
        if ( $_POST && isset($_POST['login']) && isset($_POST['password']) ){
            $user = $this->model->getByLogin($_POST['login']);

            if ( $user && $user['is_active'] && password_verify($_POST['password'], $user['password'])){
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
                Router::redirect('/');
            } else {
                Router::redirect('/users/login/');
            }

        }
    }

    public function logout()
    {
        Session::destroy();
        Router::redirect('/');
    }
}