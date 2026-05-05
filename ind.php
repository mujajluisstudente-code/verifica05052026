<?php




?>


<h2> Test Connessione Database/File</h2>
<?php

$testFile = __DIR__ . '';
$writeTest = @file_put_contents($testFile, 'Test write at ' . date('Y-m-d H:i:s'));
if ($writeTest !== false) {
    echo '<p class="success"></p>';
    @unlink($testFile); 
} else {
    echo '<p class="error"></p>';
}

