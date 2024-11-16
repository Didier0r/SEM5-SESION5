<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include_once($_SERVER['DOCUMENT_ROOT'] . '/semana5/tallermvcphp/routes.php');
    require_once(CONTROLLER_PATH . 'facturaController.php');

   
    $object = new facturaController();
    $rows = $object->select(); // Datos principales
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once(ROOT_PATH . 'header.php'); ?>
    <title>Facturas</title>
</head>
<body>
    <?php require_once(VIEW_PATH . 'navbar/navbar.php'); ?>
    
    <section class="intro">
        <div class="container">
            <div class="mb-3"></div>
            <div class="mb-3">
                <a href="create.php" class="btn btn-primary">Agregar</a>
                <!-- Botón de imprimir -->
                <a href="#" id="btnImprimir" class="btn btn-info">Imprimir</a>
            </div>
            <div class="table-responsive table-scroll" data-mdb-perfect-scrollbar="true" style="position: relative; height:700px;">
                <table id="myTabla" class="table table-striped mb-0">
                    <thead style="background-color: #002d72;">
                        <tr>
                            <th scope="col">NUMERO</th>
                            <th scope="col">FECHA</th>
                            <th scope="col">CLIENTE</th>
                            <th scope="col">OPERACIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ((array)$rows as $row) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['numero']) ?></td>
                                <td><?= htmlspecialchars($row['fecha']) ?></td>
                                <td><?= htmlspecialchars($row['cliente']) ?></td>
                                <td>
                                    <a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#idver<?= $row['numero'] ?>">Visualizar</a>
                                    <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#iddel<?= $row['numero'] ?>">Anular</a>
                                    <!-- Se incluyen los modales -->
                                    <?php 
                                        include('viewModal.php'); 
                                        include('deleteModal.php'); 
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>  
        </div>  
    </section>

    <!-- Área de impresión -->
    <div class="container" id="ventana" style="display:none;">
        <div class="mb-3">
            <h2 style="font-size: 3.00rem;">Listado de facturas</h2>
        </div>
        <div class="table-responsive table-scroll" data-mdb-perfect-scrollbar="true" style="position: relative; height:700px;">
            <table class="table table-striped mb-0" style="font-size: 2.00rem;">
                <thead>
                    <tr>
                        <th colspan="1" scope="col">NUMERO</th>
                        <th colspan="3" scope="col">FECHA</th>
                        <th colspan="3" scope="col">CLIENTE</th>
                        <th colspan="3" scope="col">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ((array)$rows as $row) { ?>
                        <tr>
                            <td colspan="1"><?= htmlspecialchars($row['numero']) ?></td>
                            <td colspan="3"><?= htmlspecialchars($row['fecha']) ?></td>
                            <td colspan="3"><?= htmlspecialchars($row['cliente']) ?></td>
                            <td colspan="3"><?= htmlspecialchars($row['total']) ?></td>     
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>  
    </div>   
   

</body>
<?php include_once(ROOT_PATH . 'footer.php'); ?>


<script>
    $(document).ready(function () {
       
        $('#myTabla').DataTable({
            language: {
                url: '../../assets/js/es-ES.json'
            },
            paging: true,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, 'Todos']
            ],
            order: [[1, 'desc']]
        });

        // Lógica para botón de impresión
        $('#btnImprimir').on('click', function () {
            var printWindow = window.open('', '', 'width=800,height=600');
            var content = document.getElementById('ventana').innerHTML;
            printWindow.document.write('<html><head><title>Impresión de Facturas</title></head><body>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        });
    });
</script>
</html>
