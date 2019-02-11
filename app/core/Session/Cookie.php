<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 11.02.2019
 * Time: 17:53
 */

namespace App\Core\Session;

class Cookie
{
    const HEADER_PREFIX = 'Set-Cookie: ';
    private $name;
    private $value;
    private $expiryTime;
    private $path;
    private $domain;
    private $httpOnly;
    private $secureOnly;

    public function __construct($name)
    {
        $this->name = $name;
        $this->expiryTime = 0;
        $this->path = '/';
        $this->httpOnly = true;
        $this->secureOnly = false;
    }

    /**
     * @param $cookieHeader
     * @return Cookie|null
     */
    public static function parse($cookieHeader): ?Cookie
    {
        if (empty($cookieHeader)) {
            return null;
        }
        if (preg_match('/^' . self::HEADER_PREFIX . '(.*?)=(.*?)(?:; (.*?))?$/i', $cookieHeader, $matches)) {
            $cookie = new self($matches[1]);
            $cookie->setPath(null);
            $cookie->setHttpOnly(false);
            $cookie->setValue(
                urldecode($matches[2])
            );
            if (\count($matches) >= 4) {
                $attributes = explode('; ', $matches[3]);
                foreach ($attributes as $attribute) {
                    if (strcasecmp($attribute, 'HttpOnly') === 0) {
                        $cookie->setHttpOnly(true);
                    } elseif (strcasecmp($attribute, 'Secure') === 0) {
                        $cookie->setSecureOnly(true);
                    } elseif (stripos($attribute, 'Expires=') === 0) {
                        $cookie->setExpiryTime((int)strtotime(substr($attribute, 8)));
                    } elseif (stripos($attribute, 'Domain=') === 0) {
                        $cookie->setDomain(substr($attribute, 7));
                    } elseif (stripos($attribute, 'Path=') === 0) {
                        $cookie->setPath(substr($attribute, 5));
                    }
                }
            }
            return $cookie;
        }

        return null;
    }

    /**
     * @param $secureOnly
     * @return $this
     */
    public function setSecureOnly($secureOnly): self
    {
        $this->secureOnly = $secureOnly;
        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public static function exists($name): bool
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * @param $name
     * @param null $defaultValue
     * @return null
     */
    public static function get($name, $defaultValue = null)
    {
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }

        return $defaultValue;
    }

