<?php

namespace LoremIpsum\EmailIt;

use Exception;
use GuzzleHttp\Exception\GuzzleException;

final class EmailItMailer extends Base
{
    public function __construct(string $apiKey)
    {
        parent::__construct($apiKey);
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $htmlContent
     * @param string $textContent
     * @param array $attachments
     * @param array $headers
     * @return array
     * @throws Exception
     */
    public function send(
        string $from,
        string $to,
        string $subject,
        string $htmlContent = '',
        string $textContent = '',
        array $attachments = [],
        array $headers = []
    ):array
    {
        $emailData = [
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
        ];
        if(!empty($htmlContent)) {
            $emailData['html'] = $htmlContent;
        }
        if(!empty($textContent)) {
            $emailData['text'] = $textContent;
        }
        if(!empty($attachments)) {
            try{
                $this->validateAttachments($attachments);
                $emailData['attachments'] = $attachments;
            }catch (\InvalidArgumentException $e) {
                throw new Exception('Invalid attachments: ' . $e->getMessage(), $e->getCode());
            }
        }
        if(!empty($headers)) {
            $this->headers = array_merge($this->headers, $headers);
        }
        try{
            $response = $this->client->request('POST', 'emails', [
                'headers' => $this->headers,
                'json' => $emailData
            ]);
        }catch (GuzzleException $exception){
            throw new Exception('Error sending email: ' . $exception->getMessage(), $exception->getCode());
        }
        $data = json_decode($response->getBody()->getContents(), true);
        return $data['data'];
    }

    /**
     * @param int $page
     * @param int $limit
     * @param string $searchTerm
     * @return array
     * @throws Exception
     */
    public function getSendingDomains(int $page = 1, int $limit = 10, string $searchTerm = ''):array
    {
        $query = [
            'per_page' => $limit,
            'page' => $page,
        ];
        if(!empty($filter)) {
            $query['filter[name]'] = $searchTerm;
        }
        try{
            $response = $this->client->request('GET', 'sending-domains', [
                'headers' => $this->headers,
                'query' => $query
            ]);
        }catch (GuzzleException $exception){
            throw new Exception('Error getting sending domains: ' . $exception->getMessage(), $exception->getCode());
        }
        $data = json_decode($response->getBody()->getContents(), true);
        return $data['data'];
    }

    /**
     * @param string $id
     * @return array
     * @throws Exception
     */
    public function getSendingDomain(string $id):array
    {
        try{
            $response = $this->client->request('GET', 'sending-domains/'. $id, [
                'headers' => $this->headers,
            ]);
        }catch (GuzzleException $exception){
            throw new Exception('Error getting sending domains: ' . $exception->getMessage(), $exception->getCode());
        }
        $data = json_decode($response->getBody()->getContents(), true);
        return $data['data'];
    }
}