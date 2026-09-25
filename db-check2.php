<?php

foreach (['product_history', 'product_histories'] as $table) {
    echo '== '.$table.' =='.PHP_EOL;
    if (! Schema::hasTable($table)) {
        echo '   MISSING'.PHP_EOL.PHP_EOL;
        continue;
    }
    echo '   columns: '.implode(', ', Schema::getColumnListing($table)).PHP_EOL;
    echo '   rows   : '.DB::table($table)->count().PHP_EOL;
    echo '   DDL    : '.DB::select('SHOW CREATE TABLE '.$table)[0]->{'Create Table'}.PHP_EOL.PHP_EOL;
}
