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

        //handling delete  and update request
        if ($this->request->getPost('btn') == 'update') {

            $this->dbHelper->updateProduct($this->request->getPost());
        } else if ($this->request->getPost('btn') == 'delete') {

            $this->dbHelper->deleteProduct($this->request->getPost('id'));
            $this->response->redirect();
        }
    }


    /**
     * addproductAction()
     * 
     * function to add product in database
     *
     * @return void
     */
    public function addproductAction()
    {
        $check = $this->request->isPost();
        if ($check) {

            $this->dbHelper->addProduct($this->request->getPost());
            $this->response->redirect('/');
        }
    }


    /**
     * viewproductAction()
     *
     * function returning single product data to a ajax request
     * 
     * @return json
     */
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
