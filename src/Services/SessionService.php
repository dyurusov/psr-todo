<?php

namespace App\Services;

// TODO: replace by middleware or use several services

class SessionService
{
    const FLASH_MESSAGE_KEY = 'flashMessage';
    const IS_ADMIN_KEY = 'isAdmin';
    const DESTINATION_KEY = 'destination';
    const FORM_STATE_KEY = 'formState';


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

    public function clear(string $key)
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
        $this->clear(static::IS_ADMIN_KEY);
    }


    public function setFlash($text, string $type = 'info')
    {
        $this->set(static::FLASH_MESSAGE_KEY, ['type' => $type, 'text' => $text]);
    }

    public function setSuccessFlash($text)
    {
        $this->setFlash($text, 'success');
    }

    public function setErrorFlash($text)
    {
        $this->setFlash($text, 'danger');
    }

    public function getFlash()
    {
        $flush = $this->get(static::FLASH_MESSAGE_KEY);
        $this->clear(static::FLASH_MESSAGE_KEY);
        return $flush;
    }


    public function rememberDestination()
    {
        $this->set(static::DESTINATION_KEY, $_SERVER['REQUEST_URI']);
    }

    public function getDestination(): string
    {
        $destination = $this->get(static::DESTINATION_KEY);
        $this->clear(static::DESTINATION_KEY);
        return $destination ?: '';
    }


    public function setFormState(array $state)
    {
        $this->set(static::FORM_STATE_KEY, $state);
    }

    public function getFormState() {
        return $this->get(static::FORM_STATE_KEY);
    }

    public function clearFormState()
    {
        $this->clear(static::FORM_STATE_KEY);
    }
}