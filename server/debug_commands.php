<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$commands = App\Models\Command::orderBy('id', 'desc')->take(5)->get();
foreach ($commands as $c) {
    echo 'ID: '.$c->id."\n";
    echo 'Status: '.$c->status."\n";
    echo 'Command: '.$c->command."\n";
    echo 'Output: '.substr($c->output ?? '', 0, 100)."\n";
    echo "-------------------\n";
}
