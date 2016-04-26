<?php

namespace Justfun\Services;

/**
 * Class paginatorService
 *
 * @author Pironato Francesco pironato.f@gmail.com
 */
Class paginatorService {
    
    const DEFAULT_ITEMS_PER_PAGE = 5;
    
    protected $offset,$limit,$numOfPages,$currentPage;
    
    protected $baseUrl;
    
    public function __construct($currentPage,$itemsNumber,$itemsPerPage) {
        $this->initialize($currentPage,$itemsNumber,$itemsPerPage);
    }

    private function initialize($currentPage,$itemsNumber,$itemsPerPage){
        
        $offset = ($itemsPerPage)*($currentPage-1);
        $limit = $itemsPerPage;
        
        $this->setOffset( round($offset) )
             ->setLimit(round($limit))
             ->setNumOfPages($itemsNumber, $itemsPerPage)
             ->setCurrentPage($currentPage);
    }
    
    public function getCurrentPage() {
        return $this->currentPage;
    }

    private function setCurrentPage($currentPage) {
        $this->currentPage = $currentPage;
        return $this;
    }

        private function setOffset($offset) {
        $this->offset = $offset;
        return $this;
    }

    private function setLimit($limit) {
        $this->limit = $limit;
        return $this;
    }

    private function setNumOfPages($itemsNumber,$itemsPerPage){
        $this->numOfPages = (int)ceil((int)$itemsNumber/(int)$itemsPerPage);
        return $this;
    }
    
    public function getOffset() {
        return $this->offset;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function getNumOfPages(){
        return $this->numOfPages;
    }
    
    public function getBaseUrl() {
        return $this->baseUrl;
    }

    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;
        return $this;
    }
}
