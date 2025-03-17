<?php
session_start();

// Conectar a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'guru');

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Guru</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .empty-cart {
            padding: 40px 20px;
            background-color: #ddd590;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
        }

        .empty-cart i {
            font-size: 80px;
            color: #a38746;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .empty-cart h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .empty-cart p {
            color: #666;
            margin-bottom: 25px;
            font-size: 1.1em;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
            margin: 10px;
        }

        .btn:hover {
            background-color: #94cf70;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .btn i {
            margin-right: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }

        th {
            background-color: #ddd590;
            color: white;
            padding: 15px;
            text-transform: uppercase;
            font-size: 0.9em;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }

        .imagen-producto {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }

        .imagen-producto:hover {
            transform: scale(1.1);
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
            font-size: 1.1em;
        }

        .action-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .delete-btn {
            color: #ff4444;
            transition: color 0.3s ease;
        }

        .delete-btn:hover {
            color: #cc0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])): ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h2>¡Tu carrito está vacío!</h2>
                <p>Parece que aún no has agregado productos a tu carrito.</p>
                <a href="index.php" class="btn"><i class="fas fa-shopping-bag"></i>Explorar Productos</a>
            </div>
        <?php else: ?>
            <h2><i class="fas fa-shopping-cart"></i> Carrito de Compras</h2>
            <table>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Imagen</th>
                    <th>Acción</th>
                </tr>
                <?php
                $totalCarrito = 0;
                foreach ($_SESSION['carrito'] as $key => $item):
                    $nombre_producto = $item['producto'];
                    $query = "SELECT nombre, precio, imagen_url FROM productos WHERE nombre = '$nombre_producto'";
                    $result = $conexion->query($query);
                    if ($result->num_rows > 0):
                        $producto = $result->fetch_assoc();
                        $subtotal = $producto['precio'];
                        $totalCarrito += $subtotal;
                ?>
                <tr>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                    <td>1</td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                    <td><img src="<?php echo $producto['imagen_url']; ?>" alt="<?php echo $producto['nombre']; ?>" class="imagen-producto"></td>
                    <td>
                        <a href="eliminar_producto_carri.php?producto=<?php echo urlencode($nombre_producto); ?>" class="delete-btn">
                            <i class="fas fa-trash-alt fa-lg"></i>
                        </a>
                    </td>
                </tr>
                <?php
                    endif;
                endforeach;
                ?>
                <tr class="total-row">
                    <td colspan="3">Total:</td>
                    <td colspan="3">$<?php echo number_format($totalCarrito, 2); ?></td>
                </tr>
            </table>
            <div class="action-buttons">
            <?php
                    if (isset($_SESSION['identificacion'])) {
                        $url_pago = "procesar_pago.php";
                    } else {
                        $url_pago = "sesion.html";
                    }
                ?>
                <a href="<?= $url_pago ?>" class="btn"><i class="fas fa-credit-card"></i> Proceder al Pago</a>
                <a href="eliminar-carrito.php" class="btn"><i class="fas fa-trash"></i>Vaciar Carrito</a>
                <a href="index.php" class="btn"><i class="fas fa-shopping-bag"></i>Seguir Comprando</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conexion->close(); ?>