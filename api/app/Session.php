<?php

namespace App;

class Session
{
    public int $id;
    public string $data;
    public string $key;

    public function init() {
        session_start();
    }

    public function set($key, $data) {
        // sign data to key
        $_SESSION[$key] = $data;
        return true;
    }

    public function unset($key) {
        // unset session
        unset($_SESSION[$key]);
    }

    public function get($key = false) {
        // get session value
        if ($key == false) return $_SESSION;
          return $_SESSION[$key];
    }

}