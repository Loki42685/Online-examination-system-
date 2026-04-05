<?php

require('fpdf186/fpdf.php'); // or fpdf186

if(class_exists('FPDF')){
    echo "FPDF LOADED ✅";
} else {
    echo "FPDF NOT LOADED ❌";
}
?>