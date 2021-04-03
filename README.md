Este códido tem como objetivo analisar arquivos pdf e retornar a cor do seu conteúdo e tamanho.

# Requesitos:

poppler-utils:  0.86.* <br>
ghostscript:    9.50


# Como usar:

    require_once("PDFColorSZ");
    $pdf = new PDFColorSz("file.pdf");     
    $values = json_decode($pdf->values);
