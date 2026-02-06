<?php

namespace App\Console\Commands;

use App\Http\Services\DocService;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

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
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        // Add static pages
        $this->addStaticPages($sitemap);

        // Add documentation pages
        $latestVersion = $docService->getLastestVersionNylo();
        $docsAdded = $this->addDocumentationPages($sitemap, $latestVersion);
        $tutorialsAdded = $this->addTutorialPages($sitemap, $latestVersion);

        if (! $docsAdded && ! $tutorialsAdded) {
            $this->warn('No documentation or tutorial versions found.');
        }

        try {
            $sitemap->writeToFile(public_path('sitemap.xml'));
            $this->info('Sitemap generated successfully at: '.public_path('sitemap.xml'));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to generate sitemap: '.$e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * Add static pages to the sitemap.
     */
    private function addStaticPages(Sitemap $sitemap): void
    {
        $locales = config('localization.supported_locales', ['en']);

        // Root homepage (non-localized)
        $sitemap->add(Url::create('/')->setPriority(0.95));

        // Localized landing pages, privacy-policy, and terms pages
        foreach ($locales as $locale => $details) {
            $sitemap->add(Url::create("/{$locale}/")->setPriority(0.90));
            $sitemap->add(Url::create("/{$locale}/privacy-policy")->setPriority(0.30));
            $sitemap->add(Url::create("/{$locale}/terms-and-conditions")->setPriority(0.30));
        }

        // Other static pages (non-localized)
        $sitemap->add(Url::create('/resources')->setPriority(0.60));
        $sitemap->add(Url::create('/learn-more/v7')->setPriority(0.70));
    }

    /**
     * Add documentation pages to the sitemap.
     */
    private function addDocumentationPages(Sitemap $sitemap, string $latestVersion): bool
    {
        $docs = config('project.doc-index');

        if (empty($docs['versions'])) {
            return false;
        }

        $added = false;
        foreach ($docs['versions'] as $version => $versionLinks) {
            if ($version !== $latestVersion) {
                continue;
            }

            $locales = array_keys(config('localization.supported_locales', ['en' => []]));
            $links = collect($versionLinks)->flatten()->toArray();

            foreach ($locales as $locale) {
                foreach ($links as $link) {
                    $urlLink = route('landing.docs', ['locale' => $locale, 'version' => $version, 'page' => $link]);
                    $priority = $locale === 'en' ? 0.8 : 0.6;
                    $sitemap->add(Url::create($urlLink)->setPriority($priority));
                    $added = true;
                }
            }
        }

        return $added;
    }

    /**
     * Add tutorial pages to the sitemap.
     */
    private function addTutorialPages(Sitemap $sitemap, string $latestVersion): bool
    {
        $docsTutorials = config('project.doc-tutorials');

        if (empty($docsTutorials['versions'])) {
            return false;
        }

        $added = false;
        foreach ($docsTutorials['versions'] as $version => $versionLinks) {
            if ($version !== $latestVersion) {
                continue;
            }

            foreach ($versionLinks as $tutorial) {
                collect($tutorial)
                    ->pluck('label')
                    ->each(function ($label) use ($version, $sitemap) {
                        $urlLink = route('tutorials.index', ['version' => $version, 'page' => $label]);
                        $sitemap->add(Url::create($urlLink)->setPriority(0.70));
                    });
                $added = true;
            }
        }

        return $added;
    }
}
