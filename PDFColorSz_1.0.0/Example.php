<?php
    include_once "./ColorSz.php";

    $pdf = new PDFColorSz("./Teste.pdf"); // Retorna um arquivo json

    $values = Json_decode($pdf->values); // Converte o json em array

    print_r($values);
?>