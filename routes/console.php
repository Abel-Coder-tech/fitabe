<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('sitemap:generate', function () {
    $base = rtrim(config('app.url'), '/');

    $pages = [
        ['loc' => '', 'priority' => '1.0', 'freq' => 'daily'],
        ['loc' => '/vote', 'priority' => '0.9', 'freq' => 'daily'],
        ['loc' => '/medias', 'priority' => '0.8', 'freq' => 'weekly'],
        ['loc' => '/contact', 'priority' => '0.7', 'freq' => 'weekly'],
        ['loc' => '/mentions-legales', 'priority' => '0.3', 'freq' => 'monthly'],
        ['loc' => '/confidentialite', 'priority' => '0.3', 'freq' => 'monthly'],
        ['loc' => '/cgu', 'priority' => '0.3', 'freq' => 'monthly'],
        ['loc' => '/reglement', 'priority' => '0.3', 'freq' => 'monthly'],
    ];

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    foreach ($pages as $page) {
        $xml .= "  <url>\n";
        $xml .= '    <loc>' . $base . $page['loc'] . "</loc>\n";
        $xml .= '    <lastmod>' . now()->toDateString() . "</lastmod>\n";
        $xml .= '    <changefreq>' . $page['freq'] . "</changefreq>\n";
        $xml .= '    <priority>' . $page['priority'] . "</priority>\n";
        $xml .= "  </url>\n";
    }

    $xml .= '</urlset>' . "\n";

    file_put_contents(public_path('sitemap.xml'), $xml);

    $this->info('Sitemap generated: ' . public_path('sitemap.xml'));
})->purpose('Generate sitemap.xml');
