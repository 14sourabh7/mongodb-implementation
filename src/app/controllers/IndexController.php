<?php

use Phalcon\Mvc\Controller;


class IndexController extends Controller
{
    public function indexAction()
    {
        $this->view->product = [];
        if ($this->request->get('search')) {
            $products = $this->dbHelper->searchProductByName($this->request->get('search'));
        } else {
            $products = $this->dbHelper->getAllProducts();
        }

        $this->view->products = $products;



        if ($this->request->getPost('btn') == 'update') {
            $this->dbHelper->updateProduct($this->request->getPost());
        } else if ($this->request->getPost('btn') == 'delete') {
            $this->dbHelper->deleteProduct($this->request->getPost('id'));
            $this->response->redirect();
        }
    }
    public function addproductAction()
    {
        $check = $this->request->isPost();
        echo '<pre>';

        if ($check) {
            $this->dbHelper->addProduct($this->request->getPost());
            $this->response->redirect('/');
        }
    }

    public function viewproductAction()
    {
        $id = $this->request->getPost('id');
        if ($id) {
            $product =  $this->dbHelper->getProduct($id);
        } else {
            $this->response->redirect('/');
        }
        return json_encode($product);;
    }
}
