<?php
$zip = new ZipArchive;
if ($zip->open('InfinityCart.zip') === TRUE) {
    $zip->extractTo('./');
    $zip->close();
    echo 'Success! Files extracted.';
} else {
    echo 'Failed to extract.';
}
?>