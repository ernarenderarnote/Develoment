<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

use Lib\Crypt\Crypt;

class EncodeCrypt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:encode-crypt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encode Crypt code';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = [
           'order_id' => 48,
           'tracking_number' => 'TN123',
           'item_ids' => [
               259
           ]
        ];

        $crypt = new Crypt;
        $crypt->setKey(env('KZ_API_SECRET', ''));
        $crypt->setComplexTypes(true);
        $crypt->setData($data);
        $requestBody = $crypt->encrypt();

        $this->line('--------------------------');
        $this->line('Encoded:');
        $this->line('');
        $this->line(urlencode(print_r($requestBody, true)));
        $this->line('');
    }
}
