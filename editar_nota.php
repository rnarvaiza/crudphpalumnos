<?php
include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
    die();
}

$config = include 'config.php';

$resultado = [
    'error' => false,
    'mensaje' => ''
];

if (!isset($_GET['id'])) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'El alumno no existe';
}

if (isset($_POST['submit'])) {
    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $nota = [
            "id"    => $_GET['id'],
            "asignatura"   => $_POST['asignatura'],
            "nota" => $_POST['nota'],
            "observaciones"    => $_POST['observaciones'],
            "alumnoid" => $_POST['alumnoid'],
        ];

        $consultaSQL = "UPDATE notas SET
                 asignatura = :asignatura,
                 nota = :nota,
                 observaciones =:observaciones,
        WHERE id = :id";
        $consulta = $conexion->prepare($consultaSQL);
        $consulta->execute($nota);

    } catch(PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
}

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $id = $_GET['id'];
    $consultaSQL = "SELECT * FROM notas WHERE id =" . $id;

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();

    $nota = $sentencia->fetch(PDO::FETCH_ASSOC);

    if (!$nota) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'No se ha encontrado el alumno';
    }

} catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
}
?>

<?php require "templates/header.php"; ?>

<?php
if ($resultado['error']) {
    ?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $resultado['mensaje'] ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<?php
if (isset($_POST['submit']) && !$resultado['error']) {
    ?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    Se actualizó la nota correctamente
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<?php
if (isset($nota) && $nota) {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4">Editando la nota del alumno <?= escapar($nota['alumnoid']) . ' ' . escapar($nota['alumnoid'])  ?></h2>
                <hr>
                <form method="post">
                    <div class="form-group">
                        <label for="nombre">Asignatura</label>
                        <input type="text" name="asignatura" id="asignatura" value="<?= escapar($nota['asignatura']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="apellidos">Nota</label>
                        <input type="text" name="nota" id="nota" value="<?= escapar($nota['nota']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="apellidos">Observaciones</label>
                        <input type="text" name="observaciones" id="observaciones" value="<?= escapar($nota['observaciones']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
                        <input type="submit" name="submit" class="btn btn-primary" value="Actualizar">
                        <a class="btn btn-primary" href="index.php">Regresar al alumno</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}
?>

<?php require "templates/footer.php"; ?>