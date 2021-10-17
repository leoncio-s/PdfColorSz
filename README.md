#PDFColorSz
Programa que utiliza os binários do poppler-utils e ghostscript para analisar arquivos pdf's e extrair as informações de tamanho e se o arquivo é colorido ou preto e branco. O programa organiza essa informações e retorna um arquivo json com as informções de cada página do arquivo.

# Requesitos:

poppler-utils:  0.86.*

ghostscript:    9.50


# Como usar:
    
    $pdf = new ColorSz("file.pdf");     
    $values = json_decode($pdf->values);
