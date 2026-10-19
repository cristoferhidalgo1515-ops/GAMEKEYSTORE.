<?php

session_start();

/* ==================================================
   VERIFICAR SESIÓN
================================================== */

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

/* ==================================================
   VERIFICAR ROL
================================================== */

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

/* ==================================================
   CONEXIÓN
================================================== */

include("../config/conexion.php");

/* ==================================================
   VERIFICAR MÉTODO
================================================== */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: videojuegos.php");
    exit();
}

/* ==================================================
   RECIBIR DATOS
================================================== */

$id_videojuego = filter_input(
    INPUT_POST,
    "id_videojuego",
    FILTER_VALIDATE_INT
);

$titulo = trim($_POST["titulo"] ?? "");

$descripcion = trim(
    $_POST["descripcion"] ?? ""
);

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

/* ==================================================
   VALIDAR ID
================================================== */

if (!$id_videojuego) {

    header(
        "Location: videojuegos.php?mensaje=" .
        urlencode(
            "El videojuego seleccionado no es válido."
        )
    );

    exit();
}

/* ==================================================
   VALIDAR TÍTULO
================================================== */

if ($titulo === "") {

    header(
        "Location: editar_videojuego.php?id=" .
        $id_videojuego .
        "&mensaje=" .
        urlencode(
            "El título del videojuego es obligatorio."
        )
    );

    exit();
}

if (strlen($titulo) > 150) {

    header(
        "Location: editar_videojuego.php?id=" .
        $id_videojuego .
        "&mensaje=" .
        urlencode(
            "El título no puede superar los 150 caracteres."
        )
    );

    exit();
}

/* ==================================================
   VALIDAR DESCRIPCIÓN
================================================== */

if (strlen($descripcion) > 1000) {

    header(
        "Location: editar_videojuego.php?id=" .
        $id_videojuego .
        "&mensaje=" .
        urlencode(
            "La descripción no puede superar los 1000 caracteres."
        )
    );

    exit();
}

/* ==================================================
   VALIDAR PRECIO
================================================== */

if (
    $precio === false ||
    $precio === null ||
    $precio < 0
) {

    header(
        "Location: editar_videojuego.php?id=" .
        $id_videojuego .
        "&mensaje=" .
        urlencode(
            "El precio ingresado no es válido."
        )
    );

    exit();
}

/* ==================================================
   VALIDAR STOCK
================================================== */

if (
    $stock === false ||
    $stock === null ||
    $stock < 0
) {

    header(
        "Location: editar_videojuego.php?id=" .
        $id_videojuego .
        "&mensaje=" .
        urlencode(
            "El stock debe ser un número entero igual o mayor que cero."
        )
    );

    exit();
}

/* ==================================================
   VALIDAR CATEGORÍA
================================================== */

if (!$id_categoria) {

    header(
        "Location: editar_videojuego.php?id=" .
        $id_videojuego .
        "&mensaje=" .
        urlencode(
            "Debes seleccionar una categoría."
        )
    );

    exit();
}

/* ==================================================
   VALIDAR PLATAFORMA
================================================== */

if (!$id_plataforma) {

    header(
        "Location: editar_videojuego.php?id=" .
        $id_videojuego .
        "&mensaje=" .
        urlencode(
            "Debes seleccionar una plataforma."
        )
    );

    exit();
}

/* ==================================================
   VALIDAR ESTADO
================================================== */

if (
    !in_array(
        $estado,
        ["activo", "inactivo"],
        true
    )
) {

    header(
        "Location: editar_videojuego.php?id=" .
        $id_videojuego .
        "&mensaje=" .
        urlencode(
            "El estado seleccionado no es válido."
        )
    );

    exit();
}

/* ==================================================
   BUSCAR VIDEOJUEGO ACTUAL
================================================== */

$consulta_videojuego = $conexion->prepare(
    "SELECT
        id_videojuego,
        imagen
    FROM videojuegos
    WHERE id_videojuego = ?
    LIMIT 1"
);

$consulta_videojuego->bind_param(
    "i",
    $id_videojuego
);

$consulta_videojuego->execute();

$resultado_videojuego =
    $consulta_videojuego->get_result();

if ($resultado_videojuego->num_rows === 0) {

    $consulta_videojuego->close();
    $conexion->close();

    header(
        "Location: videojuegos.php?mensaje=" .
        urlencode(
            "El videojuego que deseas editar no existe."
        )
    );

    exit();
}

$videojuego_actual =
    $resultado_videojuego->fetch_assoc();

$imagen_anterior =
    $videojuego_actual["imagen"] ?? null;

$consulta_videojuego->close();

/* ==================================================
   VERIFICAR CATEGORÍA
================================================== */

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
        "Location: editar_videojuego.php?id=" .
        $id_videojuego .
        "&mensaje=" .
        urlencode(
            "La categoría seleccionada no existe."
        )
    );

    exit();
}

$verificar_categoria->close();

/* ==================================================
   VERIFICAR PLATAFORMA
================================================== */

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
        "Location: editar_videojuego.php?id=" .
        $id_videojuego .
        "&mensaje=" .
        urlencode(
            "La plataforma seleccionada no existe."
        )
    );

    exit();
}

$verificar_plataforma->close();

/* ==================================================
   PROCESAR NUEVA PORTADA
================================================== */

