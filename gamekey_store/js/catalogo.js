document.addEventListener("DOMContentLoaded", function () {

    const botonMenu = document.getElementById("botonMenu");
    const menuPrincipal = document.getElementById("menuPrincipal");

    const buscador = document.getElementById("buscadorCatalogo");
    const plataforma = document.getElementById("filtroPlataforma");
    const categoria = document.getElementById("filtroCategoria");
    const ordenar = document.getElementById("ordenarCatalogo");

    const limpiarFiltros = document.getElementById("limpiarFiltros");
    const cantidadResultados = document.getElementById("cantidadResultados");
    const cuadricula = document.getElementById("cuadriculaCatalogo");
    const sinResultados = document.getElementById("sinResultados");

    const botonesComprar = document.querySelectorAll(".agregar-catalogo");
    const cantidadCarrito = document.getElementById("cantidadCarrito");

    let productos = Array.from(
        document.querySelectorAll(".producto-catalogo")
    );

    let carrito = JSON.parse(
        localStorage.getItem("carritoGamekey")
    ) || [];

    actualizarCantidadCarrito();
    const parametros =
    new URLSearchParams(
        window.location.search
    );

const categoriaURL =
    parametros.get("categoria");

if (categoriaURL) {

    const opcionExiste =
        Array.from(
            categoria.options
        ).some(
            function (opcion) {

                return opcion.value ===
                    categoriaURL;

            }
        );

    if (opcionExiste) {

        categoria.value =
            categoriaURL;

        aplicarFiltros();

    }

}

    botonMenu.addEventListener("click", function () {
        menuPrincipal.classList.toggle("mostrar");
    });

    buscador.addEventListener("input", aplicarFiltros);
    plataforma.addEventListener("change", aplicarFiltros);
    categoria.addEventListener("change", aplicarFiltros);
    ordenar.addEventListener("change", aplicarFiltros);

    limpiarFiltros.addEventListener("click", function () {

        buscador.value = "";
        plataforma.value = "todos";
        categoria.value = "todos";
        ordenar.value = "normal";

        aplicarFiltros();

    });

    botonesComprar.forEach(function (boton) {

        boton.addEventListener("click", function () {

            const nombre = boton.dataset.nombre;
            const precio = Number(boton.dataset.precio);

            const productoExistente = carrito.find(function (producto) {
                return producto.nombre === nombre;
            });

            if (productoExistente) {
                productoExistente.cantidad += 1;
            } else {
                carrito.push({
                    nombre: nombre,
                    precio: precio,
                    cantidad: 1
                });
            }

            localStorage.setItem(
                "carritoGamekey",
                JSON.stringify(carrito)
            );

            actualizarCantidadCarrito();

            alert(nombre + " fue agregado al carrito.");

        });

    });

    function aplicarFiltros() {

        const textoBusqueda = buscador.value
            .toLowerCase()
            .trim();

        const plataformaSeleccionada = plataforma.value;
        const categoriaSeleccionada = categoria.value;

        let productosVisibles = productos.filter(function (producto) {

            const nombre = producto.dataset.nombre.toLowerCase();
            const plataformaProducto = producto.dataset.plataforma;
            const categoriaProducto = producto.dataset.categoria;

            const coincideNombre =
                nombre.includes(textoBusqueda);

            const coincidePlataforma =
                plataformaSeleccionada === "todos" ||
                plataformaProducto === plataformaSeleccionada;

            const coincideCategoria =
                categoriaSeleccionada === "todos" ||
                categoriaProducto === categoriaSeleccionada;

            return (
                coincideNombre &&
                coincidePlataforma &&
                coincideCategoria
            );

        });

        ordenarProductos(productosVisibles);

        productos.forEach(function (producto) {
            producto.style.display = "none";
        });

        productosVisibles.forEach(function (producto) {

            producto.style.display = "block";
            cuadricula.appendChild(producto);

        });

        cantidadResultados.textContent =
            productosVisibles.length;

        sinResultados.style.display =
            productosVisibles.length === 0
                ? "block"
                : "none";
    }

    function ordenarProductos(lista) {

        const opcion = ordenar.value;

        if (opcion === "precio-menor") {

            lista.sort(function (a, b) {
                return Number(a.dataset.precio) -
                       Number(b.dataset.precio);
            });

        }

        if (opcion === "precio-mayor") {

            lista.sort(function (a, b) {
                return Number(b.dataset.precio) -
                       Number(a.dataset.precio);
            });

        }

        if (opcion === "nombre") {

            lista.sort(function (a, b) {

                return a.dataset.nombre.localeCompare(
                    b.dataset.nombre
                );

            });

        }

    }

    function actualizarCantidadCarrito() {

        const totalProductos = carrito.reduce(
            function (total, producto) {
                return total + producto.cantidad;
            },
            0
        );

        cantidadCarrito.textContent = totalProductos;
    }

});