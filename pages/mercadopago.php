<?php

session_start();
require_once 'auth.php';
require_once 'database.php';
require_once 'cart_check.php';

if (!isset($_SESSION['id_direccion'])) {
    header("Location: cart.php");
    exit();
}

$stmt3 = $pdo->prepare("SELECT valor FROM configuracion WHERE clave = 'shipping_cost'");
$stmt3->execute();

$envio = $stmt3->fetchColumn();

?>

<!DOCTYPE HTML>
<head>
    <title>Procesando pago...</title>
</head>
<body>

<script>
    (async() => {
        try {
            const getCart = await fetch("get_cart.php");
            const cart = await getCart.json();

            if (!cart || Object.keys(cart).length === 0) {
                alert("Cart is empty");
                return;
            }

            const userId = <?php echo $_SESSION['id_usuario'] ?? 'null'; ?>;
            if (!userId) {
                alert("User ID not found");
                return;
            }

            const directionId = <?php echo $_SESSION['id_direccion'] ?? 'null' ?>;
            if (!directionId) {
                alert("Direction ID not found");
                return;
            }

            const response = await fetch("http://localhost:3000/api/payments/create-order", {
                method: 'POST',
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    carrito: cart,
                    id_usuario: Number(userId),
                    id_direccion: Number(directionId)
                })
            });

            if (!response.ok) {
                throw new Error("Error trying to create order");
            }

            const data = await response.json();

            window.location.href = data.init_point;
        } catch(error) {
            console.error(error);
            alert("Error trying to process payment");
        }
    })();
</script>

</body>