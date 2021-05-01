<?php
include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
    die();
}

$error = false;
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

    if (isset($_POST['apellido'])) {
        $consultaSQL = "SELECT * FROM notas WHERE notas.alumnoid LIKE '%" . $_GET['id'] . "%'";
    } else {
        $consultaSQL = "SELECT * FROM notas WHERE notas.alumnoid LIKE '%" . $_GET['id'] . "%'";
    }

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();

    $alumnos = $sentencia->fetchAll();

} catch(PDOException $error) {
    $error= $error->getMessage();
}

$titulo = isset($_POST['id']) ? 'Lista de notas de (' . $_GET['nombre'] . ')'  : 'Lista de notas de ' . $_GET['nombre'];
?>

<?php include "templates/header.php"; ?>

<?php
if ($error) {
    ?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
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
                <a href="<?= 'crear_notas.php?id=' . escapar($alumno["id"]) ?>" class="btn btn-primary mt-4">Añadir nueva nota</a>
                <a href="index.php"  class="btn btn-primary mt-4">Volver</a>
                <hr>
                <form method="post" class="form-inline">
                    <div class="form-group mr-3">
                        <input type="text" id="asignatura" name="asignatura" placeholder="Buscar por asignatura" class="form-control">
                    </div>
                    <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
                    <button type="submit" name="submit" class="btn btn-primary">Ver resultados</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-3">Lista de notas</h2>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#id nota</th>
                        <th>#id alumno</th>
                        <th>asignatura</th>
                        <th>nota</th>
                        <th>observaciones</th>
                        <th>acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($alumnos && $sentencia->rowCount() > 0) {
                        foreach ($alumnos as $fila) {
                            ?>
                            <tr>
                                <td><?php echo escapar($fila["id"]); ?></td>
                                <td><?php echo escapar($fila["alumnoid"]); ?></td>
                                <td><?php echo escapar($fila["asignatura"]); ?></td>
                                <td><?php echo escapar($fila["nota"]); ?></td>
                                <td><?php echo escapar($fila["observaciones"]); ?></td>
                                <td>
                                    <a href="<?= 'eliminar_nota.php?id=' . escapar($fila["id"]) ?>">🗑️Borrar</a>
                                    <a href="<?= 'editar_nota.php?id=' . escapar($fila["id"]) ?>">✏️Editar</a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    <tbody>
                </table>
            </div>
        </div>
    </div>

<?php include "templates/footer.php"; ?>