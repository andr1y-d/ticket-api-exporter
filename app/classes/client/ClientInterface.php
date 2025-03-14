<?php

namespace App\client;

interface ClientInterface
{
    public function __construct(string $subdomain, string $email, string $apiToken);
    public function fetchIncrementalTickets(): array;
}