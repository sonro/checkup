<?php

function moveExample(string $filename)
{
    if (!file_exists($filename)) {
        $example = $filename.'.example';
        if (!file_exists($example)) {
            echo "Error no $filename file. Please create one\n";
            die;
        }
        copy($example, $filename);
    }
}
