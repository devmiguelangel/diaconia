<?php

class Session
{

    private $user_agent, $skey, $ip_address, $last_activity;

    public $idUser, $idEF, $session_time;

    private $max_time;

    private $domain;

    private $inactive = 10;


    public function __construct()
    {
        $path         = '/';
        $this->domain = $_SERVER['HTTP_HOST'];

        if (strpos($this->domain, '.app') !== false) {
            # code...
        } elseif (strpos($this->domain, 'diaconia') === false) {
            $path = '/diaconia';
        }

        // $this->max_time = (60 * 60 * 8);
        $this->max_time = ( 60 * 30 );

        ini_set('session.gc_maxlifetime', $this->max_time);
        ini_set('session.cookie_lifetime', $this->max_time);

        session_cache_limiter('private');
        session_cache_expire(10);
        session_set_cookie_params($this->max_time, $path, $this->domain, false, true);

        session_start();
        session_regenerate_id();

        if (isset( $_SESSION['sKey'] )) {
            $this->skey = $_SESSION['sKey'];
        } else {
            $this->skey = '';
        }

        if (isset( $_SESSION['idUser'] )) {
            $this->idUser = $_SESSION['idUser'];
        } else {
            $this->idUser = '';
        }

        if (isset( $_SESSION['idEF'] )) {
            $this->idEF = $_SESSION['idEF'];
        } else {
            $this->idEF = '';
        }

        if (isset( $_SESSION['ipAddress'] )) {
            $this->ip_address = $_SESSION['ipAddress'];
        } else {
            $this->ip_address = $this->get_ip_address();
        }

        if (isset( $_SESSION['userAgent'] )) {
            $this->user_agent = $_SESSION['userAgent'];
        } else {
            $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
        }

        if (isset( $_SESSION['lastActivity'] )) {
            $this->last_activity = $_SESSION['lastActivity'];
        } else {
            $this->last_activity = '';
        }

        if (isset( $_SESSION['session_time'] )) {
            $this->session_time = $_SESSION['session_time'];
        } else {
            $this->session_time = 0;
        }
    }


    public function start_session($idUser, $idEF)
    {
        $_SESSION['idUser']       = base64_encode($idUser);
        $_SESSION['idEF']         = base64_encode($idEF);
        $_SESSION['sKey']         = md5(uniqid(mt_rand(), true));
        $_SESSION['ipAddress']    = $this->ip_address;
        $_SESSION['userAgent']    = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['lastActivity'] = $_SERVER['REQUEST_TIME'];
        $_SESSION['session_time'] = 30000;
    }


    public function check_session()
    {
        if (isset( $_SESSION['sKey'] ) && isset( $_SESSION['ipAddress'] ) && isset( $_SESSION['idUser'] ) && isset( $_SESSION['idEF'] ) && isset( $_SESSION['session_time'] )) {

            if ($_SESSION['ipAddress'] === $this->get_ip_address() && $_SESSION['userAgent'] === $this->user_agent) {
                return true;
            }
        } else {
            return false;
        }
    }


    public function setSessionCookie()
    {
        $this->setDataCookie();

        if (empty( $_SESSION['idUser'] ) === false && isset( $_SESSION['idEF'] )) {
            //setcookie('DiaconiaToken[user]', $_SESSION['idUser'], time() + 1 * 24 * 60 * 60);
            return true;
        } else {
            return false;
        }
    }


    private function setDataCookie()
    {
        if (isset( $_SESSION['idUser'], $_SESSION['idEF'] )) {
            setcookie('DiaconiaToken[user]', $_SESSION['idUser'], time() + $this->max_time, $this->domain, '', false,
                true);
            setcookie('DiaconiaToken[ef]', $_SESSION['idEF'], time() + $this->max_time, $this->domain, '', false, true);
        }
    }


    public function getSessionCookie()
    {
        if (isset( $_COOKIE['DiaconiaToken'] ) && empty( $_SESSION['idUser'] ) === true) {
            $data_token = $_COOKIE['DiaconiaToken'];

            $idepro_user = htmlspecialchars($data_token['user']);
            $idepro_ef   = htmlspecialchars($data_token['ef']);
            $this->start_session(base64_decode($idepro_user), base64_decode($idepro_ef));

            $this->setDataCookie();
        }
    }


    public function remove_session()
    {
        session_unset();
        session_destroy();
        session_regenerate_id(true);

        if (isset( $_COOKIE['DiaconiaToken'] )) {
            setcookie('DiaconiaToken', '', time() - 3600, $this->domain, '', false, true);
            setcookie('DiaconiaToken[user]', '', time() - 3600, $this->domain, '', false, true);
            setcookie('DiaconiaToken[ef]', '', time() - 3600, $this->domain, '', false, true);
            /*setcookie('DiaconiaToken[user]', '', time() - 3600);
            setcookie('DiaconiaToken[ef]', '', time() - 3600);
            */
        }
    }


    private function get_ip_address()
    {
        $ip = '';
        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] )) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] )) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}

?>