<?php

include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
    die();
}

if (isset($_POST['submit'])) {
    $resultado = [
        'error' => false,
        'mensaje' => 'La nota ' . escapar($_POST['id']) . ' de la asignatura ' . escapar($_POST['asignatura']) . ' ha sido agregada con éxito'
    ];

    $config = include 'config.php';

    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $alumno = [
            "id" => $_GET['id'],
            "nombre" => $_POST['nombre'],
            "apellidos" => $_POST['apellidos'],
            "email" => $_POST['email'],
            "telefono" => $_POST['telefono'],
            "fecha_nacimiento" => $_POST['fecha_nacimiento'],
        ];

        $nota = [
            "asignatura"   => $_POST['asignatura'],
            "nota" => $_POST['nota'],
            "observaciones"    => $_POST['observaciones'],
            "alumnoid" => $_POST['alumnoid'],
        ];

        $consultaSQL = "INSERT INTO notas (asignatura, nota, observaciones, alumnoid)";
        $consultaSQL .= "values (:" . implode(", :", array_keys($nota)) . ")";

        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute($nota);

    } catch(PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
}
?>

<?php include 'templates/header.php'; ?>

<?php
if (isset($resultado)) {
    ?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
                    <?= $resultado['mensaje'] ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4">Nueva nota para #<?= escapar($alumno['nombre']) . ' ' . escapar($alumno['nombre'])?></h2>
                <hr>
                <form method="post">
                    <div class="form-group">
                        <label for="alumnoid">Id alumno</label>
                        <input type="text" name="alumnoid" id="alumnoid" value="<?= escapar($_GET['alumnoid'])?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="asignatura">Asignatura</label>
                        <input type="text" name="asignatura" id="asignatura" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nota">Nota</label>
                        <input type="text" name="nota" id="nota" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <input type="text" name="observaciones" id="Observaciones" class="form-control">
                    </div>
                    <div class="form-group">
                        <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
                        <input type="submit" name="submit" class="btn btn-primary" value="Enviar">
                        <a class="btn btn-primary" href="notas.php">Regresar a notas</a>
                        <a class="btn btn-primary" href="index.php">Regresar a alumnos</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include 'templates/footer.php'; ?>