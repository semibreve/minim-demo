# Minim
Minimal single-user auth in PHP.

Every so often, you build a website that needs:
  * to run without a database
  * to have an administrator backend
  * to be accessible by one user only

Minim is designed for this purpose; to be a secure, single-user authentication system that doesn't do anything silly like leak the users password (or store it in plain text) or operate over insecure (non-HTTPS) connections unless you want it to.

## Installation
Minim comes with a set of default credentials.

```
Email: me@example.com
Password: demo
```

These *must* be changed before you go into production, so sort these out first:

* Copy `config.yml.dist` and rename the copy to `config.yml`.
* Open up `config.yml` in your favorite text editor.
* Change the `admin_email` field to your email address
* Change the `admin_password_hash` field to the SHA-256 hash of a password of your choice. Never use online services to create your hashes, but hashes created using [this service](http://www.xorbin.com/tools/sha256-hash-calculator) will work. Don't forget to append your `salt`.
* Change the `secret_key` field to a randomly-generated string at least 12 characters long.
* Change the `salt` field to a randomly-generated string at least 12 characters long.
* The default value of 32 for the `token_length` field should be okay for most applications.
* The default value for the `token_ttl` field of 1200 seconds (20 minutes) should be okay for most applications.
* Change the `session_file_name` field to the absolute path of a writable file on your server that Minim can read and write, but that your server _will not serve_.
* Change `cookie_ssl_only` field to `true` if you're operating over HTTPS. If you're not, take a long hard look at your application and ask yourself why you're considering asking for user credentials over an insecure connection when amazing, free tools like [Let's Encrypt](https://letsencrypt.org/) exist.
* Leave `cookie_http_only` as `true` to make the authentication cookie readable only over HTTP and not by client-side script.
