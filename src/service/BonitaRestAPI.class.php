<?php

require_once 'BonitaProxy.class.php';

class BonitaRestAPI {

    protected $endpoint;
    protected $filters;
    
    const STATE_READY = 'ready';
    const STATE_SKIPPED = 'skipped';
    const STATE_COMPLETED = 'completed';

    public function getEndpoint() {
        return BONITA_API_URL . $this->endpoint;
    }

    public function setEndpoint($endpoint) {
        $this->endpoint = $endpoint;
    }
    
    function getFilters() {
        return $this->filters;
    }

    function setFilters($filters) {
        $this->filters = $filters;
    }

    protected function get($id = NULL) {
        $proxy = new BonitaProxy(BONITA_SERVER_URL, BONITA_USERNAME, BONITA_PASSWORD);
        $endpoint = $this->getEndpoint();
        
        if ( is_null($id) ) {
            $endpoint .= "?p=0&c=10000";
            if ( count($this->filters) > 0 ) {
                $endpoint .= $this->parseFilters();
            }
        } else {
            $endpoint .= "/$id";
        }
        
        return json_decode($proxy->executeCURLGETaction($endpoint));
    }
    
    protected function put($id, $data) {
        $proxy = new BonitaProxy(BONITA_SERVER_URL, BONITA_USERNAME, BONITA_PASSWORD);
        $endpoint = $this->getEndpoint();
        $endpoint .= "/{$id}";
        return json_decode($proxy->executeCURLPUTaction($endpoint, $data));
    }
    
    protected function delete($id) {
        $proxy = new BonitaProxy(BONITA_SERVER_URL, BONITA_USERNAME, BONITA_PASSWORD);
        $endpoint = $this->getEndpoint();
        $endpoint .= "/{$id}";
        return json_decode($proxy->executeCURLDELETEaction($endpoint, $data));
    }
    
    protected function post($data) {
        $proxy = new BonitaProxy(BONITA_SERVER_URL, BONITA_USERNAME, BONITA_PASSWORD);
        $endpoint = $this->getEndpoint();
        return json_decode($proxy->executeCURLPOSTaction($endpoint, $data));
    }
    
    private function parseFilters() {
        $query = '';
        foreach ( $this->getFilters() as $key => $filter ) {
            $query .= "&f={$key}={$filter}";
        }
        return $query;
    }

}