document.addEventListener("DOMContentLoaded", function () {

    const botonMenu = document.getElementById("botonMenu");
    const menuPrincipal = document.getElementById("menuPrincipal");

    const botonesComprar = document.querySelectorAll(".boton-comprar");
    const cantidadCarrito = document.getElementById("cantidadCarrito");

    const buscadorInicio = document.getElementById("buscadorInicio");
    const botonBuscar = document.getElementById("botonBuscar");

    let carrito = JSON.parse(
        localStorage.getItem("carritoGamekey")
    ) || [];

    actualizarCantidadCarrito();

    botonMenu.addEventListener("click", function () {
        menuPrincipal.classList.toggle("mostrar");
    });

    botonesComprar.forEach(function (boton) {

        boton.addEventListener("click", function () {

            const nombre = boton.dataset.nombre;
            const precio = Number(boton.dataset.precio);

            const juegoExistente = carrito.find(function (juego) {
                return juego.nombre === nombre;
            });

            if (juegoExistente) {
                juegoExistente.cantidad += 1;
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

    botonBuscar.addEventListener("click", realizarBusqueda);

    buscadorInicio.addEventListener("keydown", function (evento) {

        if (evento.key === "Enter") {
            realizarBusqueda();
        }

    });

    function realizarBusqueda() {

        const texto = buscadorInicio.value.trim();

        if (texto === "") {
            alert("Escribe el nombre de un videojuego.");
            buscadorInicio.focus();
            return;
        }

        window.location.href =
            "catalogo.php?buscar=" +
            encodeURIComponent(texto);
    }

    function actualizarCantidadCarrito() {

        const cantidadTotal = carrito.reduce(
            function (total, juego) {
                return total + juego.cantidad;
            },
            0
        );

        cantidadCarrito.textContent = cantidadTotal;
    }

});