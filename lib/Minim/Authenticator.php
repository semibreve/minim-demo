<?php

namespace Minim;

use Spyc;

/**
 * Represents a very basic authenticator.
 *
 * @package Minim
 * @author Saul Johnson
 * @since 13/09/2016
 */
class Authenticator
{
    /**
     * The security settings for this authenticator.
     *
     * @var Configuration
     */
    private $config;

    /**
     * Dispenses a randomly generated authentication token.
     *
     * @return string
     */
    private function dispenseToken()
    {
        $token = bin2hex(random_bytes($this->config->getTokenLength())); // Generate token.

        $arr = array(
            'token' => $token,
            'expires' => time() + $this->config->getTokenTimeToLive()
        ); // Prepare array containing token and expiry.

        $plain = Spyc::YAMLDump($arr); // Encode as YAML.
        $encrypted =  Encryption::encrypt($plain, $this->config->getSecretKey()); // Apply symmetric encryption.

        file_put_contents($this->config->getSessionFileName(), $encrypted); // Write to disk.

        return $token;
    }

    /**
     * Returns true if the given authentication token is valid, otherwise returns false.
     *
     * @param string $token the authentication token to validate
     * @return bool
     */
    private function validateToken($token)
    {
        // Without a session file, no token will validate.
        if (!file_exists($this->config->getSessionFileName()))
        {
            return false;
        }

        $encrypted = file_get_contents($this->config->getSessionFileName()); // Read encrypted session file.

        $plain = Encryption::decrypt($encrypted, $this->config->getSecretKey()); // Decrypt data.
        $file = Spyc::YAMLLoadString($plain); // Parse YAML.

        return $token == $file['token'] && time() < $file['expires']; // Token must be valid and not expired.
    }

    /**
     * Decrypts the stored authentication token out of the authentication cookie and returns it.
     *
     * @return string
     */
    public function getCookieToken()
    {
        $name = $this->config->getCookieName();
        $encrypted = isset($_COOKIE[$name]) ? $_COOKIE[$name] : ''; // Read encrypted cookie.
        $plain = Encryption::decrypt($encrypted, $this->config->getSecretKey());  // Decrypt cookie.

        return $plain;
    }

    /**
     * Encrypts an authentication token into the authentication cookie.
     *
     * @param string $token the token to store in the cookie
     */
    public function setCookieToken($token)
    {
        $encrypted = Encryption::encrypt($token, $this->config->getSecretKey()); // Encrypt token.
        setcookie($this->config->getCookieName(), $encrypted); // Store in cookie.
    }

    /**
     * Authenticates the given e-mail address password pair.
     *
     * @param string $email     the e-mail address to authenticate
     * @param string $password  the password to authenticate
     * @return bool
     */
    public function authenticate($email, $password)
    {
        // Check credentials.
        if ($this->config->getAdminEmail() != $email
            || Encryption::sha256($password, $this->config->getSalt()) != $this->config->getAdminPasswordHash())
        {
            return false; // Authentication failure.
        }

        // Set cookie to newly dispensed token.
        $this->setCookieToken($this->dispenseToken());

        return true;
    }

    /**
     * Checks whether or not the current user is authenticated.
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        // Validate token.
        $authenticated = $this->validateToken($this->getCookieToken());
        if ($authenticated) {
            $this->setCookieToken($this->dispenseToken()); // Dispense a new token.
        }
        return $authenticated;
    }

    /**
     * Logs the currently authenticated user out.
     */
    public function logout()
    {
        setcookie($this->config->getCookieName(), '', time() - 3600); // Remove client-side cookie.
        unlink($this->config->getSessionFileName()); // Delete session file.
    }

    /**
     * Initializes a new instance of a very basic authenticator.
     *
     * @param Configuration $config the application configuration
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }
}
