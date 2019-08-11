<?php

class PagesController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Page();
    }

    public function index()
    {
        if( $_POST ){
            $result = $this->model->savePost($_POST);
        }
        $this->data['pages'] = $this->model->getList();
    }

    public function view()
    {
        if( $_POST ){
            $result = $this->model->saveComment($_POST['post_id'], $_POST['name'], $_POST['comment']);
        }

        $params = App::getRouter()->getParams();

        if ( isset( $params[0] ) ){
            $alias = strtolower($params[0]);
            $this->data['page'] = $this->model->getByAlias($alias);
            $views = $this->model->getMutchViews($alias);
            $this->data['page']['redis'] = $views;
            $this->model->setMutchViews($alias, $views);
            $this->data['page']['comments'] = $this->model->getListCommentsByPostId($this->data['page']['id']);
        }
    }

    public function admin_index()
    {
        $this->data['pages'] = $this->model->getList();
    }

    public function admin_add()
    {
        if ( $_POST ){
            $result = $this->model->save($_POST, $_FILES);
            if ( $result ){
                Session::setFlash("Page was saved!");
            } else {
                Session::setFlash("Error");
            }
            Router::redirect("/admin/pages/");
        }
    }

    public function admin_edit()
    {
        if ( $_POST ){
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->save($_POST, $_FILES, $id);
            if ( $result ){
                Session::setFlash("Page was saved!");
            } else {
                Session::setFlash("Error");
            }
            Router::redirect("/admin/pages/");
        }

        if ( isset($this->params[0]) ){
            $this->data['page'] = $this->model->getById($this->params[0]);
        } else {
            Session::setFlash("Wrong page id.");
            Router::redirect('/admin/pages/');
        }
    }

    public function admin_delete()
    {
        if ( isset($this->params[0]) ){
            $result = $this->model->delete($this->params[0]);
            if ( $result ){
                Session::setFlash("Page was saved!");
            } else {
                Session::setFlash("Error");
            }
        }
        Router::redirect("/admin/pages/");
    }
}