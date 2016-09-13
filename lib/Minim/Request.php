<?php

namespace Minim;

/**
 * Provides static access to the request submitted from the client.
 *
 * @package Flatfolio
 * @author Saul Johnson
 * @since 11/09/2016
 */
class Request
{
    const POST_KEY_PASSWORD = 'password';
    const POST_KEY_EMAIL = 'email';

    /**
     * Gets the password submitted via the login form.
     *
     * @return string
     */
    public static function getLoginPassword()
    {
        return isset($_POST[self::POST_KEY_PASSWORD])
            ? $_POST[self::POST_KEY_PASSWORD] : '';
    }

    /**
     * Gets the email address submitted via the login form.
     *
     * @return string
     */
    public static function getLoginEmail()
    {
        return isset($_POST[self::POST_KEY_EMAIL])
            ? $_POST[self::POST_KEY_EMAIL] : '';
    }

    /**
     * Gets whether or not the login form has been submitted.
     *
     * @return bool
     */
    public static function isLoginFormSubmitted()
    {
        return self::getLoginEmail() != '' || self::getLoginPassword() != '';
    }
}
