<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
<!-- Define la codificación de caracteres como UTF-8 -->
    <meta charset="UTF-8">
    <!-- Título que aparecerá en la pestaña del navegador -->
    <title>Menú de Mariscos</title>
    
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h1>Menú del Restaurante de Mariscos</h1>
      <!-- Enlace para cerrar sesión -->
    <!-- Estás metiendo un <a> dentro de un <button>, lo cual no es válido según HTML5 -->
    <!-- Lo ideal sería usar solo un <a> estilizado como botón -->
    <a class="boton boton-secundario" href="cierredesecion2.php">Cerrar sesión</a>

    <button class="boton boton Secundario">.
    <a href="paginainicial.php">Volver a esta misma pagina</a>
</button>
     <!-- Formulario para buscar platillos por nombre -->
    <form method="get" action="paginainicial.php">
    <!-- Campo de texto para la búsqueda con valor persistente tras envío -->
        <input type="text" name="buscar" placeholder="Buscar platillo..." value="<?= $_GET['buscar'] ?? '' ?>">
        <!-- Botón para enviar el formulario -->
        <button type="submit">Buscar</button>
    </form>
<!-- Inicio de la tabla para mostrar los platillos -->
    <table>
        <tr><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Disponible</th>
       
        <?php
        // Variable que no se usa en este código, puede eliminarse
        $condicion = "";
        // Verifica si el usuario ha escrito algo en el campo de búsqueda
        if (!empty($_GET['buscar'])) {
            // Agrega comodines (%) para búsqueda parcial en SQL
            $busqueda = "%" . $_GET['buscar'] . "%";
            // Prepara una consulta SQL segura con placeholder
            $stmt = $conn->prepare("SELECT * FROM platillos WHERE nombre LIKE ?");
            // Asocia el parámetro de búsqueda a la consulta
            $stmt->bind_param("s", $busqueda);
            // Ejecuta la consulta
            $stmt->execute();
            // Obtiene los resultados de la consulta
            $result = $stmt->get_result();
        } else {
            // Si no se ingresó texto de búsqueda, selecciona todos los platillos
            $result = $conn->query("SELECT * FROM platillos");
        }
 // Recorre los resultados obtenidos y los muestra en la tabla
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
        <!-- Imprime el nombre del platillo -->
            <td><?= $row['nombre'] ?></td>
             <!-- Imprime la descripción del platillo -->
            <td><?= $row['descripcion'] ?></td>
             <!-- Imprime el precio del platillo con el símbolo $ -->
            <td>$<?= $row['precio'] ?></td>
            <!-- Muestra "Sí" si está disponible (valor 1), o "No" si no lo está -->
            <td><?= $row['disponible'] ? 'Sí' : 'No' ?></td>
            <!-- Celda vacía que puede usarse para botones futuros (editar/eliminar) -->
            <td>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
