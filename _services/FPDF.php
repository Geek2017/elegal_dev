<?php 

namespace Services;

use FPDF as SetASignFPDF;
use Illuminate\Support\Facades\Cache;

class FPDF extends SetASignFPDF
{
    public $angle=0;
    public $watermarkStr = '';
    public $isNeedMargin = false;

    public $addMargin = false;
    public $showLogoAsBackground = false;

    public function AddPage($orientation='', $size='', $rotation=0)
    {
        // Start a new page
        if($this->state==3)
            $this->Error('The document is closed');
        $family = $this->FontFamily;
        $style = $this->FontStyle.($this->underline ? 'U' : '');
        $fontsize = $this->FontSizePt;
        $lw = $this->LineWidth;
        $dc = $this->DrawColor;
        $fc = $this->FillColor;
        $tc = $this->TextColor;
        $cf = $this->ColorFlag;
        if($this->page>0)
        {
            // Page footer
            $this->InFooter = true;
            $this->Footer();
            $this->InFooter = false;
            // Close page
            $this->_endpage();
        }
        // add margins before creating one
        if ($this->addMargin) {
            $this->SetMargins(12.7,25.4,12.7);
        }
        // Start new page
        $this->_beginpage($orientation,$size,$rotation);
        // add log background
        if ($this->showLogoAsBackground) {
            $this->addBackgroundLogo($this);
        }
        // Set line cap style to square
        $this->_out('2 J');
        // Set line width
        $this->LineWidth = $lw;
        $this->_out(sprintf('%.2F w',$lw*$this->k));
        // Set font
        if($family)
            $this->SetFont($family,$style,$fontsize);
        // Set colors
        $this->DrawColor = $dc;
        if($dc!='0 G')
            $this->_out($dc);
        $this->FillColor = $fc;
        if($fc!='0 g')
            $this->_out($fc);
        $this->TextColor = $tc;
        $this->ColorFlag = $cf;
        // Page header
        $this->InHeader = true;
        $this->Header();
        $this->InHeader = false;
        // Restore line width
        if($this->LineWidth!=$lw)
        {
            $this->LineWidth = $lw;
            $this->_out(sprintf('%.2F w',$lw*$this->k));
        }
        // Restore font
        if($family)
            $this->SetFont($family,$style,$fontsize);
        // Restore colors
        if($this->DrawColor!=$dc)
        {
            $this->DrawColor = $dc;
            $this->_out($dc);
        }
        if($this->FillColor!=$fc)
        {
            $this->FillColor = $fc;
            $this->_out($fc);
        }
        $this->TextColor = $tc;
        $this->ColorFlag = $cf;
    }

    function Header()
    {
        if ($this->isNeedMargin){
            if ($this->CurOrientation == 'P') {
                
            } else {
                $this->SetLeftMargin(25.4);
            }
        }

        // if ($this->watermarkStr != '') {
        //     //Put the watermark
        //     $this->SetFont('Arial', 'B', 50);
        //     $this->SetTextColor(255, 192, 203);
        //     // $this->Watermark(35, 190, $this->watermarkStr, 45);
        // }
    }

    public function getBufferData()
    {
        return $this->buffer;
    }

    /**
     * Get base64 image data url.
     *
     * @param mixed $url       Image URL.
     * @param mixed $cacheName Cache name to be used to cache image.
     *
     * @return string
     */
    function getBase64Image($url, $cacheName)
    {
        if (Cache::has($cacheName)) {
            return Cache::get($cacheName);
        }

        $base64Image = $this->toBase64ImageUrl(base64_encode(file_get_contents($url)));

        Cache::forever($cacheName, $base64Image);

        return $base64Image;
    }

    /**
     * Format base64 image string to FPDF Image function acceptable url.
     *
     * @param string $base64String
     * @return string
     */
    function toBase64ImageUrl($base64String)
    {
        return 'data://text/plain;base64,' . $base64String;
    }

    function createWatermark(SetASignFPDF $pdf)
    {
        # code...
    }

    public function addBackgroundLogo(SetASignFPDF $pdf)
    {
        $pdf->SetAlpha(0.15); // set alpha to semi-transparency
        if ($pdf->CurOrientation == 'P') {
            $pdf->Image($pdf->getBase64Image(base_path('public/img/logo.png'), 'pdf.company.logo'), 50, 60, 0, 0, 'PNG');
        } else {
            $pdf->Image($pdf->getBase64Image(base_path('public/img/logo.png'), 'pdf.company.logo'), 60, 40, 0, 0, 'PNG');
        }
        $pdf->SetAlpha(1); // set opacity to 100
    }

    function Rotate($angle,$x=-1,$y=-1)
    {
        if($x==-1)
            $x=$this->x;
        if($y==-1)
            $y=$this->y;
        if($this->angle!=0)
            $this->_out('Q');
        $this->angle=$angle;
        if($angle!=0)
        {
            $angle*=M_PI/180;
            $c=cos($angle);
            $s=sin($angle);
            $cx=$x*$this->k;
            $cy=($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
        }
    }

    function _endpage()
    {
        if($this->angle!=0)
        {
            $this->angle=0;
            $this->_out('Q');
        }
        parent::_endpage();
    }
    function SetDash($black=null, $white=null)
    {
        if($black!==null)
            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }

    function Footer()
    {
    	$this->SetTextColor(0, 0, 0);
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial','I',8);
        // Print centered page number
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }

    function Watermark($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
    }
}
