<?php

/**
 * Created by PhpStorm.
 * User: professor
 * Date: 11.02.2019
 * Time: 18:04
 */

namespace App\Core;


use App\App;
use App\Core\Session\Cookie;
use App\Core\Session\Session;
use Models\User;


class Auth
{
    public $user;

    public function __construct()
    {
        $this->initSession();
        $this->getUser();
    }

    private function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_cookies', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.use_trans_sid', 0);
            Session::start();
        }
    }

    public function destroySession(): void
    {
        $_SESSION = [];
        $this->deleteSessionCookie();
        session_destroy();
    }

    private function deleteSessionCookie(): void
    {
        $params = session_get_cookie_params();
        $cookie = new Cookie(session_name());
        $cookie->setPath($params['path']);
        $cookie->setDomain($params['domain']);
        $cookie->setHttpOnly($params['httponly']);
        $cookie->setSecureOnly($params['secure']);
        $result = $cookie->delete();
        if ($result === false) {
            exit('Header already sent!');
        }
    }

    /**
     * @param $email
     * @param $password
     * @param $username
     * @return User
     */
    public function register($email, $password, $username): User
    {
        $user = new User();
        $user->email = $email;
        $user->password = md5(md5($password)); // TODO: Passwordni bu usulda crypt qilish xato!
        $user->username = $username;
        $user->save();
        return $user;
    }

    /**
     * @param $username
     * @param $password
     * @param null $rememberDuration
     * @return bool|User
     */
    public function login($username, $password, $rememberDuration = null)
    {
        $user = App::getInstance()->db->get(User::getTableName(), '*', [
            'username' => $username,
            'password' => md5(md5($password))
        ]);
        if ($user) {
            Session::set('user_id', $user['id']);
            if ($rememberDuration > 0) {
                // TODO cookie
            }
            return new User($user);
        }
        return false;
    }

    public function logout(): void
    {
        $this->destroySession();
    }

    public function getUser()
    {
        if (Session::get('user_id')) {
            $user = User::find(Session::get('user_id'));
            if ($user) {
                $this->user = $user;
            } else {
                Session::delete('user_id');
            }
        }
    }

}