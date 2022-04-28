<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;

final class OrderController extends Controller
{
    public function indexAction(): void
    {
        $this->view->locale = $this->locale;

        if ($this->request->get('statusfilter')) {
            $startdate = $this->request->get('start');
            $enddate = $this->request->get('end');

            if ($startdate && $enddate) {
                $orders = $this->dbHelper->orderByDate($startdate, $enddate, $this->request->get('statusfilter'));
            } else {
                $orders = $this->dbHelper->orderByDate(
                    $this->request->get('date'),
                    date('Y-m-d'),
                    $this->request->get('statusfilter')
                );
            }
        } else {
            //bydefault displaying all orders
            $orders = $this->dbHelper->getAll('orders');
        }
        $this->view->orders = $orders;
    }


    public function addorderAction(): void
    {
        $this->view->locale = $this->locale;
        if ($this->request->getPost()) {
            $data = $this->request->getPost();
            $data['date'] = date("Y-m-d");
            $this->dbHelper->addOrder($data);
            $this->response->redirect('/order');
        }
    }

    /**
     * getProductsAction()
     *
     * @return String
     */
    public function getproductsAction()
    {
        $products = $this->dbHelper->getAllProducts()->toArray();
        $products = json_encode($products);
        return str_replace('$oid', 'oid', $products);
    }


    public function updatestatusAction(): void
    {
        $data = $this->request->get();

        //calling dbhelper class
        $this->dbHelper->updateOrderStatus($data);

        $this->response->redirect('/order');
    }
}
