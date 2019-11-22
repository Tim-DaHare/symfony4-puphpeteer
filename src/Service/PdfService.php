<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Exceptions\Node;

class PdfService {

    private $fs;

    public function __construct(FileSystem $fileSystem) {
        $this->fs = $fileSystem;
    }

    public function generatePdfFromUrl(string $url, bool $deleteAfterwards = true) {
        $puppeteer = new Puppeteer;

        $parselUrl = parse_url($url);
        if (!$parselUrl) {
            return false;
        }

        $filePath = realpath('../var/pdf/').'/'.uniqid('pdf_', true).'.pdf';

        $browser = $puppeteer->launch();
        $page = $browser->newPage();
        try {
            $page->tryCatch->goto($url);
        } catch(Node\Exceptio $e) {
            return false;
        }
        $page->pdf(['path' => $filePath]);

        $file = new File($filePath);

        // if ($deleteAfterwards && file_exists($filePath)) {
        //     $this->fs->remove($filePath);
        // }

        return $file;
    }
}