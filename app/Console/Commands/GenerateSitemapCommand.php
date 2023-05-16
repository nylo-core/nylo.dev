<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Http\Services\DocService;

class GenerateSitemapCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(DocService $docService)
    {
        $sitemap = Sitemap::create(config('app.url'));
        $sitemap->add(Url::create('/')->setPriority(0.95));
        
        $sitemap->add(Url::create('resources')->setPriority(0.90));
        $sitemap->add(Url::create('contributions')->setPriority(0.90));
        $sitemap->add(Url::create('privacy-policy')->setPriority(0.90));
        $sitemap->add(Url::create('terms-and-conditions')->setPriority(0.90));

        $docs = config('project.doc-index');
        if (empty($docs['versions'])) {
            return;
        }

        $latestVersionOfNylo = $docService->getLastestVersionNylo();

        foreach ($docs['versions'] as $version => $versionLinks) {
            if ($version != $latestVersionOfNylo) {
                continue;
            }

            $links = array_values($versionLinks);

            $collectionLinks = collect($links);
            $flattenedCollection = $collectionLinks->flatten();
            $arrayLinks = $flattenedCollection->toArray();

            foreach ($arrayLinks as $link) {
                $urlLink = route('landing.docs', ['version' => $version, 'page' => $link]); 
                $sitemap->add(Url::create($urlLink)->setPriority(0.8));
            }
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
