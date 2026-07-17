<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('sitemap:generate', function () {
    $base = config('app.url');
    $pages = [
        '/' => ['daily', '1.0'],
        '/vote' => ['daily', '0.9'],
        '/medias' => ['weekly', '0.8'],
        '/contact' => ['weekly', '0.7'],
        '/mentions-legales' => ['monthly', '0.3'],
        '/confidentialite' => ['monthly', '0.3'],
        '/cgu' => ['monthly', '0.3'],
        '/reglement' => ['monthly', '0.3'],
    ];
    $sitemap = \Spatie\Sitemap\Sitemap::create();
    foreach ($pages as $path => [$freq, $priority]) {
        $sitemap->add(
            \Spatie\Sitemap\Tags\Url::create($base . $path)
                ->setChangeFrequency($freq)
                ->setPriority($priority)
        );
    }
    $sitemap->writeToFile(public_path('sitemap.xml'));
    $this->info('Sitemap generated successfully at ' . public_path('sitemap.xml'));
})->purpose('Generate sitemap.xml');
