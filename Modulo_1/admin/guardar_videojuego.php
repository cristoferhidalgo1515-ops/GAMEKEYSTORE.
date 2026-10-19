<?php

session_start();

/* Verificar sesión */

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

/* Verificar rol */

if (
    !isset($_SESSION["rol"]) ||
    $_SESSION["rol"] !== "administrador"
) {
    header(
        "Location: ../login.php?mensaje=" .
        urlencode("Acceso no autorizado.")
    );
    exit();
}

/* Conexión */

include("../config/conexion.php");

/* Permitir solamente solicitudes POST */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: videojuegos.php");
    exit();
}

/* Recibir datos */

$titulo = trim($_POST["titulo"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");

$precio = filter_input(
    INPUT_POST,
    "precio",
    FILTER_VALIDATE_FLOAT
);

$stock = filter_input(
    INPUT_POST,
    "stock",
    FILTER_VALIDATE_INT
);

$id_categoria = filter_input(
    INPUT_POST,
    "id_categoria",
    FILTER_VALIDATE_INT
);

$id_plataforma = filter_input(
    INPUT_POST,
    "id_plataforma",
    FILTER_VALIDATE_INT
);

$estado = trim($_POST["estado"] ?? "");

/* Validar título */

if ($titulo === "") {

    header(
        "Location: crear_videojuego.php?mensaje=" .
        urlencode(
            "El título del videojuego es obligatorio."
        )
    );

    exit();
}

if (strlen($titulo) > 150) {

    header(
        "Location: crear_videojuego.php?mensaje=" .
        urlencode(
            "El título no puede superar los 150 caracteres."
        )
    );

    exit();
}

/* Validar descripción */

if (strlen($descripcion) > 1000) {

    header(
        "Location: crear_videojuego.php?mensaje=" .
        urlencode(
            "La descripción no puede superar los 1000 caracteres."
        )
    );

    exit();
}

/* Validar precio */

if ($precio === false || $precio < 0) {

    header(
        "Location: crear_videojuego.php?mensaje=" .
        urlencode(
            "El precio ingresado no es válido."
        )
    );

    exit();
}

/* Validar stock */

if ($stock === false || $stock < 0) {

    header(
        "Location: crear_videojuego.php?mensaje=" .
        urlencode(
            "El stock debe ser un número entero igual o mayor que cero."
        )
    );

    exit();
}

/* Validar categoría */

if (!$id_categoria) {

    header(
        "Location: crear_videojuego.php?mensaje=" .
        urlencode(
            "Debes seleccionar una categoría."
        )
    );

    exit();
}

/* Validar plataforma */

if (!$id_plataforma) {

    header(
        "Location: crear_videojuego.php?mensaje=" .
        urlencode(
            "Debes seleccionar una plataforma."
        )
    );

    exit();
}

/* Validar estado */

if (
    !in_array(
        $estado,
        ["activo", "inactivo"],
        true
    )
) {

    header(
        "Location: crear_videojuego.php?mensaje=" .
        urlencode(
            "El estado seleccionado no es válido."
        )
    );

    exit();
}

/* Verificar que la categoría exista */

$verificar_categoria = $conexion->prepare(
    "SELECT id_categoria
     FROM categorias
     WHERE id_categoria = ?
     LIMIT 1"
);

$verificar_categoria->bind_param(
    "i",
    $id_categoria
);

$verificar_categoria->execute();

$resultado_categoria =
    $verificar_categoria->get_result();

if ($resultado_categoria->num_rows === 0) {

    $verificar_categoria->close();
    $conexion->close();

    header(
        "Location: crear_videojuego.php?mensaje=" .
        urlencode(
            "La categoría seleccionada no existe."
        )
    );

    exit();
}

$verificar_categoria->close();

/* Verificar que la plataforma exista */

$verificar_plataforma = $conexion->prepare(
    "SELECT id_plataforma
     FROM plataformas
     WHERE id_plataforma = ?
     LIMIT 1"
);

$verificar_plataforma->bind_param(
    "i",
    $id_plataforma
);

$verificar_plataforma->execute();

$resultado_plataforma =
    $verificar_plataforma->get_result();

if ($resultado_plataforma->num_rows === 0) {

    $verificar_plataforma->close();
    $conexion->close();

    header(
        "Location: crear_videojuego.php?mensaje=" .
        urlencode(
            "La plataforma seleccionada no existe."
        )
    );

    exit();
}

$verificar_plataforma->close();

/* Procesar la imagen */

$ruta_imagen = null;

if (
    isset($_FILES["imagen"]) &&
    $_FILES["imagen"]["error"] !== UPLOAD_ERR_NO_FILE
) {

    if ($_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {

        $conexion->close();

        header(
            "Location: crear_videojuego.php?mensaje=" .
            urlencode(
                "Ocurrió un error al cargar la imagen."
            )
        );

        exit();
    }

    $tamanio_maximo =
        5 * 1024 * 1024;

    if ($_FILES["imagen"]["size"] > $tamanio_maximo) {

        $conexion->close();

        header(
            "Location: crear_videojuego.php?mensaje=" .
            urlencode(
                "La imagen no puede superar los 5 MB."
            )
        );

        exit();
    }

    $informacion_imagen =
        getimagesize(
            $_FILES["imagen"]["tmp_name"]
        );

    if ($informacion_imagen === false) {

        $conexion->close();

        header(
            "Location: crear_videojuego.php?mensaje=" .
            urlencode(
                "El archivo seleccionado no es una imagen válida."
            )
        );

        exit();
    }

    $tipos_permitidos = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp"
    ];

    $tipo_imagen =
        $informacion_imagen["mime"];

    if (
        !array_key_exists(
            $tipo_imagen,
            $tipos_permitidos
        )
    ) {

        $conexion->close();

        header(
            "Location: crear_videojuego.php?mensaje=" .
            urlencode(
                "Solo se permiten imágenes JPG, JPEG, PNG o WEBP."
            )
        );

        exit();
    }

    $extension =
        $tipos_permitidos[$tipo_imagen];

    $nombre_imagen =
        "videojuego_" .
        time() .
        "_" .
        bin2hex(random_bytes(4)) .
        "." .
        $extension;

    $carpeta_destino =
        __DIR__ .
        "/../img/videojuegos/";

    if (!is_dir($carpeta_destino)) {

        if (
            !mkdir(
                $carpeta_destino,
                0777,
                true
            )
        ) {

            $conexion->close();

            header(
                "Location: crear_videojuego.php?mensaje=" .
                urlencode(
                    "No se pudo crear la carpeta de imágenes."
                )
            );

            exit();
        }
    }

    $ruta_destino =
        $carpeta_destino .
        $nombre_imagen;

    if (
        !move_uploaded_file(
            $_FILES["imagen"]["tmp_name"],
            $ruta_destino
        )
    ) {

        $conexion->close();

        header(
            "Location: crear_videojuego.php?mensaje=" .
            urlencode(
                "No se pudo guardar la imagen."
            )
        );

        exit();
    }

    $ruta_imagen =
        "img/videojuegos/" .
        $nombre_imagen;
}

/* Guardar el videojuego */

$guardar = $conexion->prepare(
    "INSERT INTO videojuegos (
        titulo,
        descripcion,
        precio,
        stock,
        imagen,
        id_categoria,
        id_plataforma,
        estado
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);

$guardar->bind_param(
    "ssdisiis",
    $titulo,
    $descripcion,
    $precio,
    $stock,
    $ruta_imagen,
    $id_categoria,
    $id_plataforma,
    $estado
);

if ($guardar->execute()) {

    $guardar->close();
    $conexion->close();

    header(
        "Location: videojuegos.php?mensaje=" .
        urlencode(
            "Videojuego registrado correctamente."
        )
    );

    exit();
}

/* Eliminar imagen si falló el INSERT */

if (
    $ruta_imagen !== null &&
    isset($ruta_destino) &&
    file_exists($ruta_destino)
) {
    unlink($ruta_destino);
}

$error = $guardar->error;

$guardar->close();
$conexion->close();

header(
    "Location: crear_videojuego.php?mensaje=" .
    urlencode(
        "No se pudo registrar el videojuego. Error: " .
        $error
    )
);

exit();

?>