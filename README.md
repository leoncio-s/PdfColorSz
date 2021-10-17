# PDFColorSz

Este programa utiliza os binários do poppler-utils(pdfinfo) e ghostscript para analisar arquivos pdf's e extrair as informações de tamanho e cor de cada página. O programa organiza essa informações e retorna um arquivo json com as informções de cada página do arquivo, infomando o tamanho da página e se a cor é colorida ou somente preto.

# Requesitos:

poppler-utils:  0.86.*

ghostscript:    9.50


# Como usar:
    
    $pdf = new ColorSz("file.pdf");     
    $values = json_decode($pdf->values);
