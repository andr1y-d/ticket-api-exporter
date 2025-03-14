<?php

namespace App\exporter;

interface ExporterInterface
{
    public function prepareFile(string $filename, string $processorName): void;
    public function export(array $data, string $filename): void;
}