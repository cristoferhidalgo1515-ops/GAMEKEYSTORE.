document.addEventListener("DOMContentLoaded", function () {

    const botonMenu =
        document.getElementById("botonMenu");

    const menuPrincipal =
        document.getElementById("menuPrincipal");

    const restarCantidad =
        document.getElementById("restarCantidad");

    const sumarCantidad =
        document.getElementById("sumarCantidad");

    const cantidadJuego =
        document.getElementById("cantidadJuego");

    const botonAgregar =
        document.getElementById("agregarCarritoDetalle");

    const cantidadCarrito =
        document.getElementById("cantidadCarrito");


    // =====================================
    // MENÚ RESPONSIVO
    // =====================================

    if (botonMenu && menuPrincipal) {

        botonMenu.addEventListener(
            "click",
            function () {

                menuPrincipal.classList.toggle(
                    "mostrar"
                );

            }
        );

    }


    // =====================================
    // CARRITO EXISTENTE
    // =====================================

    let carrito =
        JSON.parse(
            localStorage.getItem(
                "carritoGamekey"
            )
        ) || [];


    actualizarCantidadCarrito();


    // =====================================
    // STOCK
    // =====================================

    const stockMaximo =
        Number(
            botonAgregar.dataset.stock
        );


    // =====================================
    // RESTAR CANTIDAD
    // =====================================

    restarCantidad.addEventListener(
        "click",
        function () {

            let cantidad =
                Number(cantidadJuego.value);

            if (cantidad > 1) {

                cantidad--;

                cantidadJuego.value =
                    cantidad;

            }

        }
    );


    // =====================================
    // SUMAR CANTIDAD
    // =====================================

    sumarCantidad.addEventListener(
        "click",
        function () {

            let cantidad =
                Number(cantidadJuego.value);

            if (cantidad < stockMaximo) {

                cantidad++;

                cantidadJuego.value =
                    cantidad;

            } else {

                alert(
                    "No hay más unidades disponibles."
                );

            }

        }
    );


    // =====================================
    // AGREGAR AL CARRITO
    // =====================================

    botonAgregar.addEventListener(
        "click",
        function () {

            const id =
                Number(
                    botonAgregar.dataset.id
                );

            const nombre =
                botonAgregar.dataset.nombre;

            const precio =
                Number(
                    botonAgregar.dataset.precio
                );

            const imagen =
                botonAgregar.dataset.imagen;

            const cantidad =
                Number(
                    cantidadJuego.value
                );


            const productoExistente =
                carrito.find(
                    function (producto) {

                        return producto.id === id;

                    }
                );


            if (productoExistente) {

                const nuevaCantidad =
                    productoExistente.cantidad
                    +
                    cantidad;


                if (
                    nuevaCantidad >
                    stockMaximo
                ) {

                    alert(
                        "La cantidad supera el stock disponible."
                    );

                    return;

                }


                productoExistente.cantidad =
                    nuevaCantidad;

            } else {

                carrito.push({

                    id: id,

                    nombre: nombre,

                    precio: precio,

                    imagen: imagen,

                    cantidad: cantidad,

                    stock: stockMaximo

                });

            }


            localStorage.setItem(
                "carritoGamekey",
                JSON.stringify(carrito)
            );


            actualizarCantidadCarrito();


            alert(
                nombre +
                " fue agregado al carrito."
            );

        }
    );


    // =====================================
    // ACTUALIZAR CONTADOR
    // =====================================

    function actualizarCantidadCarrito() {

        const total =
            carrito.reduce(
                function (
                    acumulado,
                    producto
                ) {

                    return (
                        acumulado
                        +
                        producto.cantidad
                    );

                },
                0
            );


        cantidadCarrito.textContent =
            total;

    }

});