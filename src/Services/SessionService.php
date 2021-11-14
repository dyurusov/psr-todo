<?php

namespace App\Services;

// TODO: replace by middleware

class SessionService
{
    const FLASH_MESSAGE_KEY = 'flashMessage';
    const IS_ADMIN_KEY = 'isAdmin';

    public function __construct()
    {
        session_start();
    }

    public function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function unset(string $key)
    {
        unset($_SESSION[$key]);
    }

    public function getIsAdmin(): bool
    {
        return $this->get(static::IS_ADMIN_KEY) ?? false;
    }

    public function setIsAdmin()
    {
        $this->set(static::IS_ADMIN_KEY, true);
    }

    public function clearIsAdmin()
    {
        $this->unset(static::IS_ADMIN_KEY);
    }

    public function setFlash(string $text, string $type = 'info')
    {
        $this->set(static::FLASH_MESSAGE_KEY, ['type' => $type, 'text' => $text]);
    }

    public function setSuccessFlash(string $text)
    {
        $this->set(static::FLASH_MESSAGE_KEY, ['type' => 'success', 'text' => $text]);
    }

    public function setErrorFlash(string $text)
    {
        $this->set(static::FLASH_MESSAGE_KEY, ['type' => 'danger', 'text' => $text]);
    }

    public function getFlash()
    {
        $flush = $this->get(static::FLASH_MESSAGE_KEY);
        $this->unset(static::FLASH_MESSAGE_KEY);
        return $flush;
    }
}