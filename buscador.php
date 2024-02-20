<?php
// Conexión 
define('HOST_DB', 'localhost');
define('USER_DB', 'root');
define('PASS_DB', '');
define('NAME_DB', 'tarea4');

// Conexión 
function conectar(){
    global $conexion; 

    //Definición global
    $conexion = new mysqli(HOST_DB, USER_DB, PASS_DB, NAME_DB);

    // Verificar la conexión
    if ($conexion->connect_error) {
        die('NO SE HA PODIDO CONECTAR AL MOTOR DE LA BASE DE DATOS: ' . $conexion->connect_error);
    }
}

function desconectar(){
    global $conexion;
    $conexion->close();
}

//Variable para el resultado de la búsqueda
$texto = '';

//Variable para el número de registros encontrados
$registros = '';

if ($_POST) {
    $busqueda = trim($_POST['buscar']);
    $entero = 0;

    if (empty($busqueda)) {
        $texto = 'Búsqueda sin resultados';
    } else {
        // Si hay información para buscar, se abre la conexión
        conectar();
        $conexion->set_charset('utf8');

        //Consulta la base de datos, se utiliza un comparador LIKE
        $sql = "SELECT * FROM prueba4 WHERE APELLIDO LIKE '%" . $busqueda . "%' ORDER BY apellido";

        $resultado = $conexion->query($sql); // Consulta

        //Si hay resultados
        if ($resultado->num_rows > 0) {
            // Registra el número de resultados
            $registros = '<p>Se han encontrado ' . $resultado->num_rows . ' registros </p>';

            // Se almacenan las cadenas de resultado 
            while ($fila = $resultado->fetch_assoc()) {
                $texto .= $fila['nombre'] . ' ';
                $texto .= $fila['apellido'] . '<br />';
            }
        } else {
            $texto = "Sin resultados en la base de datos";
        }

        // No deja conexiones abiertas
        desconectar();
    }
}
?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
<meta charset='utf-8'>
</head>
<body>
    <h1>Buscador</h1>
    <form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <input id="buscar" name="buscar" type="search" placeholder="Buscar aquí..." autofocus >
        <input type="submit" name="buscador" value="buscar">
    </form>
    <?php
    // Resultado, número de registros y contenido. echo $registros;
    echo $registros;
    echo $texto;
    ?>
</body>
</html>