<?php

namespace App\Mailer;

class CsvReportGenerator
{
    public function generateResource(array $quotes)
    {
        $fp = fopen("php://temp", 'r+');
        fputcsv($fp, ['Date', 'Open', 'High', 'Low', 'Close', 'Volume'], escape: '\\');
        foreach ($quotes as $quote) {
            fputcsv($fp, $quote->jsonSerialize(), escape: '\\');
        }
        rewind($fp);

        return $fp;
    }
}