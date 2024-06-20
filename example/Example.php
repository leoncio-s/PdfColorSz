<?php
    require_once("../src/PDFColorSz.php");

    $pdf = new PDFColorSz("./Teste.pdf"); // Retorna um arquivo json

    $values = $pdf->getValues(); // Converte o json em array

    print_r($values);
?>