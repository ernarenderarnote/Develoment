<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

use Lib\Crypt\Crypt;

class DecodeCrypt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:decode-crypt {data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Decode Crypt code';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = $this->argument('data');

        $crypt = new Crypt;
        $crypt->setKey(env('KZ_API_SECRET', ''));
        $crypt->setComplexTypes(true);
        $crypt->setData($data);
        $requestBody = $crypt->decrypt();

        $this->line('--------------------------');
        $this->line('Decoded:');
        $this->line('');
        $this->line(print_r($requestBody, true));
        $this->line('');
    }
}
