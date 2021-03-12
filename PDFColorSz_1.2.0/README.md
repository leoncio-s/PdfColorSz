Script para retornar cor e tamanho de arquivos pdf.

Requesitos:
php:            7.*
poppler-utils:  0.86.*
ghostscript:    9.50


Como usar:
<?php
    require_once("PDFColorSZ");
    $get = new PDFColorSz("file.pdf");     
    $values = json_decode($pdf->values);
?>