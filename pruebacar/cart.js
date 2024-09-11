// Función para añadir un producto al carrito
function addToCart(productId, productName, productPrice) {
    let xhr = new XMLHttpRequest(); // Crear una solicitud AJAX
    xhr.open("POST", "add_to_cart.php", true); // Configurar solicitud POST
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // Establecer el tipo de contenido
    xhr.onload = function() { // Manejar la respuesta de la solicitud
        if (xhr.status === 200) {
            alert('Producto añadido al carrito'); // Mensaje de éxito
        } else {
            alert('Error al añadir al carrito'); // Mensaje de error
        }
    };
    // Enviar datos del producto al servidor
    xhr.send(`product_id=${productId}&product_name=${productName}&product_price=${productPrice}`);
}
