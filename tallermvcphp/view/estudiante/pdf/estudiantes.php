<?php
// Desactiva la visualización de errores para evitar salidas antes de generar el PDF
ini_set('display_errors', 0);
error_reporting(0);

// Inicia el búfer de salida para capturar el HTML
ob_start();

include_once ($_SERVER['DOCUMENT_ROOT'].'/semana5/tallermvcphp/routes.php');
require_once(ROOT_PATH.'vendor/autoload.php');

// Incluye el controlador y obtiene los datos
require_once(CONTROLLER_PATH.'estudianteController.php');
$object = new estudianteController(); 
$rows = $object->select();

// Utiliza el espacio de nombres de Html2Pdf
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

try {
    // Captura el contenido HTML de la página que se va a convertir
    include dirname(__FILE__).'/doc/estudiantes_html.php';  // El archivo HTML
    $content = ob_get_clean();  // Obtener el contenido del búfer

    // Verifica que el contenido se ha cargado correctamente
    if (empty($content)) {
        throw new Exception("El contenido HTML no se ha cargado correctamente.");
    }

    // Configura y genera el PDF
    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', 3);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->output('estudiantes.pdf'); // Muestra el PDF al usuario

} catch (Html2PdfException $e) {
    $html2pdf->clean();
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();  // Muestra el mensaje de error en caso de que falle
} catch (Exception $e) {
    // Captura cualquier otro tipo de error y muestra un mensaje adecuado
    echo "Error: " . $e->getMessage();
}
?>
