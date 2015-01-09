<?php
namespace Justfun\Core;

use Justfun\Core\Interfaces\statusInterface as status;
/**
 * Description of Core
 *
 * @author outsider
 */
class Core implements status {

    protected $server;
    protected $request;
    protected $routing;
    protected $get;
    protected $post;
    protected $session;
    protected $cookie;
    protected $status;
    
    protected $exception;

    public function __construct($server, $get, $post) {
        
        // set globals
        try {
            $this->setServer($_SERVER)->setGet($_GET)->setPost($_POST);
        } catch (Exception $exception) {
            // incapsulo le eccezioni e le gestisco
            $this->setStatus(self::STATUS_ERR);
            $this->setException ($exception);
            // da sostituire con un oggetto response da passare al /controller/view, da mandare in 505/404
            die('<h2 style="color:red">'.$this->getStatus().'</h2> <br/>'.$this->getErrors());
        }
        
        
        // manage templating
        
        // status ok
        $this->setStatus(self::STATUS_OK);
    }

    
    public function getException(){
        return $this->exception;
    }


    public function setException($exception){
        $this->exception = $exception;
        return $this;
    }
    
    
    
    
    public function getStatus() {
        return $this->status;
    }

    public function getErrors() {
        if($exc = $this->getException())   return $exc->getTraceAsString();
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function getServer() {
        return $this->server;
    }

    public function getRequest() {
        return $this->request;
    }

    public function getGet() {
        return $this->get;
    }

    public function getPost() {
        return $this->post;
    }

    public function getSession() {
        return $this->session;
    }

    public function getCookie() {
        return $this->cookie;
    }

    public function setServer($server) {
        $this->server = $server;
        return $this;
    }

    public function setRequest($request) {
        $this->request = $request;
        return $this;
    }

    public function setGet($get) {
        $this->get = $get;
        return $this;
    }

    public function setPost($post) {
        $this->post = $post;
        return $this;
    }

    public function setSession($session) {
        $this->session = $session;
        return $this;
    }

    public function setCookie($cookie) {
        $this->cookie = $cookie;
        return $this;
    }

}
