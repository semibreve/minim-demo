<?php

namespace Minim;

/**
 * Handles very basic authentication for the backend.
 *
 * @package Flatfolio
 * @author Saul Johnson
 * @since 11/09/2016
 */
class Auth
{
    const COOKIE_KEY_AUTH = 'flatfolio_auth';

    /**
     * Hashes a password.
     *
     * @param string $password  the password to hash
     * @return string
     */
    public static function hashPassword($password)
    {
        return hash('sha256', $password);
    }

    /**
     * Encrypts a string.
     *
     * @param string $data      the string to encrypt
     * @param string $password  the password to use to encrypt it
     * @return string
     */
    public static function encrypt($data, $password)
    {
        $iv_size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encryptedMessage = openssl_encrypt($data, "AES-256-CBC", $password, 0, $iv);
        return $iv.$encryptedMessage;
    }

    /**
     * Decrypts a previously encrypted string.
     *
     * @param string $data      the string to decrypt
     * @param string $password  the password to use to decrypt it
     * @return string
     */
    public static function decrypt($data, $password)
    {
        $iv_size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CBC);
        $iv = substr($data, 0, $iv_size);
        $decryptedMessage = openssl_decrypt(substr($data, $iv_size), "AES-256-CBC", $password, 0, $iv);
        return $decryptedMessage;
    }

    /**
     * Decrypts the stored password out of the auth cookie and returns it.
     *
     * @return string
     */
    public static function getAuth()
    {
        // Decrypt password on its way out of cookie.
        $config = getSecurity();
        $auth = isset($_COOKIE[self::COOKIE_KEY_AUTH])
            ? $_COOKIE[self::COOKIE_KEY_AUTH] : '';
        return self::decrypt($auth, $config->getSecretKey());
    }

    /**
     * Encrypts a password into the auth cookie.
     *
     * @param $auth string  the password to store in the cookie
     */
    public static function setAuth($auth)
    {
        // Encrypt password on its way in to cookie.
        $config = getSecurity();
        setcookie(self::COOKIE_KEY_AUTH, self::encrypt($auth,
            $config->getSecretKey()));
    }

    /**
     * Authenticates the given e-mail address password pair.
     *
     * @param string $email     the e-mail address to authenticate
     * @param string $password  the password to authenticate
     * @return bool
     */
    public static function authenticate($email, $password)
    {
        // Check credentials.
        $config = getSecurity();
        if ($config->getAdminEmail() != $email
            || self::hashPassword($password) != $config->getAdminPasswordHash())
        {
            return false; // Authentication failure.
        }

        // Set cookie to encrypted password.
        self::setAuth($password);

        return true;
    }

    /**
     * Checks whether or not the current user is authenticated.
     *
     * @return bool
     */
    public static function isAuthenticated()
    {
        // Is encrypted password stored in cookie?
        $config = getSecurity();
        return self::hashPassword(self::getAuth()) == $config->getAdminPasswordHash();
    }

    /**
     * Logs the currently authenticated user out.
     */
    public static function logout()
    {
        setcookie(self::COOKIE_KEY_AUTH, '');
    }
}