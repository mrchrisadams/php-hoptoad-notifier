<?php
class Services_Hoptoad_Exception extends RuntimeException
{
    protected $data;

    public function getRequestData()
    {
        return $this->data;
    }

    public function setRequestData($data)
    {
        $this->data = $data;
        return $this;
    }
}
