
document.addEventListener("DOMContentLoaded", function () {

    // ==========================================
    // ELEMENTOS DEL HTML
    // ==========================================

    const botonMenu = document.getElementById("botonMenu");
    const menuPrincipal = document.getElementById("menuPrincipal");

    const listaCarrito = document.getElementById("listaCarrito");
    const carritoVacio = document.getElementById("carritoVacio");
    const vaciarCarrito = document.getElementById("vaciarCarrito");

    const cantidadCarrito = document.getElementById("cantidadCarrito");
    const cantidadResumen = document.getElementById("cantidadResumen");

    const subtotalCarrito = document.getElementById("subtotalCarrito");
    const totalCarrito = document.getElementById("totalCarrito");

    const continuarCompra = document.getElementById("continuarCompra");


    // ==========================================
    // POO: CLASE PRODUCTO
    // ==========================================

    // POO: Una clase es una plantilla para crear objetos.
    // Esta clase representa un videojuego de GameKey Store.

    class Producto {

        // POO: CONSTRUCTOR.
        // Inicializa las propiedades de cada videojuego.
        // Se utiliza Object.assign para recuperar también
        // propiedades adicionales, como el stock.

        constructor(datos) {
            Object.assign(this, datos);

            this.nombre = datos.nombre;
            this.precio = Number(datos.precio);
            this.cantidad = Number(datos.cantidad);
            this.imagen = datos.imagen || "";
        }

        // POO: MÉTODO.
        // Calcula el subtotal de este videojuego.

        calcularSubtotal() {
            return this.precio * this.cantidad;
        }

    }


    // ==========================================
    // POO: CLASE CARRITO
    // ==========================================

    // Esta clase administra los productos de la compra.
    // Agrupa los datos y las operaciones del carrito.

    class Carrito {

        // POO: CONSTRUCTOR.
        // Cada objeto Carrito comienza con una lista
        // de productos, que puede estar vacía.

        constructor(productos = []) {

            // POO: PROPIEDAD.
            // "productos" almacena los objetos Producto.

            this.productos = productos.map(
                function (datos) {
                    return datos instanceof Producto
                        ? datos
                        : new Producto(datos);
                }
            );
        }

        // POO: MÉTODO.
        // Guarda el contenido del carrito en el navegador.

        guardar() {
            localStorage.setItem(
                "carritoGamekey",
                JSON.stringify(this.productos)
            );
        }

        // POO: MÉTODO.
        // Elimina un producto usando su posición.

        eliminar(indice) {
            this.productos.splice(indice, 1);
        }

        // POO: MÉTODO.
        // Vacía todos los productos del carrito.

        vaciar() {
            this.productos = [];
        }

        // POO: MÉTODO.
        // Aumenta la cantidad de un producto si hay stock.

        aumentar(indice) {

            const producto = this.productos[indice];

            if (!producto) {
                return false;
            }

            const stock = Number(producto.stock) || 99;

            if (producto.cantidad < stock) {
                producto.cantidad++;
                return true;
            }

            return false;
        }

        // POO: MÉTODO.
        // Disminuye la cantidad o elimina el producto
        // cuando solo queda una unidad.

        disminuir(indice) {

            const producto = this.productos[indice];

            if (!producto) {
                return;
            }

            if (producto.cantidad > 1) {
                producto.cantidad--;
            } else {
                this.eliminar(indice);
            }
        }

        // POO: MÉTODO.
        // Calcula cuántas unidades hay en total.

        calcularCantidadTotal() {

            return this.productos.reduce(
                function (total, producto) {
                    return total + producto.cantidad;
                },
                0
            );
        }

        // POO: MÉTODO.
        // Calcula el precio total de la compra.

        calcularTotal() {

            return this.productos.reduce(
                function (total, producto) {
                    return total + producto.calcularSubtotal();
                },
                0
            );
        }

    }


    // ==========================================
    // MENÚ RESPONSIVO
    // ==========================================

    if (botonMenu && menuPrincipal) {

        botonMenu.addEventListener("click", function () {

            menuPrincipal.classList.toggle("mostrar");

        });

    }


    // ==========================================
    // POO: CREACIÓN DEL OBJETO CARRITO
    // ==========================================

    // Recuperamos los datos guardados anteriormente.

    let datosGuardados = [];

    try {

        datosGuardados = JSON.parse(
            localStorage.getItem("carritoGamekey")
        ) || [];

        if (!Array.isArray(datosGuardados)) {
            datosGuardados = [];
        }

    } catch (error) {

        datosGuardados = [];

    }

    // POO: OBJETO.
    // "carrito" es una instancia de la clase Carrito.
    // Sus productos se convierten en objetos Producto.

    const carrito = new Carrito(datosGuardados);


    // Mostramos el contenido al cargar la página.

    mostrarCarrito();


    // ==========================================
    // MOSTRAR CARRITO
    // ==========================================

    function mostrarCarrito() {

        listaCarrito.innerHTML = "";

        const productos = carrito.productos;

        // Mostramos el mensaje cuando no hay productos.

        if (productos.length === 0) {

            carritoVacio.style.display = "block";
            vaciarCarrito.style.display = "none";
            continuarCompra.disabled = true;

        } else {

            carritoVacio.style.display = "none";
            vaciarCarrito.style.display = "block";
            continuarCompra.disabled = false;

        }


        // Recorremos los objetos Producto.

        productos.forEach(function (producto, indice) {

            const item = document.createElement("article");

            item.className = "item-carrito";

            const imagen = producto.imagen
                ? "../img/juegos/" + producto.imagen
                : "";

            // POO: LLAMADA A UN MÉTODO.
            // El objeto Producto calcula su propio subtotal.

            const subtotal = producto.calcularSubtotal();


            item.innerHTML = `

                <div class="imagen-carrito">

                    ${
                        imagen
                            ? `<img
                                src="${imagen}"
                                alt="${producto.nombre}">`
                            : `<div></div>`
                    }

                </div>

                <div class="info-carrito">

                    <h3>${producto.nombre}</h3>

                    <p class="precio-unitario">
                        Precio unitario:
                        $${producto.precio.toFixed(2)}
                    </p>

                    <div class="controles-carrito">

                        <div class="control-cantidad-carrito">

                            <button
                                type="button"
                                class="restar"
                                data-indice="${indice}">
                                −
                            </button>

                            <span>${producto.cantidad}</span>

                            <button
                                type="button"
                                class="sumar"
                                data-indice="${indice}">
                                +
                            </button>

                        </div>

                        <button
                            type="button"
                            class="eliminar-producto"
                            data-indice="${indice}">
                            Eliminar
                        </button>

                    </div>

                </div>

                <div class="precio-item">

                    <span>Subtotal</span>

                    <strong>$${subtotal.toFixed(2)}</strong>

                </div>

            `;

            listaCarrito.appendChild(item);

        });


        agregarEventosProductos();

        actualizarResumen();

    }


    // ==========================================
    // EVENTOS DE LOS PRODUCTOS
    // ==========================================

    function agregarEventosProductos() {

        const botonesRestar =
            document.querySelectorAll(".restar");

        const botonesSumar =
            document.querySelectorAll(".sumar");

        const botonesEliminar =
            document.querySelectorAll(".eliminar-producto");


        // RESTAR CANTIDAD

        botonesRestar.forEach(function (boton) {

            boton.addEventListener("click", function () {

                const indice = Number(boton.dataset.indice);

                // POO: LLAMADA AL MÉTODO disminuir()
                carrito.disminuir(indice);

                carrito.guardar();

                mostrarCarrito();

            });

        });


        // SUMAR CANTIDAD

        botonesSumar.forEach(function (boton) {

            boton.addEventListener("click", function () {

                const indice = Number(boton.dataset.indice);

                // POO: LLAMADA AL MÉTODO aumentar().
                // El método comprueba el stock disponible.

                const sePudoAgregar = carrito.aumentar(indice);

                if (!sePudoAgregar) {

                    alert(
                        "No hay más unidades disponibles."
                    );

                }

                carrito.guardar();

                mostrarCarrito();

            });

        });


        // ELIMINAR PRODUCTO

        botonesEliminar.forEach(function (boton) {

            boton.addEventListener("click", function () {

                const indice = Number(boton.dataset.indice);

                // POO: LLAMADA AL MÉTODO eliminar().

                carrito.eliminar(indice);

                carrito.guardar();

                mostrarCarrito();

            });

        });

    }


    // ==========================================
    // VACIAR CARRITO
    // ==========================================

    if (vaciarCarrito) {

        vaciarCarrito.addEventListener("click", function () {

            if (carrito.productos.length === 0) {
                return;
            }

            const confirmar = confirm(
                "¿Deseas vaciar todo el carrito?"
            );

            if (!confirmar) {
                return;
            }

            // POO: LLAMADA AL MÉTODO vaciar().

            carrito.vaciar();

            carrito.guardar();

            mostrarCarrito();

        });

    }


    // ==========================================
    // CONTINUAR COMPRA
    // ==========================================

    if (continuarCompra) {

        continuarCompra.addEventListener("click", function () {

            if (carrito.productos.length === 0) {

                alert("Tu carrito está vacío.");

                return;

            }

            // El siguiente paso recoge los datos del cliente y registra el pedido en el módulo 3.
            window.location.href = "checkout.php";

        });

    }


    // ==========================================
    // ACTUALIZAR RESUMEN
    // ==========================================

    function actualizarResumen() {

        // POO: LLAMADAS A MÉTODOS DEL OBJETO CARRITO.

        const cantidadTotal =
            carrito.calcularCantidadTotal();

        const subtotal =
            carrito.calcularTotal();


        cantidadCarrito.textContent = cantidadTotal;

        cantidadResumen.textContent = cantidadTotal;

        subtotalCarrito.textContent =
            "$" + subtotal.toFixed(2);

        totalCarrito.textContent =
            "$" + subtotal.toFixed(2);

    }

});
