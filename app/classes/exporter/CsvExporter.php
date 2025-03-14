<?php

namespace App\exporter;

use App\processor\IncrementalTicketProcessor;

class CsvExporter implements ExporterInterface
{
    public function prepareFile(string $filename, string $processorName): void
    {
        $handle = fopen($filename, 'w');

        fputcsv($handle, $this->getHeadersRow($processorName));

        fclose($handle);
    }

    public function export(array $data, string $filename): void
    {
        if (empty($data)) {
            return;
        }

        $handle = fopen($filename, 'a');

        if ($handle === false) {
            echo "Не вдалося відкрити файл для запису.";
            return;
        }

        foreach ($data as $row) {
            if (is_array($row)) {
                fputcsv($handle, $row);
            }
        }

        fclose($handle);
    }

    private function getHeadersRow(string $processorName): array
    {
        $processorToHeadersMapping = [
            IncrementalTicketProcessor::class => [
                'ID',
                'Description',
                'Status',
                'Priority',
                'Agent ID',
                'Agent Name',
                'Agent Email',
                'Contact ID',
                'Contact Name',
                'Contact Email',
                'Group ID',
                'Group Name',
                'Company ID',
                'Company Name',
                'Comments'
            ]
        ];

        return $processorToHeadersMapping[$processorName] ?? [];
    }
}
