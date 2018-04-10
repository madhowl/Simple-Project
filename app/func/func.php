<?php
function showLoginForm()
{
    echo '<form method="post" action="" class="login">
    <p>
      <label for="login">Логин:</label>
      <input type="text" name="login" id="login" value="name@example.com">
    </p>

    <p>
      <label for="password">Пароль:</label>
      <input type="password" name="password" id="password" value="4815162342">
    </p>

    <p class="login-submit">
      <button type="submit" class="login-button">Войти</button>
    </p>

    <p class="forgot-password"><a href="index.html">Забыл пароль?</a></p>
  </form>';

}
function showRegForm()
{
    echo '<form method="post" action="" class="login">
    <p>
      <label for="login">Логин:</label>
      <input type="text" name="login" id="login" value="name@example.com">
    </p>

    <p>
      <label for="password">Пароль:</label>
      <input type="password" name="password" id="password" value="4815162342">
    </p>

    <p class="login-submit">
      <button type="submit" class="login-button">Зарегистрироваться</button>
    </p>

    <p class="forgot-password"><a href="index.html">Забыл пароль?</a></p>
  </form>';
}
function userLogout()
{
    session_destroy();
}

function main()
{
    if (isset($_GET['act']))
    {
        $action=stripcslashes( htmlspecialchars($_GET['act']));
        switch ($action)
        {
            case 'login': showLoginForm();
                include_once ('./inc/login.php');
            break;
            case 'logout': userLogout();
                include_once ('./inc/login.php');

            default : include_once ('./inc/login.php');
        }
    }
    else include_once ('./inc/login.php');
}


/**
 * @return mysqli
 */
function dbConnect()
{
    $mysqli = new mysqli('localhost', 'root', 'secret', 'db-test');
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }
    return $mysqli;

}

/**
 *
 */
function userAdd()
{
    if (isset($_POST['login'])) {
        $login = $_POST['login'];
        if ($login == '') {
            unset($login);
        }
    } //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        if ($password == '') {
            unset($password);
        }
    }
    //заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
    if (empty($login) or empty($password)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
    {
        exit ("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
    }
    //если логин и пароль введены, то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);
    //удаляем лишние пробелы
    $login = trim($login);
    $password = trim($password);
    // подключаемся к базе
    $db = dbConnect();
    $result =mysqli_query($db,"SELECT id FROM users WHERE login='$login'");

    // проверка на существование пользователя с таким же логином
    //$result = mysql_query("SELECT id FROM users WHERE login='$login'", $db);
    $myrow = mysqli_fetch_array($result);
    if (!empty($myrow['id'])) {
        exit ("Извините, введённый вами логин уже зарегистрирован. Введите другой логин.");
    }
    // если такого нет, то сохраняем данные
    $result2 = mysqli_query($db,"INSERT INTO users (login,password) VALUES('$login','$password')");
    // Проверяем, есть ли ошибки
    if ($result2 == 'TRUE') {
        echo "Вы успешно зарегистрированы! Теперь вы можете зайти на сайт. <a href='index.php'>Главная страница</a>";
    } else {
        echo "Ошибка! Вы не зарегистрированы.";
    }
}
function userLogin()
{
    if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} } //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
    if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }
    //заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
    if (empty($login) or empty($password)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
    {
        exit ("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
    }
    //если логин и пароль введены,то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);
//удаляем лишние пробелы
    $login = trim($login);
    $password = trim($password);
// подключаемся к базе
    $db = dbConnect();
    $result = mysqli_query($db,"SELECT * FROM users WHERE login='$login'"); //извлекаем из базы все данные о пользователе с введенным логином
    $myrow = mysqli_fetch_array($result);
    if (empty($myrow['password']))
    {
        //если пользователя с введенным логином не существует
        exit ("Извините, введённый вами login или пароль неверный.");
    }
    else {
        //если существует, то сверяем пароли
        if ($myrow['password']==$password) {
            //если пароли совпадают, то запускаем пользователю сессию! Можете его поздравить, он вошел!
            $_SESSION['login']=$myrow['login'];
            $_SESSION['id']=$myrow['id'];//эти данные очень часто используются, вот их и будет "носить с собой" вошедший пользователь
            echo "Вы успешно вошли на сайт! <a href='index.php'>Главная страница</a>";
        }
        else {
            //если пароли не сошлись

            exit ("Извините, введённый вами login или пароль неверный.");
        }
    }
}