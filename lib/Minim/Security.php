<?php

namespace Minim;

use Spyc;

/**
 * A singleton handle on the application security configuration file.
 *
 * @package Flatfolio
 * @author Saul Johnson
 * @since 11/09/2016
 */
class Security
{
    private static $instance;
    private $config;

    /**
     * Private constructor for a handle on the application security configuration file.
     *
     * @param string $path  the path to read the security configuration file from
     */
    private function __construct($path)
    {
        $this->config = Spyc::YAMLLoad($path);
    }

    /**
     * Gets the admin email address required for login to the backend.
     *
     * @return string
     */
    public function getAdminEmail()
    {
        return $this->config['admin_email'];
    }

    /**
     * Gets the password hash required for login to the backend.
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
     * Gets the single instance of this class.
     *
     * @return Security
     */
    public static function get()
    {
        if (self::$instance == null)
        {
            self::$instance = new Security(__DIR__ . '/../../config/security.yml'); // Load configuration.
        }
        return self::$instance;
    }
}