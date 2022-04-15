<?php

namespace App\Components;

use Phalcon\Di\Injectable;

class MongoHelper extends Injectable
{
    public function getAllProducts()
    {
        return
            $this->mongo->store->products->find();
    }
    public function getProduct($id)
    {
        return
            $this->mongo->store->products->findOne([
                '_id' => new \MongoDB\BSON\ObjectID($id)
            ]);
    }
    public function searchProductByName($name)
    {
        return
            $this->mongo->store->products->find(['name' => $name]);
    }

    public function addProduct($product)
    {
        $this->mongo->store->products->insertOne($product);
    }

    public function updateProduct($data)
    {
        $this->mongo->store->products->updateOne(
            [

                '_id' => new \MongoDB\BSON\ObjectID($data['id'])
            ],
            [
                '$set' => $data
            ]
        );
    }

    public function deleteProduct($id)
    {
        $this->mongo->store->products->deleteOne(
            [
                '_id' => new \MongoDB\BSON\ObjectID($id)
            ]
        );
    }
}