    /**
     * @param null $domain
     * @return null|string
     */
    private static function normalizeDomain($domain = null): ?string
    {
        $domain = (string)$domain;
        if ($domain === '') {
            return null;
        }
        if (filter_var($domain, FILTER_VALIDATE_IP) !== false) {
            return null;
        }
        if (strpos($domain, '.') === false || strrpos($domain, '.') === 0) {
            return null;
        }
        if ($domain[0] !== '.') {
            $domain = '.' . $domain;
        }
        return $domain;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpiryTime(): int
    {
        return $this->expiryTime;
    }

    /**
     * @param $expiryTime
     * @return $this
     */
    public function setExpiryTime($expiryTime): self
    {
        $this->expiryTime = $expiryTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxAge(): int
    {
        return $this->expiryTime - time();
    }

    /**
     * @param $maxAge
     * @return $this
     */
    public function setMaxAge($maxAge): self
    {
        $this->expiryTime = time() + $maxAge;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setPath($path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return null
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param null $domain
     * @return $this
     */
    public function setDomain($domain = null): self
    {
        $this->domain = self::normalizeDomain($domain);
        return $this;
    }

    /**
     * @return bool
     */
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * @param $httpOnly
     * @return $this
     */
    public function setHttpOnly($httpOnly): self
    {
        $this->httpOnly = $httpOnly;
        return $this;
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        $copiedCookie = clone $this;
        $copiedCookie->setValue('');
        return $copiedCookie->save();
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        return self::addHttpHeader((string)$this);
    }

    /**
     * @param $header
     * @return bool
     */
    private static function addHttpHeader($header): bool
    {
        if (!headers_sent() && !empty($header)) {
            header($header, false);
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return self::buildCookieHeader($this->name, $this->value, $this->expiryTime, $this->path, $this->domain, $this->secureOnly, $this->httpOnly);
    }

    /**
     * @param $name
     * @param null $value
     * @param int $expiryTime
     * @param null $path
     * @param null $domain
     * @param bool $secureOnly
     * @param bool $httpOnly
     * @return string
     */
    public static function buildCookieHeader($name, $value = null, $expiryTime = 0, $path = null, $domain = null, $secureOnly = false, $httpOnly = false): string
    {
        if (self::isNameValid($name)) {
            $name = (string)$name;
        } else {
            return null;
        }
        if (self::isExpiryTimeValid($expiryTime)) {
            $expiryTime = (int)$expiryTime;
        } else {
            return null;
        }
        $forceShowExpiry = false;
        if (null === $value || $value === false || $value === '') {
            $value = 'deleted';
            $expiryTime = 0;
            $forceShowExpiry = true;
        }
        $maxAgeStr = self::formatMaxAge($expiryTime, $forceShowExpiry);
        $expiryTimeStr = self::formatExpiryTime($expiryTime, $forceShowExpiry);
        $headerStr = self::HEADER_PREFIX . $name . '=' . urlencode($value);
        if (null !== $expiryTimeStr) {
            $headerStr .= '; expires=' . $expiryTimeStr;
        }
        if (PHP_VERSION_ID >= 50500 && null !== $maxAgeStr) {
            $headerStr .= '; Max-Age=' . $maxAgeStr;
        }
        if (!empty($path) || $path === 0) {
            $headerStr .= '; path=' . $path;
        }
        if (!empty($domain) || $domain === 0) {
            $headerStr .= '; domain=' . $domain;
        }
        if ($secureOnly) {
            $headerStr .= '; secure';
        }
        if ($httpOnly) {
            $headerStr .= '; httponly';
        }
        return $headerStr;
    }

    /**
     * @param $name
     * @return bool
     */
    private static function isNameValid($name): bool
    {
        $name = (string)$name;
        if ($name !== '' || PHP_VERSION_ID < 70000) {
            if (!preg_match('/[=,; \t\r\n\013\014]/', $name)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $expiryTime
     * @return bool
     */
    private static function isExpiryTimeValid($expiryTime): bool
    {
        return is_numeric($expiryTime) || $expiryTime === null || \is_bool($expiryTime);
    }

    /**
     * @param $expiryTime
     * @param bool $forceShow
     * @return null|string
     */
    private static function formatMaxAge($expiryTime, $forceShow = false): ?string
    {
        if ($expiryTime > 0 || $forceShow) {
            return (string)self::calculateMaxAge($expiryTime);
        }

        return null;
    }

    /**
     * @param $expiryTime
     * @return int
     */
    private static function calculateMaxAge($expiryTime): int
    {
        if ($expiryTime === 0) {
            return 0;
        }

        $maxAge = $expiryTime - time();
        // The value of the `Max-Age` property must not be negative on PHP 7.0.19+ (< 7.1) and
        // PHP 7.1.5+ (https://bugs.php.net/bug.php?id=72071).
        if ((PHP_VERSION_ID >= 70019 && PHP_VERSION_ID < 70100) || PHP_VERSION_ID >= 70105) {
            if ($maxAge < 0) {
                $maxAge = 0;
            }
        }
        return $maxAge;
    }

    /**
     * @param $expiryTime
     * @param bool $forceShow
     * @return false|null|string
     */
    private static function formatExpiryTime($expiryTime, $forceShow = false)
    {
        if ($expiryTime > 0 || $forceShow) {
            if ($forceShow) {
                $expiryTime = 1;
            }
            return gmdate('D, d-M-Y H:i:s T', $expiryTime);
        }

        return null;
    }

}