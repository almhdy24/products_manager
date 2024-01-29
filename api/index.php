<?php

use Rakit\Validation\Validator;
use Bramus\Router\Router;
use ReallySimpleJWT\Token;
use App\SQLiteConnection;
use App\Products;
use App\Config;
use App\Users;


require "vendor/autoload.php";
// set headers
header("Content-type: application/json");

// Create db instance
$db = (new SQLiteConnection)->connect();

// Create Validator instance
$validator = new Validator;

// Create Database instance
//$database = new Database($db);

// Create product instance
$product = new Products($db);

// Create user instance
$user = new Users($db);

// Create Router instance
$router = new Router();

// routes
$router->get(/**
  * @return mixed
  */ "/fetchAll", function () use ($product, $validator) {
    // make it
    $validation = $validator->validate($_GET, [
      'token' => 'required',
    ]);
    if ($validation->fails()) {
      // handling errors
      $errors = $validation->errors();
      echo json_encode($errors->firstOfAll());
      exit;
    } else {
      // validation passes
      $token = $_GET['token'];
      $secret = Config::Secret_Key;
      $result = Token::validate($token, $secret);
      if (!$result) {
        echo json_encode(["msg" => "bad token"]);
        return;
      }
      echo $product->fetchAll();
    }
  });
$router->get(/**
  * @return mixed
  * @return product  array
  */ "/fetch/", function () use ($product, $validator) {
    // make it
    $validation = $validator->validate($_GET, [
      'token' => 'required',
      'id' => 'required|numeric',
    ]);
    if ($validation->fails()) {
      // handling errors
      $errors = $validation->errors();
      echo json_encode($errors->firstOfAll());
      exit;
    } else {
      // validation passes
      $token = $_GET['token'];
      $secret = Config::Secret_Key;
      $result = Token::validate($token, $secret);
      if (!$result) {
        echo json_encode(["msg" => "bad token"]);
        return;
      }
      echo $product->fetch($_GET['id']);
    }});
$router->get(/**
  * @return mixed
  */ "/delete", function () use ($product, $validator) {
    // make it
    $validation = $validator->validate($_GET, [
      'token' => 'required',
      'id' => 'required|numeric',
    ]);
    if ($validation->fails()) {
      // handling errors
      $errors = $validation->errors();
      echo json_encode($errors->firstOfAll());
      exit;
    } else {
      // validation passes
      $token = $_GET['token'];
      $secret = Config::Secret_Key;
      $result = Token::validate($token, $secret);
      if (!$result) {
        echo json_encode(["msg" => "bad token"]);
        return;
      }
      echo $product->delete($_GET['id']);
    }
  });
$router->post('/insert', function () use ($product, $validator) {
  // make it
  $validation = $validator->validate($_POST, [
    'name' => 'required',
    'price' => 'required|numeric',
    'amount' => 'required|numeric',
    'token' => 'required',
  ]);
  if ($validation->fails()) {
    // handling errors
    $errors = $validation->errors();
    echo json_encode($errors->firstOfAll());
    exit;
  } else {
    // validation passes
    $token = $_POST['token'];
    $secret = Config::Secret_Key; '';
    $result = Token::validate($token, $secret);
    if (!$result) {
      echo json_encode(["msg" => "bad token"]);
      return;
    }
    echo $product->insert($_POST);
  }
});
$router->post('/update', function () use ($product, $validator) {
  // make it
  $validation = $validator->validate($_POST, [
    'id' => 'required',
    'name' => 'required',
    'price' => 'required|numeric',
    'amount' => 'required|numeric',
  ]);
  if ($validation->fails()) {
    // handling errors
    $errors = $validation->errors();
    echo json_encode($errors->firstOfAll());
    exit;
  } else {
    $token = $_GET['token'];
    $secret = Config::Secret_Key;
    $result = Token::validate($token, $secret);
    if (!$result) {
      echo json_encode(["msg" => "bad token"]);
      return;
    }
    echo $product->update($_POST, $_POST['id']);
  }
});

$router->get('/search/', function () use ($product, $validator) {
  // make it
  $validation = $validator->validate($_GET, [
    'query' => 'required',
    'token' => 'required'
  ]);
  if ($validation->fails()) {
    // handling errors
    $errors = $validation->errors();
    echo json_encode($errors->firstOfAll());
    exit;
  } else {
    $token = $_GET['token'];
    $secret = Config::Secret_Key;
    $result = Token::validate($token, $secret);
    if (!$result) {
      echo json_encode(["msg" => "bad token"]);
      return;
    }
    echo $product->search($_GET['query']);
  }
});

$router->post('/login', function () use ($user, $validator) {
  // make it
  $validation = $validator->validate($_POST, [
    'email' => 'required|email',
    'password' => 'required',
  ]);
  if ($validation->fails()) {
    // handling errors
    $errors = $validation->errors();
    echo json_encode($errors->firstOfAll());
    exit;
  } else {
    // validation passes
    $data = $user->fetch_by_email($_POST['email']);
    if (!empty($data)) {
      if ($data[0]['password'] == $_POST['password']) {
        $payload = [
          'iat' => time(),
          'uid' => $data[0]['id'],
          'exp' => time() + 3600,
          'iss' => 'localhost'
        ];

        $secret = Config::Secret_Key; //'Hello&MikeFooBar123';

        $token = Token::customPayload($payload, $secret);
        echo json_encode(array("msg" => true, "token" => $token));
        exit;
      }
      echo json_encode(["msg" => false]);
    } else {
      echo json_encode(["msg" => false]);
    }
  }
});
$router->get('/db/', function () {
  //echo $database->backup();

});
$router->post('/signup', function() use($user, $validator) {
  // make it
  $validation = $validator->validate($_POST, [
    'username' => 'required',
    'email' => 'required|email',
    'password' => 'required',
  ]);
  if ($validation->fails()) {
    // handling errors
    $errors = $validation->errors();
    echo json_encode($errors->firstOfAll());
    exit;
  } else {
    if(!isset($_POST['plan'])){
      $_POST["plan"] = "free";
    }
    // validation passes
    $data =json_decode($user->insert($_POST),true);
        $payload = [
          'iat' => time(),
          'uid' => md5(random_int(111,999)),
          'exp' => time() + 3600,
          'iss' => 'localhost'
        ];

        $secret = Config::Secret_Key; //'Hello&MikeFooBar123';

        $token = Token::customPayload($payload, $secret);
  }
  $data["token"] = $token;
  $data["msg"] = true;
  echo json_encode($data);
});
$router->set404(/**
  *
  */ '/(/.*)?', function () {
    header('HTTP/1.1 404 Not Found');

    $jsonArray = array();
    $jsonArray['status'] = "404";
    $jsonArray['status_text'] = "route not defined";

    echo json_encode($jsonArray);
  });
$router->run();