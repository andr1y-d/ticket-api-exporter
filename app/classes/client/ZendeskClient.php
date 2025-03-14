<?php

namespace App\client;
use GuzzleHttp\Client;


class ZendeskClient implements ClientInterface
{
    private Client $httpClient;
    private string $subdomain;
    private string $email;
    private string $apiToken;

    public function __construct(string $subdomain, string $email, string $apiToken)
    {
        $this->subdomain = $subdomain;
        $this->email = $email;
        $this->apiToken = $apiToken;
        $this->httpClient = new Client([
            "headers" => ["accept" => "application/json"],
            "base_uri" => "https://{$this->subdomain}.zendesk.com",
            "auth" => ["$this->email/token","$this->apiToken"]
        ]);
    }

    public function fetchIncrementalTickets(): array
    {
        $response = $this->httpClient->request('GET', "api/v2/incremental/tickets/cursor?start_time=0&include=users,groups,organizations");

        return json_decode($response->getBody(), true);
    }

    public function fetchNextPage(string $nextPage): array
    {
        $response = $this->httpClient->request('GET', $nextPage);

        return json_decode($response->getBody(), true);
    }
}