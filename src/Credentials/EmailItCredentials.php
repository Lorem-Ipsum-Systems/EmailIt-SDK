<?php

namespace LoremIpsum\EmailIt\Credentials;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use LoremIpsum\EmailIt\Base;
use LoremIpsum\EmailIt\Enum\CredentialsTypeEnum;

class EmailItCredentials extends Base
{
    public function __construct(string $apiKey)
    {
        parent::__construct($apiKey);
    }

    /**
     * @param int $page
     * @param int $limit
     * @param string $filter
     * @param CredentialsTypeEnum $type
     * @return array
     * @throws Exception
     */
    public function listCredentials(int $page = 1, int $limit = 25, string $filter = '', CredentialsTypeEnum $type = CredentialsTypeEnum::API):array
    {
        $url = 'credentials?page=' . $page . '&limit=' . $limit . '&filter[name]=' . $filter . '&filter[type]=' . $type->value;

        try{
            $response = $this->client->request('GET', $url, [
                'headers' => $this->headers,
            ]);
        }catch (GuzzleException $exception){
            throw new Exception('Error getting credentials: ' . $exception->getMessage(), $exception->getCode());
        }
        $data = json_decode($response->getBody()->getContents(), true);
        return $data['data'];
    }

    /**
     * @param string $name
     * @param CredentialsTypeEnum $type
     * @return array
     * @throws Exception
     */
    public function createCredentials(string $name, CredentialsTypeEnum $type = CredentialsTypeEnum::API):array
    {
        $body = [
            'name' => $name,
            'type' => $type->value
        ];
        try{
            $response = $this->client->request('POST', 'credentials', [
                'headers' => $this->headers,
                'json' => $body
            ]);
        }catch (GuzzleException $exception){
            throw new Exception('Error creating credentials: ' . $exception->getMessage(), $exception->getCode());
        }
        $data = json_decode($response->getBody()->getContents(), true);
        return $data['data'];
    }

    /**
     * @param string $id
     * @return void
     * @throws Exception
     */
    public function deleteCredentials(string $id):void
    {
        $url = 'credentials/' . $id;
        try{
            $response = $this->client->request('DELETE', $url, [
                'headers' => $this->headers,
            ]);
        }catch (GuzzleException $exception){
            throw new Exception('Error deleting credentials: ' . $exception->getMessage(), $exception->getCode());
        }
    }
}