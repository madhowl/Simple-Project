<?php
session_start();
// Проверяем, пусты ли переменные логина и id пользователя
/*if (empty($_SESSION['login']) or empty($_SESSION['id']))
{
    // Если пусты, то мы не выводим ссылку
    echo "Вы вошли на сайт, как гость<br><a href='#'>Эта ссылка  доступна только зарегистрированным пользователям</a>";
}
else
{

    // Если не пусты, то мы выводим ссылку
    echo "Вы вошли на сайт, как ".$_SESSION['login']."<br><a  href='http://tvpavlovsk.sk6.ru/'>Эта ссылка доступна только  зарегистрированным пользователям</a>";
}*/
require_once ('./app/func/func.php');
include_once ('./inc/head.php');
main();
//showLoginForm();

//userAdd();
//userLogin();
include_once ('./inc/foot.php');