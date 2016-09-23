<?php

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
     * Запрос всех данных о пользователе из базы
     * @return mixed
     */
    public function getInfo()
    {
        $a = new Conn();
        $db = new mysqli($a->host, $a->user, $a->password, $a->dbace);
        if (!$db->set_charset('utf8')) {
            printf('Ошибка при загрузке набора символов utf8: %s\n', $db->error);
        }

        if ($db->connect_errno) {
            exit("ошибка подключения");
        }

        $idses = $_SESSION ['id'];

        $res = Users::find($idses);
        $_SESSION ['username'] = $res->username;
        $_SESSION ['password'] = $res->password;
        $_SESSION ['name']     = $res->name;
        $_SESSION ['age']      = $res->age;
        $_SESSION ['about']    = $res->about;
        $_SESSION ['avatar']   = $res->avatar;

        return $_SESSION;
    }

    /**
     * Function adds lofin and password from registration form
     */
    public function addLoginPassword()
    {
        $a = new Conn();
        $a->dbChekMake();
        $gump = new GumpCheck();
        if ($gump->checkPostReg()) {
            $user_login     = $_POST['registerLogin'];
            $user_password  = $_POST['registerPassword'];
            $user_password2 = $_POST['registerConfirm'];
            $ip             = $_POST['ip'];
            $record = 27;
            if ($user_password == $user_password2) {
                if (isset($user_login) || isset($user_password)) {
                    if (empty($user_login) || empty($user_password)) {
                        echo 'Данные введены неверно';
                        return false;
                    } else {
                        $user_login    = strip_tags($user_login);
                        $user_password = strip_tags($user_password);

                        $res = Users::where('username', $user_login)->get();

                        foreach ($res as $item) {
                            $record = $item->username;
                        }

                        if ($record != 27) {
                            echo 'Такой пользователь уже существует';
                            return false;
                        } else {
                            $user = new Users();
                            $user->username = $user_login;
                            $user->password = $user_password;
                            $user->ip       = $ip;
                            $user->save();

                            $res = Users::where('username', $user_login)->get();
                            foreach ($res as $item) {
                                $_SESSION['id'] = $item->id;
                            }

                            $this->getInfo();
                            return true;
                        }
                    }
                }
            } else {
                echo 'Пароли не совпадают';
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Function adds data about user to date base
     */
    public function addNameAgeAbout()
    {
        $b = new CreateCategoryTable();
        $b->up();
        $c = new CreateGoodsTable();
        $c->up();
        $a = new GumpCheck();
        if ($a->checkPostData()) {
            $arr = '';
            $id  = $_SESSION['id'];

            if (empty($_POST['Username']) || empty($_POST['Password'])) {
                echo 'Логин и пароль не могут быть пустыми';
                return false;
            } else {
                $arr['username'] = strip_tags($_POST['Username']);
                $arr['password'] = strip_tags($_POST['Password']);
                $arr['name']     = strip_tags($_POST['Name']);
                $arr['age']      = strip_tags($_POST['Age']);
                $arr['about']    = strip_tags($_POST['About']);

                $user = Users::find($id);
                $user->username = $arr['username'];
                $user->password = $arr['password'];
                $user->name     = $arr['name'];
                $user->age      = $arr['age'];
                $user->about    = $arr['about'];
                $user->save();
                $this->getInfo();
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Проверяет пользователя и пароль

     */
    public function checkLoginPassword()
    {
        $a = new Conn();
        $a->dbChekMake();
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
                    $login = null;
                    $password = null;
                    $id = null;
                    $user_login    = $_POST['usernameLogin'];
                    $user_password = $_POST['passwordLogin'];

                    $users = Users::where('username', $user_login)->get()->toArray();
                    foreach ($users as $user) {
                        $login = $user['username'];
                        $password = $user['password'];
                        $id = $user['id'];
                    }

                    if ($login != null && $password == $user_password) {
                        $_SESSION ['id'] = $id;
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

        $img         = $_FILES;
        $target_dir  = 'img/photos/';
        $target_file = $target_dir . basename($img['img']['name']);
        $status = true;
        $imageType = pathinfo($target_file, PATHINFO_EXTENSION);
        if ($target_file == $target_dir) {
            echo 'Вы не выбрали файл ';
            return false;
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
                        ->resize(480, 480)->save($target_file);

                    echo
                        "Файл "
                        . basename($img['img']['name'])
                        . " был загружен <br><br>";


                    $photo = new Photos();
                    $photo->user_id = $id;
                    $photo->file    = $img['img']['name'];
                    $photo->save();
                    return true;
                }
                return false;
            }
        }
    }

    /**
     * Добавить аватарку
     * @return bool
     */
    public function addAvatar()
    {
        if (file_exists('/img/avatars') === false) {
            mkdir('./img', 0777);
            mkdir('./img/avatars', 0777);
        }

        $img         = $_FILES;
        $target_dir  = 'img/avatars/';
        $filename    = basename($img['img']['name']);
        $target_file = $target_dir . $filename;
        $status      = true;
        $imageType   = pathinfo($target_file, PATHINFO_EXTENSION);
        $id          = $_SESSION ['id'];
        if ($target_file == $target_dir) {
            echo 'Вы не выбрали файл ';
            return false;
        } else {
            $check = getimagesize($img['img']['tmp_name']);
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
                        ->resize(480, 480)->save($target_file);

                    echo
                        'Файл '
                        . basename($img['img']['name'])
                        . ' был загружен <br><br>';

                    $user = Users::find($id);
                    $user->avatar = $filename;
                    $user->save();
                    $this->getInfo();
                }
                return false;
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
        $photos = Photos::where('user_id', $id)->get()->toArray();
        foreach ($photos as $photo) {
            echo $photo['file'],'<br>';
        }
    }

    /**
     * Вывод всех польщователей из базы
     * @return bool
     */
    public function getAllUser()
    {
        $users = Users::orderBy('age')->get()->toArray();
        foreach ($users as $user) {
            echo 'Имя: ', $user['username'], ' Возраст: ', $user ['age'], ' - ';
            switch ($user['age']) {
                case null:
                    echo 'Возраст не заведен';
                    break;
                case ($user['age'] >= '18'):
                    echo 'совершеннолетний';
                    break;
                default:
                    echo 'несовершеннолетний';

            }
            echo '<br>';
        }
        return true;
    }

    /**
     * Удаляет пользователя
     */
    public function deletUser()
    {
        $userid = $_SESSION ['id'];
        $users  = Users::where('id', $userid)->delete();
        unset($_SESSION['id']);
        session_destroy();
        return true;
    }
}




