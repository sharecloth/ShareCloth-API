<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12.10.2016
 * Time: 9:35
 */

namespace ShareCloth\Api\Response;


class JsonResponseFactory extends AbstractResponseFactory
{

    public function getApiResponse($data)
    {
        $data = json_decode($data, true);

        $apiResponse = new ApiResponse();
        $apiResponse->setStatus($data['status']);
        $apiResponse->setData($data['data']);

        return $apiResponse;
    }
}