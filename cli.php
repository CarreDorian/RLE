<?php

include 'src/advenced_en_decode.php';

// check that all parameters exist
if (!isset($argv[1])) echo "parrameters forget\n";
elseif (!isset($argv[2])) echo "parrameters forget\n";
elseif (!isset($argv[3])) echo "parrameters forget\n";
else {

    // verification of the action to do.
    switch ($argv[1]) {
        // compress the file give in first argument
        case "encode":
            if (encode_advanced_rle($argv[2], $argv[3])) echo "ERROR : encode\n";
            else echo "OK\n";
            break;
        // decompress the file give in first argument
        case "decode":
            if (decode_advanced_rle($argv[2], $argv[3])) echo "ERROR : decode\n";
            else echo "OK\n";
            break;
        // compress file give in first argument and decompress it
        case "duo":
            if (!isset($argv[4])) {
                echo "parrameters forget\n";
                break;
            }
            if (encode_advanced_rle($argv[2], $argv[3])) {
                echo "ERROR : encode\n";
                break;
            } else echo "OK - ";
            if (decode_advanced_rle($argv[3], $argv[4])) echo "ERROR : decode\n";
            else echo "OK\n";
            break;
        default:
            echo "ERROR : bad parrameters\n";
    }

}

?>
