<?php

declare(strict_types=1);

//db queries
namespace App\Components;

use Phalcon\Di\Injectable;

final class MongoHelper extends Injectable
{

    /**
     * createID($id)
     * 
     * function to create  \MongoDB\BSON\ObjectID($id)
     *
     * @param String $id
     * @return object
     */
    private function createID($id)
    {
        return new \MongoDB\BSON\ObjectID($id);
    }

    /**
     * getAll($document)
     * 
     * class function to find all from document
     *
     * @param String $document
     * @return array
     */
    private function getAll($document)
    {
        return $this->mongo->store->$document->find();
    }


    private function getSingle($document, $id)
    {
        return $this->mongo->store->$document->findOne([
            '_id' => $this->createID($id)
        ]);
    }


    /**
     * searchName($document,$name)
     * 
     * class function to search in db
     *
     * @param String $document
     * @param String $name
     * @return object
     */
    private function searchName($document, $name)
    {
        return $this->mongo->store->$document->find(['name' => $name]);
    }

    /**
     * addData($data)
     * 
     * class function to add data 
     *
     * @param Array $data
     * @return Void
     */
    private function addData($document, $data)
    {
        $this->mongo->store->$document->insertOne($data);
    }

    /**
     * updateData($id,$data)
     * 
     * class function to update data
     *
     * @param String $document
     * @param Array $data
     * @return Void
     */
    private function updateData($document, $data)
    {
        $this->mongo->store->$document->updateOne(
            [

                '_id' => $this->createID($data['id'])
            ],
            [
                '$set' => $data
            ]
        );
    }

    /**
     * deleteData($document,$id)
     * 
     * function to delete data
     *
     * @param String $document
     * @param String $id
     * @return Void
     */
    private function deleteData($document, $id)
    {
        $this->mongo->store->$document->deleteOne(
            [
                '_id' => $this->createID($id)
            ]
        );
    }


    /**
     * function to filter data by date only
     *
     * @param String $document
     * @param String $start 
     * @param String $end
     * @return array
     */
    private function getDataByDate($document, $start, $end)
    {
        return $this->mongo->store->$document->find(['date' => ['$gte' => $start, '$lte' => $end]]);
    }

    /**
     * function to flter data by date and status
     *
     * @param String $start
     * @param String $end
     * @param String $statusfilter
     * @return array
     */
    private function getDataByfilterDate($start, $end, $statusfilter)
    {
        return $this->mongo->store->orders->find(['date' => ['$gte' => $start, '$lte' => $end], 'status' => $statusfilter]);
    }



    /**
     * public functions for products
     */

    public function getAllProducts()
    {
        return $this->getAll('products');
    }

    public function getProduct($id)
    {
        return $this->getSingle('products', $id);
    }

    public function searchProductByName($name)
    {
        return $this->searchName('products', $name);
    }

    public function addProduct($product): void
    {
        $this->addData('products', $product);
    }

    public function updateProduct($data): void
    {
        $this->updateData('products', $data);
    }

    public function deleteProduct($id): void
    {
        $this->deleteData('products', $id);
    }



    /**
     * public functions for orders
     */

    public function addOrder($data): void
    {
        $this->addData('orders', $data);
        $quantity = $data['quantity'];
        $stock = $this->getSingle('products', $data['product_id'])->stock - $quantity;
        $this->updateData('products', ['id' => $data['product_id'], 'stock' => $stock]);
    }
    public function searchOrderByName($name)
    {
        return $this->searchName('orders', $name);
    }

    public function updateOrderStatus($data): void
    {
        $this->updateData('orders', $data);
    }


    /**
     * orderByDate($start, $end, $statusFilter)
     *
     * function to order by date
     * 
     * @param String $start
     * @param String $end
     * @param String $statusfilter
     * @return Array
     */
    public function orderByDate($start, $end, $statusfilter)
    {
        if ($statusfilter === 'all') {
            return $this->getDataByDate('orders', $start, $end);
        }
        return $this->getDataByfilterDate($start, $end, $statusfilter);
    }
}
