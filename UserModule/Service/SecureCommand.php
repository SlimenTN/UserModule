<?php
namespace app\UserModule\Service;


use framework\core\Controller\GlobalContainer;

/**
 * Service SecureCommand
 * @package app\UserModule\Service
 *
 * Prevent visitor from accessing private pages
 */
class SecureCommand
{
    /**
     * @var GlobalContainer
     */
    private $_GC;

    public function __construct(GlobalContainer $globalContainer)
    {
        $this->_GC = $globalContainer;
        $this->secureCommand();
    }

    /**
     * Secure command in case no user is logged in
     * save old accessed url to call it after a success login
     * then redirect unconnected user to the login page
     */
    private function secureCommand(){
        $session = $this->_GC->session();
        if(!$session->exist('user')){
            $oldURL = $this->buildCurrentURL();
            $session->push('old_url', $oldURL);
            $this->_GC->redirectToRoute('login');
        }
    }

    /**
     * Get correct protocol
     * @return string
     */
    private function protocol(){
        return isset($_SERVER["HTTPS"]) ? 'https' : 'http';
    }

    /**
     * Build current url
     * @return string
     */
    private function buildCurrentURL(){
        return $this->protocol().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
}