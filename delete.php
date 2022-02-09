<?php

function deleteFile($address, $filename) {
    // $address = $_GET["address"];
    // $filename = $_GET["filename"];
    echo $address;
    echo $filename;
    unlink($address.'/'.$filename.'-large.webp');
    unlink($address.'/'.$filename.'-large.jpg');
    unlink($address.'/'.$filename.'-large.png');
    unlink($address.'/'.$filename.'-medium.webp');
    unlink($address.'/'.$filename.'-medium.jpg');
    unlink($address.'/'.$filename.'-medium.png');
    unlink($address.'/'.$filename.'-small.webp');
    unlink($address.'/'.$filename.'-small.jpg');
    unlink($address.'/'.$filename.'-small.png');
    unlink($address.'/'.$filename.'-thumbnail.webp');
    unlink($address.'/'.$filename.'-thumbnail.jpg');
    unlink($address.'/'.$filename.'-thumbnail.png');
    unlink($address.'/'.$filename.'.webp');
    unlink($address.'/'.$filename.'.jpg');
    unlink($address.'/'.$filename.'.png');
}

?>