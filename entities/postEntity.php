<?php
namespace Justfun\Entities;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of postEntity
 *
 * @author francesco
 */
class postEntity {
    
    const ENTITY_NAME = 'post';
    const MESSAGE_PREVIEW_MAXLENGTH = 120;
    
    protected static $count = 0;
    
    protected $id,$title,$message,$created,$url;
    
    public function getId() {
        return $this->id;
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function getMessage() {
        return $this->message;
    }
    
    public function getMessagePreview(){
        $message = substr($this->message, 0, self::MESSAGE_PREVIEW_MAXLENGTH);
        libxml_use_internal_errors(true); // evita errori dovuti a tag troncati
        $node = new \DOMDocument();
        $node->loadHTML($message);
        $node->normalizeDocument(); // normalizza il documento (chiude tag aperti)
        // rimuove doctype e tag html body
        $message =  preg_replace('/^<!DOCTYPE.+?>/',
                                 '',
                                str_replace( 
                                    array('<html>', '</html>', '<body>', '</body>'), 
                                    array('', '', '', ''), 
                                    $node->saveHTML()));
        return $message;
    }

    public function getCreated() {
        return date("g:i a F j, Y ", strtotime($this->created));
        //return $this->created;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    public function setCreated($created) {
        $this->created = $created;
        return $this;
    }

    // @TODO: generare l'url adatto
    public function getUrl() {
        return '/'.self::ENTITY_NAME.'/'.$this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }
}
