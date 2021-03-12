Script para retornar cor e tamanho de arquivos pdf.

# Requesitos:

poppler-utils:  0.86.* <br>
ghostscript:    9.50


# Como usar:

    require_once("PDFColorSZ");
    $get = new PDFColorSz("file.pdf");     
    $values = json_decode($pdf->values);
