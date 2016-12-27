<?php

namespace ShareCloth\Api\Response;

/**
 * Created by PhpStorm.
 * User: User
 * Date: 12.10.2016
 * Time: 9:34
 */
class ApiResponse
{

    /**
     *
     */
    const STATUS_SUCCESS = 'success';

    /**
     *
     */
    const STATUS_ERROR = 'error';


    private $_status;

    private $_data;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->_status = $status;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->_data = $data;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        return $this->getDataItem('message');
    }


    public function isResponseSuccess()
    {
        return $this->_status === self::STATUS_SUCCESS;
    }


    public function getDataItem($key)
    {
        return array_key_exists($key, $this->_data) ? $this->_data[$key] : null;
    }



}