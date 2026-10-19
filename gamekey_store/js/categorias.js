document.addEventListener(
    "DOMContentLoaded",
    function () {

        const botonMenu =
            document.getElementById(
                "botonMenu"
            );

        const menuPrincipal =
            document.getElementById(
                "menuPrincipal"
            );

        const cantidadCarrito =
            document.getElementById(
                "cantidadCarrito"
            );

        const botonesCategoria =
            document.querySelectorAll(
                ".boton-categoria"
            );


        // ==================================
        // MENÚ
        // ==================================

        if (
            botonMenu &&
            menuPrincipal
        ) {

            botonMenu.addEventListener(
                "click",
                function () {

                    menuPrincipal
                        .classList
                        .toggle("mostrar");

                }
            );

        }


        // ==================================
        // CONTADOR DEL CARRITO
        // ==================================

        const carrito =
            JSON.parse(
                localStorage.getItem(
                    "carritoGamekey"
                )
            ) || [];


        const total =
            carrito.reduce(
                function (
                    acumulado,
                    producto
                ) {

                    return (
                        acumulado +
                        producto.cantidad
                    );

                },
                0
            );


        cantidadCarrito.textContent =
            total;


        // ==================================
        // CATEGORÍAS
        // ==================================

        botonesCategoria.forEach(
            function (boton) {

                boton.addEventListener(
                    "click",
                    function () {

                        const categoria =
                            boton.dataset
                                .categoria;


                        window.location.href =
                            "catalogo.php?categoria="
                            +
                            encodeURIComponent(
                                categoria
                            );

                    }
                );

            }
        );

    }
);