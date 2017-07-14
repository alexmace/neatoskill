<?php

namespace App\Alexa;

class Request
{

    const REQUEST_INTENT = 'IntentRequest';
    const REQUEST_LAUNCH = 'LaunchRequest';
    const REQUEST_SESSIONENDED = 'SessionEndedRequest';
    const VALID_REQUESTS = [
        self::REQUEST_INTENT,
        self::REQUEST_LAUNCH,
        self::REQUEST_SESSIONENDED
    ];

    private $_applicationId;
    private $_newSession = false;
    private $_sessionId;
    private $_requestType;
    private $_locale;
    private $_intent;

    public function __construct(array $data)
    {
        $version = $data['version'];
        $session = $data['session'];
        $context = $data['context'];
        $request = $data['request'];

        $this->_applicationId = $session['application']['applicationId'];
        $this->_newSession = $session['new'];
        $this->_sessionId = $session['sessionId'];
        $this->_requestType = $request['type'];
        $this->_locale = $request['locale'];
        $this->_intent = $request['intent']['name'];
    }

    public function getApplicationId()
    {
        return $this->_applicationId;
    }

    public function isNewSession()
    {
        return $this->_newSession;
    }

    public function getSessionId()
    {
        return $this->_sessionId;
    }

    public function getRequestType()
    {
        return $this->_requestType;
    }

    public function getLocale()
    {
        return $this->_locale;
    }

    public function getIntent()
    {
        return $this->_intent;
    }
}
