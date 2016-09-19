<?php
require_once 'vendor/autoload.php';

use Intervention\Image\ImageManagerStatic as Imageres;
/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 14.09.16
 * Time: 18:26
 */
class User
{

    /**
     * Удаляет сессию
     */
    public function logoutUser()
    {
        unset($_SESSION);
        session_destroy();
        return true;
    }

    /**
     * DB_Operate constructor.
     * @param $host
     * @param $login
     * @param $password
     * @param $database
     */
    function __construct()
    {
        $a = new Connection();
        $this->db_host  =  $a->host;
        $this->db_login = $a->user;
        $this->db_pass  = $a->password;
        $this->db_name  = $a->dbace;
    }

    /**
     * ПРоверяет есть ли база, если нет создает
     */
    public function dbChekMake()
    {
        $mysqli = new mysqli($this->db_host, $this->db_login, $this->db_pass);
        if ($mysqli->connect_errno) {
            exit('Не удалось подключиться к MySQL: ' . $mysqli->connect_error);
        } else {
            $db = new mysqli(
                $this->db_host,
                $this->db_login,
                $this->db_pass,
                $this->db_name
            );
            if ($db->connect_errno) {
                $mysqli->query('CREATE DATABASE IF NOT EXISTS $this->db_name');
                $mysqli->select_db($this->db_name);
                $mysqli->query(
                    "CREATE TABLE users (
                             id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                             username VARCHAR(50),
                             password VARCHAR(50),
                             name VARCHAR(50),
                             age INT (3),
                             about VARCHAR(1000),
                             avatar VARCHAR(50)
                             )
                             DEFAULT CHARSET = utf8 
                             COLLATE=utf8_unicode_ci 
                             AUTO_INCREMENT = 1"
                );
                $mysqli->query(
                    "CREATE TABLE photos (
                            id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
                            user_id INT UNSIGNED,
                            file VARCHAR(200)
                        )
                        DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1"
                );
            }
        }
    }

    /**
     * Запрос всех данных о пользователе из базы
     * @return mixed
     */
    public function getInfo()
    {
        $a = new Connection();
        $db = new mysqli($a->host, $a->user, $a->password, $a->dbace);
        if (!$db->set_charset('utf8')) {
            printf('Ошибка при загрузке набора символов utf8: %s\n', $db->error);
        }

        if ($db->connect_errno) {
            exit("ошибка подключения");
        }

        $idses = $_SESSION ['id'];
        $res = $db->query(
            "select * from users where id = '$idses'"
        );

        $record = $res->fetch_assoc();

        $_SESSION ['username'] = $record ['username'];
        $_SESSION ['password'] = $record ['password'];
        $_SESSION ['name']     = $record ['name'];
        $_SESSION ['age']      = $record ['age'];
        $_SESSION ['about']    = $record ['about'];
        $_SESSION ['avatar']   = $record ['avatar'];
        return $_SESSION;
    }

    /**
     * Function adds lofin and password from registration form
     */
    public function addLoginPassword()
    {
        $this->dbChekMake();
        if (empty($_POST["g-recaptcha-response"])) {
            echo 'Подтвердите что вы человек';
            return false;
        } else {
            $user_login     = $_POST['registerLogin'];
            $user_password  = $_POST['registerPassword'];
            $user_password2 = $_POST['registerConfirm'];
            if ($user_password == $user_password2) {
                if (isset($user_login) || isset($user_password)) {
                    if (empty($user_login) || empty($user_password)) {
                        echo 'Данные введены неверно';
                        return false;
                    } else {
                        $user_login= strip_tags($user_login);
                        $a = new Connection();

                        $result = $a->connect()->query(
                            "select * from users
                        where username = '$user_login' LIMIT 0,1"
                        );
                        $record = $result->fetch_assoc();
                        if (!empty($record)) {
                            echo 'Такой пользователь уже существует';
                            return false;
                        } else {
                            $a->connect()->query(
                                "INSERT INTO `users` (username, password) 
                            VALUES ('$user_login', '$user_password')"
                            );

                            $res = $a->connect()->query(
                                "select id from users where username = '$user_login' "
                            );
                            $record = $res->fetch_assoc();
                            $_SESSION ['id'] = $record ['id'];
                            $this->getInfo();

                            return true;
                        }
                    }
                }
            } else {
                echo 'Пароли не совпадают';
                return false;
            }
        }
    }

    /**
     * Function adds data about user to date base
     */
    public function addNameAgeAbout()
    {
        $arr = '';
        $id  = $_SESSION['id'];

        if (empty($_POST['Username']) || empty($_POST['Password'])) {
            echo 'Логин и пароль не могут быть пустыми';
        } else {
            $arr['username'] = strip_tags($_POST['Username']);
            $arr['password'] = strip_tags($_POST['Password']);
            $arr['name']     = strip_tags($_POST['Name']);
            $arr['age']      = strip_tags($_POST['Age']);
            $arr['about']    = strip_tags($_POST['About']);
            $a = new Connection();
            $db = new mysqli($a->host, $a->user, $a->password, $a->dbace);
            if (!$db->set_charset('utf8')) {
                printf(
                    'Ошибка при загрузке набора символов utf8: %s\n',
                    $db->error
                );
            }

            if ($db->connect_errno) {
                echo 'ошибка подключения к БР';
            } else {
                $sql = " UPDATE users
                          SET username = ?, 
                          password = ?, 
                          name = ?, 
                          age = ?, 
                          about = ? 
                          WHERE id = ?";
                if ($stmt = $db->prepare($sql)) {
                    $stmt->bind_param(
                        'sssisi',
                        $arr['username'],
                        $arr['password'],
                        $arr['name'],
                        $arr['age'],
                        $arr['about'],
                        $id
                    );
                    $stmt->execute();
                    $this->getInfo();
                    return true;
                }
            }
        }
    }

    /**
     * Проверяет пользователя и пароль

     */
    public function checkLoginPassword()
    {
        $this->dbChekMake();
        if (empty($_POST["g-recaptcha-response"])) {

            echo 'Подтвердите что вы человек';
            return false;
        } else {
            if (isset($_POST['usernameLogin']) || isset($_POST['passwordLogin'])) {
                if (empty($_POST['usernameLogin'])
                    || empty($_POST['passwordLogin'])
                ) {
                    echo 'Проерьте ведденые данные или пройдите регистрацию';
                } else {
                    $user_login    = $_POST['usernameLogin'];
                    $user_password = $_POST['passwordLogin'];
                    $a = new Connection();
                    $result = $a->connect()->query(
                        "select * from users where username = '$user_login' LIMIT 0,1"
                    );
                    $record = $result->fetch_assoc();

                    if (isset($result) && $record ['password'] == $user_password) {
                        echo 1;
                        $res = $a->connect()->query(
                            "select id from users where username = '$user_login' "
                        );
                        $record = $res->fetch_assoc();
                        $_SESSION ['id'] = $record ['id'];
                        $this->getInfo();
                        return true;
                    } else {
                        echo ' Что-то пошло не так, может нужно зарегистрироваться';
                        return false;
                    }
                }
            }
        }
    }

    /**
     * Добавить картику в хранилище
     * @return bool
     */
    public function addPicture()
    {
        if (file_exists('./img/photos') === false) {
            mkdir('./img', 0777);
            mkdir('./img/photos', 0777);
        }

        $id = $_SESSION['id'];

        $img = $_FILES;
        $target_dir = 'img/photos/';
        $target_file = $target_dir . basename($img['img']['name']);
        $status = true;
        $imageType = pathinfo($target_file, PATHINFO_EXTENSION);
        if ($target_file == $target_dir) {
            echo 'Вы не выбрали файл ';
        } else {
            $check = getimagesize($img['img']['tmp_name']);
            if ($check !== false) {
            } else {
                echo 'Это не картинка ';
                $status = false;
            }
            if (file_exists($target_file)) {
                echo "Такой файл уже есть <br><br>";
                $status = false;
            }

            if ($imageType != "jpg"
                && $imageType != "png"
                && $imageType != "jpeg"
                && $imageType != "gif"
                && $imageType != "JPG"
            ) {
                echo "Вы можете загрузить только картинки <br> ";
                $status = false;
            }

            if ($status) {
                if (move_uploaded_file(
                    $img['img']['tmp_name'],
                    $target_file
                )
                ) {
                    $imge = Imageres::make($target_file)
                        ->resize($check[0], $check[1])->save($target_file);

                    echo
                        "Файл "
                        . basename($img['img']['name'])
                        . " был загружен <br><br>";

                    $a = new Connection();
                    $db = new mysqli(
                        $a->host,
                        $a->user,
                        $a->password,
                        $a->dbace
                    );
                    if ($db->connect_errno) {
                        exit("ошибка подключения к БД, повторите запрос");
                    }
                    if (!$db->set_charset("utf8")) {
                        printf(
                            "Ошибка при загрузке набора символов utf8: %s\n",
                            $db->error
                        );
                    }

                    $sql = "INSERT INTO photos (user_id, file) VALUES(?, ?)";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param('is', $id, $img['img']['name']);
                    $stmt->execute();
                    $stmt->close();
                    $db->close();
                    return true;
                }
            }
        }
    }

    /**
     * Добавить аватарку
     * @return bool
     */
    public function addAvatar()
    {
        if (file_exists('/img/avatars') === false) {//строгое сравнение
            mkdir('./img', 0777);
            mkdir('./img/avatars', 0777);
            //здесь не создается папка - поправил
        }

        $img         = $_FILES;
        $target_dir  = 'img/avatars/';
        $filename    = basename($img['img']['name']);
        $target_file = $target_dir . $filename;
        $status      = true;
        $imageType   = pathinfo($target_file, PATHINFO_EXTENSION);
        $id          = $_SESSION ['id'];
        if ($target_file == $target_dir) {
            echo 'Вы не выбрали файл ';//двойные кавычки - поправил
        } else {
            $check = getimagesize($img['img']['tmp_name']);//двойные кавычки - поправил
            if ($check !== false) {
            } else {
                echo 'Это не картинка';
                $status = false;
            }
            if (file_exists($target_file)) {
                echo 'Такой файл уже есть <br><br>';
                $status = false;
            }

            if ($imageType != 'jpg'
                && $imageType != 'png'
                && $imageType != 'jpeg'
                && $imageType != 'gif'
                && $imageType != 'JPG'
            ) {
                echo 'Вы можете загрузить только картинки <br> ';
                $status = false;
            }

            if ($status) {
                if (move_uploaded_file(
                    $img['img']['tmp_name'],
                    $target_file
                )
                ) {
                    $imge = Imageres::make($target_file)
                        ->resize(200, 200)->save($target_file);

                    echo
                        'Файл '
                        . basename($img['img']['name'])
                        . ' был загружен <br><br>';

                    $a = new Connection();
                    $db = new mysqli(
                        $a->host,
                        $a->user,
                        $a->password,
                        $a->dbace
                    );
                    if ($db->connect_errno) {
                        exit('ошибка подключения к БД, повторите запрос');
                    }
                    if (!$db->set_charset('utf8')) {
                        printf(
                            'Ошибка при загрузке набора символов utf8: %s\n',
                            $db->error
                        );
                    }

                    $sql = 'UPDATE users
                          SET avatar = ?
                          WHERE id = ?';
                    if ($stmt = $db->prepare($sql)) {
                        $stmt->bind_param(
                            'si',
                            $filename,
                            $id
                        );
                        $stmt->execute();
                        $this->getInfo();
                        return true;
                    } else {
                        echo 'не получилось';
                    }
                }
            }
        }
    }

    /**
     * Вывод всех фото пользователя
     * @return bool
     */
    public function getAllPhoto()
    {
        $id = $_SESSION['id'];
        $a = new Connection();
        $res = $a -> connect()->query(
            "select file from photos where user_id = '$id'"
        );

        $record = $res->fetch_all();
        foreach ($record as $item) {
            foreach ($item as $item) {
                echo $item;
                echo '<br>';
            }

        }

        return true;

    }

    /**
     * Вывод всех польщователей из базы
     * @return bool
     */
    public function getAllUser()
    {
        $a = new Connection();
        $res = $a -> connect()->query(
        "select name,age from users ORDER BY age"
    );

        $record = $res->fetch_all();
        foreach ($record as $item) {
            echo '<br>';
            foreach ($item as $key=>$value) {
                if ($key == 0 ) {
                    echo 'Имя: ', $value, ',  ';
                };
                if ($key == 1 ) {
                    echo 'Возраст: ', $value, ' - ';
                    switch ($value) {
                        case null:
                            echo 'Возраст не заведен';
                            break;
                        case ($value >= '18'):
                            echo 'совершеннолетний';
                            break;
                        default:
                            echo 'несовершеннолетний';
                    }
                }
            }
        }

        return true;
    }

    /**
     * Удаляет пользователя
     */
    public function deletUser()
    {
        $userid = $_SESSION ['id'];
        $a = new Connection();
        $a->connect()->query("DELETE FROM `users` WHERE 'id' = $userid");
        unset($_SESSION['id']);
        session_destroy();
        return true;

    }
}




