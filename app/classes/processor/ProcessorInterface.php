<?php

namespace App\processor;

use App\client\ClientInterface;

interface ProcessorInterface
{
    public function __construct(ClientInterface $client);
    public function hasNotFetchedData(): bool;
    public function getDataToExport(): array;
}