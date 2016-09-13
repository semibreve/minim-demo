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
class Auth
{
    /**
     * The security settings for this authenticator.
     *
     * @var Security
     */
    private $security;

    /**
     * Dispenses a randomly generated authentication token.
     *
     * @return string
     */
    private function dispenseToken()
    {
        $token = bin2hex(random_bytes($this->security->getTokenLength())); // Generate token.

        $arr = array(
            'token' => $token,
            'expires' => time() + $this->security->getTokenTimeToLive()
        ); // Prepare array containing token and expiry.

        $plain = Spyc::YAMLDump($arr); // Encode as YAML.
        $encrypted =  Crypto::encrypt($plain, $this->security->getSecretKey()); // Apply symmetric encryption.

        file_put_contents($this->security->getSessionFileName(), $encrypted); // Write to disk.

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
        $encrypted = file_get_contents($this->security->getSessionFileName()); // Read encrypted session file.

        $plain = Crypto::decrypt($encrypted, $this->security->getSecretKey()); // Decrypt data.
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
        $name = $this->security->getCookieName();
        $encrypted = isset($_COOKIE[$name]) ? $_COOKIE[$name] : ''; // Read encrypted cookie.
        $plain = Crypto::decrypt($encrypted, $this->security->getSecretKey());  // Decrypt cookie.

        return $plain;
    }

    /**
     * Encrypts an authentication token into the authentication cookie.
     *
     * @param string $token the token to store in the cookie
     */
    public function setCookieToken($token)
    {
        $encrypted = Crypto::encrypt($token, $this->security->getSecretKey()); // Encrypt token.
        setcookie($this->security->getCookieName(), $encrypted); // Store in cookie.
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
        if ($this->security->getAdminEmail() != $email
            || Crypto::sha256($password, $this->security->getSalt()) != $this->security->getAdminPasswordHash())
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
        setcookie($this->security->getCookieName(), '', time() - 3600); // Remove client-side cookie.
        unlink($this->security->getSessionFileName()); // Delete session file.
    }

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
}
