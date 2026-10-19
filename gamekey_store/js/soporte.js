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

        const formulario =
            document.getElementById(
                "formularioSoporte"
            );

        const mensajeEnviado =
            document.getElementById(
                "mensajeEnviado"
            );

        const cantidadCarrito =
            document.getElementById(
                "cantidadCarrito"
            );


        // MENÚ

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


        // CONTADOR DEL CARRITO

        const carrito =
            JSON.parse(
                localStorage.getItem(
                    "carritoGamekey"
                )
            ) || [];


        const cantidadTotal =
            carrito.reduce(
                function (
                    total,
                    producto
                ) {

                    return (
                        total +
                        producto.cantidad
                    );

                },
                0
            );


        cantidadCarrito.textContent =
            cantidadTotal;


        // FORMULARIO

        formulario.addEventListener(
            "submit",
            function (evento) {

                evento.preventDefault();


                mensajeEnviado.style.display =
                    "block";


                formulario.reset();


                setTimeout(
                    function () {

                        mensajeEnviado
                            .style
                            .display = "none";

                    },
                    4000
                );

            }
        );

    }
);