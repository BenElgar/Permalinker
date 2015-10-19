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
        //$data = [];

        $path = storage_path('app/tmp/') . $this->id . '/';
        $safe_url = escapeshellcmd($this->url);
        $user_agent = env('SCRAPER_USER_AGENT');

        $return_status = -1;
        $output = [];

        $result = exec(
            'httrack '. $safe_url .' -w -O "'. $path .'"  -z --depth=1 --user-agent="'. $user_agent .'" --near --urlhack -s0 -N1099 --preserve',
            $output,
            $return_status
        );

        // Upload to S3
        $s3 = AWS::createClient('s3');
        $s3->uploadDirectory($path, 'permalinker-snapshots', $this->id.'/', [
             'concurrency' => 20,
        ]);

        Snapshot::fillItem($this->id, $path);

        echo(route('snapshot.show', $this->id));
        echo("\n");
        echo($return_status);
        echo("\n");
    }
}
