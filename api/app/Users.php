<?php

namespace App;

class Users
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     *
     */
    public function fetchAll()
    {
        $query = $this->db->prepare("SELECT *  FROM users");
        $query->execute();
        return json_encode($query->fetchAll());

    }

    public function fetch($id)
    {
        /** @var TYPE_NAME $id */
        $query = $this->db->prepare("SELECT *  FROM Users WHERE id = ?");
        $query->execute(array($id));
        return json_encode($query->fetchAll());
    }
    public function fetch_by_email($email)
    {
        /** @var TYPE_NAME $email*/
        $query = $this->db->prepare("SELECT *  FROM Users WHERE email = ?");
        $query->execute(array($email));
        return $query->fetchAll();
    }
    public function delete($id)
    {
        /** @var TYPE_NAME $id */
        $query = $this->db->prepare("DELETE   FROM users WHERE id = '$id'");
        $query->execute();
        return json_encode(["msg" => "The User was deleted successfully."]);
    }


    public function insert(array $data)
    {
        /** @var TYPE_NAME $id */
        $query = $this->db->prepare("INSERT INTO users (username,email,password,plan) VALUES (?,?,?,?)");
        $query->execute(array($data['username'],$data['email'],$data['password'],$data['plan']));
        return json_encode(["msgText" => "The user  account was created successfully."]);
    }
}