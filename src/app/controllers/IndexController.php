<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;


final class IndexController extends Controller
{
    public function indexAction(): void
    {
        $this->view->product = [];
        $this->view->locale = $this->locale;
        if ($this->request->get('search')) {
            $products = $this->dbHelper->searchProductByName($this->request->get('search'));
        } else {
            $products = $this->dbHelper->getAllProducts();
        }

        $this->view->products = $products;

        //handling delete  and update request
        if ($this->request->getPost('btn') === 'update') {
            $func = $this->request->getPost('btn') . "Product";
            $this->dbHelper->$func($this->request->getPost());
            $this->response->redirect();
        }
    }



    public function addproductAction(): void
    {
        $check = $this->request->isPost();
        $this->view->locale = $this->locale;
        if ($check) {
            $this->dbHelper->addProduct($this->request->getPost());
            $this->response->redirect('/');
        }
    }



    public function viewproductAction()
    {

        $id = $this->request->getPost('id');
        if ($id) {
            return json_encode($this->dbHelper->getProduct($id));
        }
        $this->response->redirect('/');
    }
}
