<?php

namespace Kayue\Kount;

use Psr\Http\Message\ResponseInterface;

class Response
{
    private $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function decodeJson()
    {
        $content = $this->getRawJson();
        $json = json_decode($content);

        if (!$json) {
            throw new BadResponseException('Cannot decode content as JSON "'.$content.'"');
        }

        return $json;
    }

    public function getRawJson()
    {
        return (string) $this->response->getBody();
    }

    public function getStatus()
    {
        return $this->decodeJson()->status;
    }

    public function getErrors()
    {
        return $this->decodeJson()->errors;
    }

    public function getResult()
    {
        $json = $this->decodeJson();

        if (isset($json->result)) {
            return $json->result;
        }

        return null;
    }
}
