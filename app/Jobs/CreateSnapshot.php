<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Snapshot;
use Log;
use AWS;
use File;
use DOMDocument;
use DOMXPath;

class CreateSnapshot extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $id;
    protected $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $url)
    {
        $this->id  = $id;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path       = storage_path('app/tmp/') . $this->id . '/';
        $safe_url   = escapeshellcmd($this->url);
        $user_agent = env('SCRAPER_USER_AGENT');

        $return_status = -1;
        $output        = [];

        // Download website
        $result = exec(
            'httrack '. $safe_url .' -w -O "'. $path .'"  -z --depth=1 --user-agent="'. $user_agent .'" --near --urlhack -s0 -N1099',
            $output,
            $return_status
        );

        // Get page title
        $page_title = self::getPageTitle($this->url);

        // Upload to S3
        $s3 = AWS::createClient('s3');
        $s3->uploadDirectory($path, 'permalinker-snapshots', $this->id.'/');

        // Update database
        Snapshot::fillItem($this->id, $page_title);

        // Delete temporary directory
        File::deleteDirectory($path);

    }

    private static function getPageTitle($url)
    {
        $doc = new DOMDocument();
        @$doc->loadHTMLFile($url);
        $xpath = new DOMXPath($doc);
        $title_object = $xpath->query('//title')->item(0);
        return $title_object ? $title_object->nodeValue : 'Unknown';
    }
}
