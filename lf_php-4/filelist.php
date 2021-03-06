<?php
require_once('php/cookie.php');
// Выход
if (isset($_GET['out'])) {
    header('Location: index.php');
}
/* Удаление пользователя
========================*/
if($_GET['path']) {
    $path = $_GET['path'];
    // Поиск в БД id и удаление фото
    $del_file = "SELECT photo FROM users WHERE (photo = '$path')";
    $res = mysqli_query($connection, $del_file) or die('Ошибка поиска записи: ' . mysqli_error($connection));
    $res_path = mysqli_fetch_assoc($res);
    unlink($path);
    // Удаление строки из БД
    $del_users = "UPDATE users SET photo = '' WHERE (photo = '$path')"; /*"DELETE photo FROM users WHERE (photo = '$path')";*/
    mysqli_query($connection, $del_users) or die('Ошибка поиска записи: ' . mysqli_error($connection));
    unset($_GET['path']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Список файлов</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Project name</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <?php if ($_SESSION['auth'] === 1) {
                    echo '<li><a href="list.php">Список пользователей</a></li>';
                } ?>
                <?php if ($_SESSION['auth'] === 1) {
                    echo '<li class="active"><a href="filelist.php">Список файлов</a></li>';
                } ?>
                <?php if ($_SESSION['auth'] === 1) {
                    echo '<li><a href="lk.php">Личный кабинет</a></li>';
                } ?>
                <?php if ($_SESSION['auth'] === 1) {
                    echo '<li><a href="lk.php?out=out">Выход</a></li>';
                } ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">
    <h1>Запретная зона, доступ только авторизированному пользователю</h1>
    <h2>Информация выводится из списка файлов</h2>
    <table class="table table-bordered">
        <tr>
            <th>Название файла</th>
            <th>Фотография</th>
            <th>Действия</th>
        </tr>
        <?php
        // Подключаюсь к БД
        $connection = mysqli_connect('localhost', 'root', '', 'beloc_hw4', 3306);

        if (!$connection) {
            echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
            echo "Код ошибки error: " . mysqli_connect_error() . PHP_EOL;
            echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }

        // Поиск в БД на совпадение Логина
        $search_users = "SELECT photo FROM users";
        $res_users = mysqli_query($connection, $search_users) or die('Ошибка поиска записи: ' . mysqli_error($connection));
        $res = mysqli_fetch_all($res_users);

        // Установка кодировки
        mysqli_query($connection, 'SET NAMES "UTF-8"');

        foreach ($res as $key => $value) {
            $name_file = str_replace('./photos/', '', $value[0]);
            if($value[0]){
                echo "<tr>
                        <td>" . $name_file . "</td>
                        <td><img src='" . $value[0] . "' alt='img' width='200px'></td>
                        <td>
                            <a href='filelist.php?path=" . $value[0] . "'>Удалить аватарку пользователя</a>
                        </td>
                    </tr>";
            }
        }
        ?>
    </table>

</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/main.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
