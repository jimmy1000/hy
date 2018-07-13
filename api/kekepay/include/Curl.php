<?php
class Curl
{
    public $curlObject = null;
    public $url = null;
    public $method = 'GET';
    public $sslVerify = false;
    public $header = null;
    public $printResult = false;
    public $lastError = '';
    public $allowMethod = array('HEAD', 'GET', 'POST', 'DELETE', 'PUT');
    public $postData = array();
    public $headOut = false;
    public $httpHead = null;
    public function init() {
        if ($this->url == null) {
            $this->error('url is null.');
            return false;
        }
        if (!in_array($this->method, $this->allowMethod)) {
            $this->error('request method is unsupported.');
            return false;
        }
        if ($this->curlObject == null) {
            $this->curlObject = curl_init();
        }
        if ($this->headOut) {
            curl_setopt($this->curlObject, CURLINFO_HEADER_OUT, true);
        }
        curl_setopt($this->curlObject, CURLOPT_POSTFIELDS, $this->postData);
        curl_setopt($this->curlObject, CURLOPT_URL, $this->url);
        curl_setopt($this->curlObject, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($this->curlObject, CURLOPT_RETURNTRANSFER, !$this->printResult);
        curl_setopt($this->curlObject, CURLOPT_SSL_VERIFYHOST, $this->sslVerify);
        curl_setopt($this->curlObject, CURLOPT_SSL_VERIFYPEER, $this->sslVerify);
        curl_setopt($this->curlObject, CURLOPT_HEADER, false);
        return true;
    }
    public function request() {
        if (!$this->init()) {
            echo $this->lastError;
            return false;
        }
        return curl_exec($this->curlObject);
    }
    public function error( $info ) {
        $this->lastError = $info;
    }
    public function getHttpHead() {
        if (!$this->headOut)
            return false;
        $this->httpHead = curl_getinfo($this->curlObject, CURLINFO_HEADER_OUT);
        return $this->httpHead;
    }
}