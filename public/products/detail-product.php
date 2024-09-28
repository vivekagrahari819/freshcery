<?php 

// Redirect if no HTTP referer
if (!isset($_SERVER['HTTP_REFERER'])) {
    header('Location: http://localhost/freshcery/index.php');
    exit;
}

?>

<?php
require "../includes/header.php";
require "../includes/db.php";

// session_start(); // Ensure the session is started

if (isset($_POST['submit'])) {
    $pro_id = $_POST['pro_id'];
    $pro_title = $_POST['pro_title'];
    $pro_image = $_POST['pro_image'];
    $pro_price = $_POST['pro_price'];
    $pro_qty = $_POST['pro_qty'];
    $user_id = $_POST['user_id'];

    $data = $pdo->prepare("INSERT INTO cart (pro_id, pro_title, pro_image, pro_price, pro_qty, user_id) VALUES (:pro_id, :pro_title, :pro_image, :pro_price, :pro_qty, :user_id)");
    $data->execute([
        ':pro_id' => $pro_id,
        ':pro_title' => $pro_title,
        ':pro_image' => $pro_image,
        ':pro_price' => $pro_price,
        ':pro_qty' => $pro_qty,
        ':user_id' => $user_id
    ]);

    echo "Product added to cart!";
}

$validate = null; // Initialize $validate

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE status = 1 AND id = :id");
    $stmt->execute(['id' => $id]);

    $product = $stmt->fetch(PDO::FETCH_OBJ);

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $validate = $pdo->prepare("SELECT * FROM cart WHERE pro_id = :id AND user_id = :user_id");
        $validate->execute(['id' => $id, 'user_id' => $user_id]);
    }

    if ($product) {
        $relatedProductsStmt = $pdo->prepare("SELECT * FROM products WHERE status = 1 AND category_id = :category_id AND id != :id");
        $relatedProductsStmt->execute(['category_id' => $product->category_id, 'id' => $id]);

        $AllrelatedProducts = $relatedProductsStmt->fetchAll(PDO::FETCH_OBJ);
    } else {
        echo "<p>Product not found.</p>";
        exit;
    }
} else {
    echo "<script> window.location.href='" . APPURL ." /404.php' ; </script>";
    exit;
}
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?php echo APPURL; ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5"><?php echo htmlspecialchars($product->title); ?></h1>
                <p class="lead">Save time and leave the groceries to us.</p>
            </div>
        </div>
    </div>
    <div class="product-detail">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="slider-zoom">
                        <a href="<?php echo APPURL; ?>/assets/img/<?php echo htmlspecialchars($product->image); ?>" class="cloud-zoom">
                            <img alt="Detail Zoom thumbs image" src="<?php echo APPURL; ?>/assets/img/<?php echo htmlspecialchars($product->image); ?>" class="product-image">
                        </a>
                    </div>
                </div>
                <div class="col-sm-6">
                    <p><strong>Overview</strong><br><?php echo htmlspecialchars($product->description); ?></p>
                    <div class="row">
                        <div class="col-sm-6">
                            <p><strong>Price</strong> (/Pack)<br><span class="price"><?php echo htmlspecialchars($product->price); ?>$</span></p>
                        </div>
                    </div>
                    <p class="mb-1"><strong>Quantity</strong></p>

                    <form method="post" id="form-data">
                        <input class="form-control" type="hidden" name="pro_title" value="<?php echo htmlspecialchars($product->title); ?>" readonly>
                        <input class="form-control" type="hidden" name="pro_image" value="<?php echo htmlspecialchars($product->image); ?>" readonly>
                        <input class="form-control" type="hidden" name="pro_price" value="<?php echo htmlspecialchars($product->price); ?>" readonly>
                        <input class="form-control" type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_id']) : 'N/A'; ?>" readonly>
                        <input class="form-control" type="hidden" name="pro_id" value="<?php echo htmlspecialchars($product->id); ?>" readonly>
                        <div class="row">
                            <div class="col-sm-5">
                                <input class="form-control" type="number" min="1" value="<?php echo htmlspecialchars($product->quantity); ?>" name="pro_qty">
                            </div>
                            <div class="col-sm-6"><span class="pt-1 d-inline-block">Pack (1000 gram)</span></div>
                        </div>
                        <?php if (isset($_SESSION['username'])) : ?>
                            <?php if ($validate && $validate->rowCount() > 0) : ?>
                                <button name="submit" type="submit" class="btn-insert mt-3 btn btn-primary btn-lg" disabled><i class="fa fa-shopping-basket"></i> Add to Cart</button>
                            <?php else : ?>
                                <button name="submit" type="submit" class="btn-insert mt-3 btn btn-primary btn-lg"><i class="fa fa-shopping-basket"></i> Add to Cart</button>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-success bg-success text-white text-center">
                                Log in to buy this product or add it to cart
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <section id="related-product">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title">Related Products</h2>
                    <div class="product-carousel owl-carousel">
                        <?php if ($AllrelatedProducts): ?>
                            <?php foreach ($AllrelatedProducts as $relatedProduct): ?>
                                <div class="item">
                                    <div class="card card-product">
                                        <div class="card-ribbon">
                                            <div class="card-ribbon-container right">
                                                <span class="ribbon ribbon-primary">SPECIAL</span>
                                            </div>
                                        </div>
                                        <div class="card-badge">
                                            <div class="card-badge-container left">
                                                <span class="badge badge-default">Until <?php echo htmlspecialchars($relatedProduct->exp_date); ?></span>
                                                <span class="badge badge-primary">20% OFF</span>
                                            </div>
                                            <img src="<?php echo APPURL; ?>/assets/img/<?php echo htmlspecialchars($relatedProduct->image); ?>" alt="Card image" class="product-image">
                                        </div>
                                        <div class="card-body">
                                            <h4 class="card-title"><a href="detail-product.php?id=<?php echo htmlspecialchars($relatedProduct->id); ?>"><?php echo htmlspecialchars($relatedProduct->title); ?></a></h4>
                                            <div class="card-price">
                                                <span class="reguler"><?php echo htmlspecialchars($relatedProduct->price); ?>$</span>
                                            </div>
                                            <a href="detail-product.php?id=<?php echo htmlspecialchars($relatedProduct->id); ?>" class="btn btn-block btn-primary">Add to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No related products found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require "../includes/footer.php"; ?>

<script>
    $(document).ready(function() {
        $(".form-control").on('input', function() {
            var value = $(this).val();
            if (value < 1) {
                $(this).val(1);
            }
        });

        $('.btn-insert').on('click', function(e) {
            e.preventDefault();

            var form_data = $("#form-data").serialize() + "&submit=submit";

            $.ajax({
                url: "detail-product.php?id=<?php echo $id; ?>",
                method: "POST",
                data: form_data,
                success: function() {
                    alert("Product added to cart");
                    $(".btn-insert").html("<i class='fa fa-shopping-basket'></i> Added to Cart").prop("disabled", true);
                    withRef();
                }
            });
        });

        function withRef() {
            $("body").load("detail-product.php?id=<?php echo $id; ?>");
        }
    });
</script>

<style>
    .product-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }
</style>
