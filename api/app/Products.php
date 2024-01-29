<?php

namespace App;

class Products
{
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
    *
    */
    public function fetchAll() {
        $query = $this->db->prepare("SELECT *  FROM products");
        $query->execute();
        return json_encode($query->fetchAll());

    }

    public function fetch($id) {

        /** @var TYPE_NAME $id */
        $query = $this->db->prepare("SELECT *  FROM products WHERE id = ?");
        $query->execute(array($id));
        return json_encode($query->fetchAll());
    }
    public function delete($id) {
        // ckeck if id is int
        /* if (is_integer($id)) {
            return json_encode(array("msg" => "The parameter you passed is not integer"));
        } */
        /** @var TYPE_NAME $id */
        $query = $this->db->prepare("DELETE   FROM products WHERE id = '$id'");
        $query->execute();
        return json_encode(["msg" => "The Product was deleted successfully."]);
    }


    public function insert(array $data) {
        /** @var TYPE_NAME $id */
        $query = $this->db->prepare("INSERT INTO products (name,price,amount) VALUES (?,?,?)");
        $query->execute(array($data['name'], $data['price'], $data['amount']));
        return json_encode(["msg" => "The Product was inserted successfully."]);
    }

    public function update(array $data, int $id) {
        /** @var TYPE_NAME $id */
        $query = $this->db->prepare("UPDATE products SET name= ?, price= ? ,amount= ? WHERE id= ?");
        $query->execute(array($data['name'], $data['price'], $data['amount'], $id));
        return json_encode(["msg" => "The Product was updated successfully."]);
        //return json_encode($data);
    }

    public function search(string $search) {
        /** @var TYPE_NAME $id */
        $query = $this->db->prepare("SELECT * FROM products WHERE name like '%$search%'");
        $query->execute();
        return json_encode($query->fetchAll());
    }
}