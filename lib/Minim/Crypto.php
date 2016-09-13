<?php

namespace Minim;

/**
 * Provides useful encryption functions.
 *
 * @package Minim
 * @author Saul Johnson
 * @since 13/09/2016
 */
class Crypto
{
    /**
     * Hashes a password using SHA-256.
     *
     * @param string $password  the password to hash
     * @param string $salt      the salt for the password
     * @return string
     */
    public static function sha256($password, $salt)
    {
        return hash('sha256', $password . $salt);
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
}