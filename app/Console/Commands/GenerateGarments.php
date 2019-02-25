<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use JKatzen\QueueMonitor\QueueStatus;

use App\Models\ProductModelTemplate;
use App\Models\Garment;

class GenerateGarments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-garments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate garments for product model templates';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$templates = ProductModelTemplate::get();
		
		foreach($templates as $template) {
			$garment = Garment::getGuessedByTemplate($template);
			$template->garment_id = $garment->id;
			$template->save();
		}
    }
}
