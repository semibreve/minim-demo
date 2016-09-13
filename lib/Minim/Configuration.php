<?php

namespace Minim;

use Spyc;

/**
 * Represents the application security configuration file.
 *
 * @package Minim
 * @author Saul Johnson
 * @since 11/09/2016
 */
class Configuration
{
    private $config;

    /**
     * Creates a new instance of the application security configuration file.
     *
     * @param string $path  the path to read the security configuration file from
     */
    public function __construct($path)
    {
        $this->config = Spyc::YAMLLoad($path);
    }

    /**
     * Gets the admin email address required for login.
     *
     * @return string
     */
    public function getAdminEmail()
    {
        return $this->config['admin_email'];
    }

    /**
     * Gets the password hash required for login.
     *
     * @return string
     */
    public function getAdminPasswordHash()
    {
        return $this->config['admin_password_hash'];
    }

    /**
     * Gets the secret key used by the application for symmetric encryption purposes.
     *
     * @return string
     */
    public function getSecretKey()
    {
        return $this->config['secret_key'];
    }

    /**
     * Gets the salt to use during password hashing.
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->config['salt'];
    }

    /**
     * Gets the length configured for login tokens.
     *
     * @return int
     */
    public function getTokenLength()
    {
        return $this->config['token_length'];
    }

    /**
     * Gets the time to live for login tokens, in seconds.
     *
     * @return int
     */
    public function getTokenTtl()
    {
        return $this->config['token_ttl'];
    }

    /**
     * Gets the name of the cookie configured to hold the login auth token.
     *
     * @return string
     */
    public function getCookieName()
    {
        return $this->config['cookie_name'];
    }

    /**
     * Gets the name of the session file.
     *
     * @return string
     */
    public function getSessionFileName()
    {
        return $this->config['session_file_name'];
    }

    /**
     * Gets whether or not the login cookie is enabled for HTTPS only.
     *
     * @return bool
     */
    public function getCookieSslOnly()
    {
        return $this->config['cookie_ssl_only'];
    }

    /**
     * Gets whether or not the login cookie is enabled for HTTP(S) only and not client-side script.
     *
     * @return bool
     */
    public function getCookieHttpOnly()
    {
        return $this->config['cookie_http_only'];
    }
}
