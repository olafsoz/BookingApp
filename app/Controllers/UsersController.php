<?php

namespace App\Controllers;

use App\Redirect;
use App\Database;
use App\View;

class UsersController
{
    public function start(): Redirect
    {
        session_unset();
        session_destroy();
        return new Redirect('users/login');
    }
    public function showLogin(): View
    {
        return new View('Users/login');
    }
    public function showRegister(): View
    {
        return new View('Users/register');
    }
    public function register()
    {
        $emailExists = Database::connection()
            ->createQueryBuilder()
            ->select('email')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $_POST['email'])
            ->executeQuery()
            ->fetchAllAssociative();

        if (empty($emailExists) && $_POST['password'] === $_POST['pwd_repeat']) {
            Database::connection()
                ->insert('users', [
                    'email' => $_POST['email'],
                    'pwd' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                ]);

            $id = Database::connection()
                ->createQueryBuilder()
                ->select('id')
                ->from('users')
                ->where('email = ?')
                ->setParameter(0, $_POST['email'])
                ->executeQuery()
                ->fetchAssociative();

            Database::connection()
                ->insert('user_info', [
                    'user_id' => $id['id'],
                    'name' => $_POST['name'],
                    'surname' => $_POST['surname']
                ]);
            $_SESSION['current_user'] = [
                'user_id' => $id['id'],
                'name' => $_POST['name'],
                'surname' => $_POST['surname']
            ];
            return new Redirect('/main');
        } else {
            return new Redirect('/users/register');
        }
    }
    public function login()
    {
        $info = Database::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $_POST['email'])
            ->executeQuery()
            ->fetchAssociative();

        if (password_verify($_POST['password'], $info['pwd'])) {
            $user = Database::connection()
                ->createQueryBuilder()
                ->select('*')
                ->from('user_info')
                ->where('user_id = ?')
                ->setParameter(0, $info['id'])
                ->executeQuery()
                ->fetchAssociative();
            $_SESSION['current_user'] = [
                'user_id' => $user['user_id'],
                'name' => $user['name'],
                'surname' => $user['surname']
            ];
            return new Redirect('/main');
        } else {
            return new Redirect('/');
        }
    }
}