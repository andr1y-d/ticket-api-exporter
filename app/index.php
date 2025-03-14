<?php

require 'vendor/autoload.php';

use App\client\ZendeskClient;
use App\exporter\CsvExporter;
use App\exporter\ExporterInterface;
use App\processor\IncrementalTicketProcessor;
use App\processor\ProcessorInterface;

$options = getopt("", ["subdomain:", "email:", "apiToken:", "outputFile:"]);

$subdomain = $options['subdomain'] ?? null;
$email = $options['email'] ?? null;
$apiToken = $options['apiToken'] ?? null;
$outputFile = $options['outputFile'] ?? null;

if (!$apiToken || !$subdomain || !$email || !$outputFile) {
    $subdomain = 'myco5278';
    $email = 'aaannndddriyyyko@gmail.com';
    $apiToken = '2aZiy6C7v849qQ0adxAKAPxauCTlZGK0GlylbjZk';
    $outputFile = 'tickets.csv';
}

class ExportData
{
    private ProcessorInterface $processor;
    private ExporterInterface $exporter;

    public function __construct(string $subdomain, string $email, string $apiToken)
    {
        $this->processor = new IncrementalTicketProcessor(new ZendeskClient($subdomain, $email, $apiToken));
        $this->exporter = new CsvExporter();
    }

    public function process(string $outputFile): void
    {
        $this->exporter->prepareFile($outputFile, get_class($this->processor));
        do {
            try {
                $data = $this->processor->getDataToExport();
            } catch (Exception $e) {
                if ($e->getCode() == 401) {
                    die($e->getMessage());
                }

                echo $e->getMessage();

                continue;
            }

            $this->exporter->export($data, $outputFile);
        } while ($this->processor->hasNotFetchedData());
    }
}

(new ExportData($subdomain, $email, $apiToken))->process($outputFile);