$ruta_imagen = $imagen_anterior;

$nueva_imagen_guardada = false;

$ruta_destino_nueva = null;

if (
    isset($_FILES["imagen"]) &&
    $_FILES["imagen"]["error"] !== UPLOAD_ERR_NO_FILE
) {

    if ($_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {

        $conexion->close();

        header(
            "Location: editar_videojuego.php?id=" .
            $id_videojuego .
            "&mensaje=" .
            urlencode(
                "Ocurrió un error al cargar la nueva portada."
            )
        );

        exit();
    }

    /* Máximo 5 MB */

    $tamanio_maximo =
        5 * 1024 * 1024;

    if (
        $_FILES["imagen"]["size"] >
        $tamanio_maximo
    ) {

        $conexion->close();

        header(
            "Location: editar_videojuego.php?id=" .
            $id_videojuego .
            "&mensaje=" .
            urlencode(
                "La portada no puede superar los 5 MB."
            )
        );

        exit();
    }

    /* Verificar que realmente sea una imagen */

    $informacion_imagen = getimagesize(
        $_FILES["imagen"]["tmp_name"]
    );

    if ($informacion_imagen === false) {

        $conexion->close();

        header(
            "Location: editar_videojuego.php?id=" .
            $id_videojuego .
            "&mensaje=" .
            urlencode(
                "El archivo seleccionado no es una imagen válida."
            )
        );

        exit();
    }

    /* Tipos permitidos */

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
            "Location: editar_videojuego.php?id=" .
            $id_videojuego .
            "&mensaje=" .
            urlencode(
                "Solo se permiten imágenes JPG, JPEG, PNG o WEBP."
            )
        );

        exit();
    }

    $extension =
        $tipos_permitidos[$tipo_imagen];

    /* Crear nombre único */

    try {

        $codigo_aleatorio =
            bin2hex(random_bytes(4));

    } catch (Exception $error) {

        $codigo_aleatorio =
            uniqid();
    }

    $nombre_imagen =
        "videojuego_" .
        time() .
        "_" .
        $codigo_aleatorio .
        "." .
        $extension;

    /* Carpeta donde se guardará */

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
                "Location: editar_videojuego.php?id=" .
                $id_videojuego .
                "&mensaje=" .
                urlencode(
                    "No se pudo crear la carpeta de imágenes."
                )
            );

            exit();
        }
    }

    $ruta_destino_nueva =
        $carpeta_destino .
        $nombre_imagen;

    if (
        !move_uploaded_file(
            $_FILES["imagen"]["tmp_name"],
            $ruta_destino_nueva
        )
    ) {

        $conexion->close();

        header(
            "Location: editar_videojuego.php?id=" .
            $id_videojuego .
            "&mensaje=" .
            urlencode(
                "No se pudo guardar la nueva portada."
            )
        );

        exit();
    }

    $ruta_imagen =
        "img/videojuegos/" .
        $nombre_imagen;

    $nueva_imagen_guardada = true;
}

/* ==================================================
   ACTUALIZAR VIDEOJUEGO
================================================== */

$actualizar = $conexion->prepare(
    "UPDATE videojuegos
    SET
        titulo = ?,
        descripcion = ?,
        precio = ?,
        stock = ?,
        imagen = ?,
        id_categoria = ?,
        id_plataforma = ?,
        estado = ?
    WHERE id_videojuego = ?"
);

$actualizar->bind_param(
    "ssdisiisi",
    $titulo,
    $descripcion,
    $precio,
    $stock,
    $ruta_imagen,
    $id_categoria,
    $id_plataforma,
    $estado,
    $id_videojuego
);

/* ==================================================
   SI LA ACTUALIZACIÓN FUNCIONA
================================================== */

if ($actualizar->execute()) {

    $actualizar->close();
    $conexion->close();

    /*
       Eliminar la portada anterior solamente si:
       1. Se cargó una nueva portada.
       2. Había una portada anterior.
       3. La nueva ruta es diferente.
    */

    if (
        $nueva_imagen_guardada &&
        !empty($imagen_anterior) &&
        $imagen_anterior !== $ruta_imagen
    ) {

        $ruta_archivo_anterior =
            __DIR__ .
            "/../" .
            $imagen_anterior;

        if (
            file_exists($ruta_archivo_anterior) &&
            is_file($ruta_archivo_anterior)
        ) {
            unlink($ruta_archivo_anterior);
        }
    }

    header(
        "Location: videojuegos.php?mensaje=" .
        urlencode(
            "Videojuego actualizado correctamente."
        )
    );

    exit();
}

/* ==================================================
   SI LA ACTUALIZACIÓN FALLA
================================================== */

$error_actualizacion =
    $actualizar->error;

/*
   Si se había guardado una portada nueva, pero la
   actualización de la base de datos falló, se elimina
   esa imagen nueva para no dejar archivos innecesarios.
*/

if (
    $nueva_imagen_guardada &&
    $ruta_destino_nueva !== null &&
    file_exists($ruta_destino_nueva)
) {
    unlink($ruta_destino_nueva);
}

$actualizar->close();
$conexion->close();

header(
    "Location: editar_videojuego.php?id=" .
    $id_videojuego .
    "&mensaje=" .
    urlencode(
        "No se pudo actualizar el videojuego. Error: " .
        $error_actualizacion
    )
);

exit();

?>