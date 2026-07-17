<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function index()
    {
        $pages = [
            ['loc' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('public.vote'), 'priority' => '0.8', 'changefreq' => 'daily'],
            ['loc' => route('public.medias'), 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('public.contact'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => route('public.reglement'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => route('public.mentions-legales'), 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['loc' => route('public.confidentialite'), 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['loc' => route('public.cgu'), 'priority' => '0.3', 'changefreq' => 'yearly'],
        ];

        return response()
            ->view('sitemap', compact('pages'))
            ->header('Content-Type', 'application/xml');
    }
}
