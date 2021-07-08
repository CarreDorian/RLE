<?php

    // the compress code :
function encode_advanced_rle(string $path_to_encode, string $result_path) {
    // check if the picture exist
    if (!file_exists($path_to_encode)) return 1;
    $codemotif = "";
    $hexa = bin2hex(file_get_contents($path_to_encode));
    $taille = strlen($hexa);

        // set a space all the two carracters. I'm gonna call it a motif
    for ($i = 1; $i < $taille; $i += 2)
        $codemotif = $codemotif.$hexa[$i-1].$hexa[$i].' ';
    
    $taille2 = strlen($codemotif);
    $compress = "";

    $i = 2;
    while ($i <= $taille2 - 3) {
        // compare the motifs two by two, and since they are equal, concatenate them.
        $pack = $codemotif[$i-2].$codemotif[$i-1].' ';
        $pack2 = $codemotif[$i+1].$codemotif[$i+2].' ';
        $count = 0;
        if ($pack == $pack2) {
            $count++;
            do {
                $count++;
                $i += 3;
                if ($i+1 >= $taille2) break;
                $pack = $codemotif[$i-2].$codemotif[$i-1].' ';
                $pack2 = $codemotif[$i+1].$codemotif[$i+2].' ';
            } while ($pack == $pack2);
            $compress .= $count.' '.$pack;
            $i += 3;
            if ($i+1 >= $taille2) break;
            $pack = $codemotif[$i-2].$codemotif[$i-1].' ';
            $pack2 = $codemotif[$i+1].$codemotif[$i+2].' ';
            $count = 0;
        }
        // compare the motifs two by two, and since they are differents.
        if ($pack != $pack2) {
            $j = $i;
            do {
                $count++;
                $i += 3; 
                if ($i + 1 >= $taille2) {
                    $count++;
                    $i += 3;
                    break;
                }
                $pack = $codemotif[$i-2].$codemotif[$i-1].' ';
                $pack2 = $codemotif[$i+1].$codemotif[$i+2].' ';
            } while ($pack != $pack2);
            $compress .= '00 '.$count.' ';
            // add the last motif if they are an odd number
            while ($j < $i) {
                $pack = $codemotif[$j-2].$codemotif[$j-1].' ';
                $compress = $compress.$pack;
                $j += 3;
            }
        }
    }

    // make a save 
    if (file_exists($result_path)) unlink($result_path);
    file_put_contents($result_path, $compress);
    return 0;
}

    // the decompress code
function decode_advanced_rle(string $path_to_encode, string $result_path) {
    // check if the picture exist
    if (!file_exists($path_to_encode)) return 1;
    $pack = explode(' ', file_get_contents($path_to_encode));
    $taille = count($pack);
    $decompress = "";
    $i = 0;

    // '00' is follow by a x number, says that they are no same motifs
    // for the x motif and add them to the decompress file
    while ($i < $taille) {
        switch ($pack[$i]) {
            case '00':
                $count = ((int) $pack[++$i]) + $i;
                while (++$i <= $count){
                    $decompress = $decompress.$pack[$i];}
                break;
            // multiply the compress motif
            default:
                $count = (int) $pack[$i++];
                $j = 0;
                while ($j++ < $count) 
                    $decompress = $decompress.$pack[$i]; 
                $i++;
        }
    }
    // save the motif
    $decompress = hex2bin($decompress);
    if (file_exists($result_path)) unlink($result_path);
        file_put_contents($result_path, $decompress);
    
    return 0;
}

?>