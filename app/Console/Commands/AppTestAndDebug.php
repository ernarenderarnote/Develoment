<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\ProductModel;

class AppTestAndDebug extends Command
{
    protected $signature = 'app:test-and-debug';
    protected $description = '';

    public function handle()
    {
        $this->line('--------------------------');

        $model = ProductModel::find(187);
        sd($model->price);

        $this->line('Finished');
    }
}
