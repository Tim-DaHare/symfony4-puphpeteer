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

        $hostname = parse_url($url)['host'];
        if (empty($hostname)) {
            return false;
        }

        $filename = explode('.', $hostname)[1];
        $filePath = realpath('../var/pdf/').'/'.$filename.'.pdf';

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