<?php

namespace SprykerFeature\Client\Payone\ClientApi;
use SprykerFeature\Client\Payone\ClientApi\Request\AbstractRequest;


interface HashGeneratorInterface
{

    public function generateHash(AbstractRequest $request, $securityKey);

}