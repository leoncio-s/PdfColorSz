<?php
    $__meta=[
        "Author" => "Leôncio Souza",
        "Version" => "1.0.0",
        "Name" => 'PdfColorSz'
    ];

    require __DIR__ . '/vendor/autoload.php';

    use \Howtomakeaturn\PDFInfo\PDFInfo;

class PDFColorSz{
    protected $pdf;
    
    public $values;
    
    public static $binPdfInfo;

    public static $binGs;

    public function __construct($file){
        $this->pdf = $file;
        $this->get();
    }

    public function getBinaryPdfInfo()
    {
        if (empty(static::$binPdfInfo)) {
            static::$binPdfInfo = trim(trim(getenv('PDFINFO_BIN'), '\\/" \'')) ?: 'pdfinfo';
        }

        return static::$binPdfInfo;
    }

    public function getBinaryGs()
    {
        if (empty(static::$binGs)) {
            static::$binGs = trim(trim(getenv('PDFINFO_BIN'), '\\/" \'')) ?: 'gs';
        }

        return static::$binGs;
    }

    public function get(){
        $data=array();
        $pdfinfo = new PDFInfo($this->pdf);
        $cmdPdi = escapeshellarg($this->getBinaryPdfInfo());
        exec("$cmdPdi -f 1 -l $pdfinfo->pages -box $this->pdf | grep MediaBox", $out);
        $x = 1;
        foreach($out as $v){           
            $val = explode("  ", $v);
            $data["page" . $x] = array(
                round($val[count($val) - 1] * 0.352778),
                round($val[count($val) - 2] * 0.352778)
            );
            $x = $x + 1;
        }

        $cmdGs = escapeshellarg($this->getBinaryGs());
        exec("$cmdGs -o - -q -sDEVICE=inkcov $this->pdf", $output);
        
        $p = 1;
        foreach($output as $v){
            $array = explode(" ", $v);
            foreach($array as $key => $val){
                if($val == "" || $val == "CMYK" || $val == "OK"){
                    unset($array[$key]);
                }
            }

            if($array[1]>0 || $array[3]>0 || $array[5]>0){
                array_push($data["page" . $p], "Colorido");

            }elseif(($array[1]==0 || $array[3]==0 || $array[5]==0) && $array[7]>0){
                array_push($data["page" . $p], "Preto e branco");
            }
            
            $p = $p + 1;
        }
        return $this->values = json_encode($data);
    }
}
?>