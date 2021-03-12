<?php
    $__meta=[
        "Author" => "Leôncio Souza",
        "Version" => "1.2",
        "Name" => 'PdfColorSz'
    ];

class PDFColorSz
{
    protected $pdf;
    
    public $values;
    
    public static $binPdfInfo;

    public static $binGs;

    public function __construct($file)
    {
        $ext = explode(".", $file)[2];

        if($ext === "pdf" || $ext === "Pdf" || $ext === "PDF" )
        {
            $this->pdf = $file;
            $this->get();
        } else {
            throw new Exception("$file não é um arquivo valido");
        }
    }

    public function getBinaryPdfInfo()
    {
        if (empty(static::$binPdfInfo))
        {
            static::$binPdfInfo = trim(trim(getenv('PDFINFO_BIN'), '\\/" \'')) ?: 'pdfinfo';
        }

        return static::$binPdfInfo;
    }

    public function getBinaryGs()
    {
        if (empty(static::$binGs))
        {
            static::$binGs = trim(trim(getenv('GS_BIN'), '\\/" \'')) ?: 'gs';
        }

        return static::$binGs;
    }

    public function get()
    {
        (object) $data=array();
        $cmdGs = escapeshellarg($this->getBinaryGs());
        exec("$cmdGs -o - -q -sDEVICE=inkcov $this->pdf", $output, $retVal);

        if($retVal === 1) {
            throw new Exception("Falha ao abrir arquivo PDF");
        }
        if($retVal === 127) {
            throw new Exception("GhostScript não instalado");
        }

        $regularExpression = "/ {1}[0-9].{5}[0-9]  {1}[0-9].{5}[0-9]  {1}[0-9].{5}[0-9]  {1}[0-9].{5}[0-9] CMYK OK/";
        

        $p = 1;

        foreach($output as $v)
        {
            if (preg_match($regularExpression, $v) != 0)
            {
                $array = str_replace(" ",";", $v);
                //inserção de novo código
                $array = str_replace(";;", ";", $array);
                $array = explode(";", $array);
                $array = array_diff($array, ["","CMYK","OK"]);

                if($array[1] == 0 && $array[2] == 0 && $array[3] == 0 && $array[4] > 0 )
                {
                    $data["page" . $p]["color"] = "preto e branco";
                }else if($array[1] == 0 && $array[2] == 0 && $array[3] == 0 && $array[4] == 0 )
                {
                    $data["page" . $p]["color"]= "em branco";
                }
                else
                {
                    $data["page" . $p]["color"]= "colorido";
                }
                
            } else {
                continue;
            }
            $p ++;
        }

        $cmdPdi = escapeshellarg($this->getBinaryPdfInfo());
        exec("$cmdPdi -f 1 -l " . count($output) . " -box $this->pdf | grep MediaBox", $out);

        $x = 1;
        foreach($out as $v)
        {           
            $val = explode("  ", $v);
            $sz = array(
                round($val[count($val) - 1] * 0.352778),
                round($val[count($val) - 2] * 0.352778)
            );
            if($sz[0] > $sz[1])
            {
                $data["page" . $x]["height"] = $sz[1];
                $data["page" . $x]["width"] = $sz[0];
            } else
            {
                $data["page" . $x]["height"] = $sz[0];
                $data["page" . $x]["width"] = $sz[1];
            }
            $x++;
        }

        return $this->values = json_encode($data);
    }
}
?>