<?php

namespace EmmanuelVella\UserPreferenceBundle\User\Preference;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

class UserPreference
{
    protected $request;
    protected $preferences = array();

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        foreach ($this->preferences as $name => $value) {
            $response->headers->setCookie(new Cookie($name, base64_encode(serialize($value)), strtotime('+1 year')));
        }

        $event->setResponse($response);
    }

    public function has($name)
    {
        if ($this->request->getSession()->has($name)) {
            return true;
        }

        if ($this->request->cookies->has($name)) {
            return true;
        }

        return false;
    }

    public function set($name, $value)
    {
        $this->preferences[$name] = $value;

        $this->request->getSession()->set($name, $value);
    }

    public function get($name)
    {
        $cookies = $this->request->cookies;
        $session = $this->request->getSession();

        if ($session->has($name)) {
            return $session->get($name);
        }

        if ($cookies->has($name)) {
            return unserialize(base64_decode($cookies->get($name)));
        }

        return false;
    }
}
