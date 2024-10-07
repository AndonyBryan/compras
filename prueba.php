<?php
session_start();

// Reemplazar con una ruta donde se guardarán las imágenes
$images = [
    "1" => "https://m.media-amazon.com/images/I/71LDd1ocd0L.jpg",
    "2" => "https://chedrauimx.vtexassets.com/arquivos/ids/35357437/5010993866144_00.jpg?v=638612032713200000",
    "3" => "https://f.fcdn.app/imgs/12f149/www.atrixuy.com/atriuy/0b1d/original/catalogo/TBJUG1983ROJO_TBJUG1983ROJO_1/1500-1500/gonher-pistola-revolver-arma-juguete-cowboy-ninos-infantil-gonher-pistola-revolver-arma-juguete-cowboy-ninos-infantil.jpg",
];

if (isset($_POST["add_to_cart"])) {
    if (isset($_SESSION["shopping_cart"])) {
        $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
        if (!in_array($_GET["id"], $item_array_id)) {
            $count = count($_SESSION["shopping_cart"]);
            $item_array = array(
                'item_id' => $_GET["id"],
                'item_name' => $_POST["hidden_name"],
                'item_price' => $_POST["hidden_price"],
                'item_quantity' => $_POST["quantity"]
            );
            $_SESSION["shopping_cart"][$count] = $item_array;
            echo '<script>alert("Producto agregado al carrito")</script>';
        } else {
            echo '<script>alert("El producto ya se encuentra agregado")</script>';
        }
    } else {
        $item_array = array(
            'item_id' => $_GET["id"],
            'item_name' => $_POST["hidden_name"],
            'item_price' => $_POST["hidden_price"],
            'item_quantity' => $_POST["quantity"]
        );
        $_SESSION["shopping_cart"][0] = $item_array;
        echo '<script>alert("Producto agregado al carrito")</script>';
    }
}

if (isset($_GET["action"])) {
    if ($_GET["action"] == "delete") {
        foreach ($_SESSION["shopping_cart"] as $keys => $values) {
            if ($values["item_id"] == $_GET["id"]) {
                unset($_SESSION["shopping_cart"][$keys]);
                echo '<script>alert("Producto eliminado")</script>';
                echo '<script>window.location="prueba.php"</script>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Carro de Compra</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .thumbnail {
            transition: transform 0.2s;
        }
        .thumbnail:hover {
            transform: scale(1.05);
        }
        .btn-success {
            transition: background-color 0.3s;
        }
        .btn-success:hover {
            background-color: #28a745;
            color: white;
        }
        .table th, .table td {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container" style="width:800px;">
    <h3 align="center">Carro de Compra</h3>

    <!-- Barra de navegación con pestañas -->
    <ul class="nav nav-pills">
        <li class="active"><a data-toggle="pill" href="#home">Inicio</a></li>
        <li><a data-toggle="pill" href="#orderDetails">Detalles del Carro</a></li>
        <li><a data-toggle="pill" href="#profile">Perfil</a></li>
        <li><a data-toggle="pill" href="#contact">Contacto</a></li>
    </ul>

    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <div class="row">
                <?php
                $productos = [
                    ["id" => "1", "name" => "Producto 1", "price" => 100.00],
                    ["id" => "2", "name" => "Producto 2", "price" => 150.00],
                    ["id" => "3", "name" => "Producto 3", "price" => 200.00],
                ];

                foreach ($productos as $row) {
                    ?>
                    <div class="col-md-4">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>?action=add&id=<?php echo $row["id"]; ?>">
                            <div class="thumbnail">
                                <img src="<?php echo $images[$row["id"]]; ?>" class="img-responsive" />
                                <div class="caption">
                                    <h4 class="text-info text-center"><?php echo $row["name"]; ?></h4>
                                    <h4 class="text-danger text-center">$ <?php echo $row["price"]; ?></h4>
                                    <input type="text" name="quantity" class="form-control" value="1" />
                                    <p class='text-center'>
                                        <input type="submit" name="add_to_cart" class="btn btn-success" value="Agregar al carro" />
                                    </p>
                                    <input type="hidden" name="hidden_name" value="<?php echo $row["name"]; ?>" />
                                    <input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>" />
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <div id="orderDetails" class="tab-pane fade">
            <h3>Detalle de la orden</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Descripción</th>
                        <th width="10%" class='text-center'>Cantidad</th>
                        <th width="20%" class='text-right'>Precio</th>
                        <th width="15%" class='text-right'>Total</th>
                        <th width="5%"></th>
                    </tr>
                    <?php
                    if (!empty($_SESSION["shopping_cart"])) {
                        $total = 0;
                        foreach ($_SESSION["shopping_cart"] as $keys => $values) {
                            ?>
                            <tr>
                                <td><?php echo $values["item_name"]; ?></td>
                                <td class='text-center'><?php echo $values["item_quantity"]; ?></td>
                                <td class='text-right'>$ <?php echo $values["item_price"]; ?></td>
                                <td class='text-right'>$ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>
                                <td><a href="<?php echo 'prueba.php'; ?>?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Eliminar</span></a></td>
                            </tr>
                            <?php
                            $total += ($values["item_quantity"] * $values["item_price"]);
                        }
                        ?>
                        <tr>
                            <td colspan="3" align="right">Total</td>
                            <td align="right">$ <?php echo number_format($total, 2); ?></td>
                            <td></td>
                        </tr>
                        <?php
                    } else {
                        ?>
                        <tr>
                            <td colspan="5" class='text-center'>No hay productos en el carro</td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>

        <div id="profile" class="tab-pane fade">
            <h4>Perfil de Usuario</h4>
            <p><strong>Nombre:</strong> Antony Matuz</p>
            <p><strong>Matrícula:</strong> 22887049</p>
            <p><strong>Carrera:</strong> Ingeniería en Informática</p>
            <p><strong>Semestre:</strong> 5º semestre</p>
        </div>

        <div id="contact" class="tab-pane fade">
            <h4>Contacto</h4>
            <p>Email: <a href="mailto:your.email@example.com">your.email@example.com</a></p>
        </div>
    </div>
</div>
</body>
</html>
