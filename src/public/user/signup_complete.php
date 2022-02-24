<?php
require_once(__DIR__ . '/../../app/Infrastructure/Dao/UserDao.php');
require_once __DIR__ . '/../../app/Infrastructure/Redirect/redirect.php';
require_once __DIR__ . '/../../vendor/autoload.php';


$email = filter_input(INPUT_POST, 'email');
$name = filter_input(INPUT_POST, 'name');
$password = filter_input(INPUT_POST, 'password');
$confirmPassword = filter_input(INPUT_POST, 'confirmPassword');

session_start();
if (empty($password) || empty($confirmPassword)) $_SESSION['errors'][] = "パスワードを入力してください";
if ($password !== $confirmPassword) $_SESSION['errors'][] = "パスワードが一致しません";

if (!empty($_SESSION['errors'])) {
  $_SESSION['formInputs']['name'] = $name;
  $_SESSION['formInputs']['email'] = $email;
  redirect('./signup.php');
}

$userDao = new UserDao();
// メールアドレスに一致するユーザーの取得
$user = $userDao->findByEmail($email);

if (!is_null($user)) $_SESSION['errors'][] = "すでに登録済みのメールアドレスです";
if (!empty($_SESSION['errors'])) redirect('./signup.php');

// ユーザーの保存
$userDao->create($name, $email, $password);

$_SESSION['message'] = "登録できました。";
redirect('./signin.php');
