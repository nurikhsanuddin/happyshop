<?php
function format_uang($angka){ 
    $hasil = "Rp. " . number_format($angka,0, ',' , '.'); 
    return $hasil; 
}