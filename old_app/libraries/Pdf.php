<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter PDF Library
 *
 * @package			CodeIgniter
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Muhanz
 * @license			MIT License
 * @link			https://github.com/hanzzame/ci3-pdf-generator-library
 *
 */

require_once(dirname(__FILE__) . '/dompdf/autoload.inc.php');
use Dompdf\Dompdf;

class Pdf
{
	public function create($html,$filename)
    {
	    $dompdf = new Dompdf();
		$dompdf->set_option('enable_html5_parser', TRUE);
	    $dompdf->loadHtml($html);
	    $dompdf->render();
	    //$dompdf->stream($filename.'.pdf');
		$pdf_string =   $dompdf->output();
        file_put_contents($filename, $pdf_string ); 
  }
}