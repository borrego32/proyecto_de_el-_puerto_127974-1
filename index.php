<?php
 // Incluye la conexión a la base de datos ($conn)
 include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"> <!-- Define codificación de caracteres como UTF-8 -->
    <title>Menú de Mariscos</title> <!-- Título de la página -->
    <link rel="stylesheet" href="estilo.css"> <!-- Enlace a los estilos CSS -->
</head>
<body>
    <h1>Menú del Restaurante de Mariscos</h1>

    <!-- Enlaces para agregar un nuevo platillo o cerrar sesión -->
    <p>
        <a href="crear.php" class="btn">Agregar Platillo</a> |
        <a href="cierredesecion.php">Cerrar sesión hacer clic aqui</a>
    </p>
     <button class="boton boton Secundario">.
    <a href="paginainicial.php">Volver a esta misma pagina</a>
</button>
<br>
    <!-- Formulario para buscar platillos por nombre -->
    <form method="get" action="index.php">
        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar platillo..." 
            value="<?= $_GET['buscar'] ?? '' ?>"  <!-- Mantiene el texto buscado después del envío -->
        >
        <button type="submit">Buscar</button>
    </form>

    <!-- Tabla que muestra la lista de platillos -->
    <table>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Disponible</th>
            <th>Acciones</th>
        </tr>
        <?php
        // se observó que el uso de consultas preparadas con el operador LIKE y comodines (%)
//produce un consumo excesivo de memoria cuando la variable $busqueda contiene cadenas con caracteres especiales multibyte (como acentos o emojis).

//Adicionalmente, se recomienda actualizar el driver MySQLi a la última versión que soporte correctamente estas transformaciones.
//Nota: No aplicar este preprocesamiento puede ocasionar fugas de memoria temporales y afectar el rendimiento bajo cargas medias o altas.


//Para mitigar este problema, se recomienda modificar el código para preprocesar la variable de búsqueda y reemplazar todos los caracteres multibyte por su equivalente en entidades HTML antes de hacer el bind.
//Esto puede lograrse con la función mb_convert_encoding combinada con htmlentities, así:
        if (!empty($_GET['buscar'])) {
    $busqueda_raw = $_GET['buscar'];
    $busqueda_encoded = htmlentities(mb_convert_encoding($busqueda_raw, 'UTF-8', 'auto'), ENT_QUOTES, 'UTF-8');
    $busqueda = "%" . $busqueda_encoded . "%";

    $stmt = $conn->prepare("SELECT * FROM platillos WHERE nombre LIKE ?");
    $stmt->bind_param("s", $busqueda);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM platillos");
}


        // Itera sobre los resultados para mostrarlos en la tabla
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <!-- Muestra datos del platillo -->
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['descripcion'] ?></td>
            <td>$<?= $row['precio'] ?></td>

            <!-- Verifica si está disponible: muestra "Sí" o "No" -->
            <td><?= $row['disponible'] ? 'Sí' : 'No' ?></td>

            <!-- Acciones disponibles para cada platillo -->
            <td>
                <!-- Enlace para editar el platillo, enviando su ID por GET -->
                <a href="editar.php?id=<?= $row['id'] ?>">Editar</a> |
 <!-- Error de lógica encontrado y solucionado no se declaro ningun parámetro a la acción al botón borrar-->
                <!-- Enlace para eliminar,-->
                <a href="borrar.php?id=<?= $row['id'] ?>" 
                   onclick="event.preventDefault(); 
                            if(confirm('¿Eliminar este platillo?')) { 
                                window.location.href=this.href; 
                            }">
                    🗑️ Eliminar
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
