<?php
require "../includes/header.php";
require "../includes/db.php";

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle form submission for checkout
if (isset($_POST['submit'])) {
    $inp_price = $_POST['inp_price'];
    $_SESSION['price'] = $inp_price;
    echo "<script>window.location.href='" . APPURL . "products/checkout.php';</script>";
}

// Fetch products from the cart
$products = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id");
$products->execute(['user_id' => $_SESSION['user_id']]);
$allProducts = $products->fetchAll(PDO::FETCH_OBJ);

$totalAmount = 0; // Initialize total amount
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?php echo APPURL; ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">Your Cart</h1>
                <p class="lead">Save time and leave the groceries to us.</p>
            </div>
        </div>
    </div>

    <section id="cart">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="10%"></th>
                                    <th>Products</th>
                                    <th>Price</th>
                                    <th width="15%">Quantity</th>
                                    <th width="15%">Update</th>
                                    <th>Subtotal</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($allProducts) > 0) : ?>
                                    <?php foreach ($allProducts as $product) :
                                        $subtotal = $product->pro_price * $product->pro_qty; // Calculate subtotal
                                        $totalAmount += $subtotal; // Add to total amount
                                    ?>
                                        <tr id="product-<?php echo $product->id; ?>">
                                            <td>
                                                <img src="<?php echo APPURL; ?>/assets/img/<?php echo htmlspecialchars($product->pro_image); ?>" width="60">
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($product->pro_title); ?><br>
                                                <small>1000g</small>
                                            </td>
                                            <td class="pro_price" data-price="<?php echo htmlspecialchars($product->pro_price); ?>">
                                                USD <?php echo htmlspecialchars($product->pro_price); ?>
                                            </td>
                                            <td>
                                                <input class="pro_qty form-control" type="number" min="1" value="<?php echo htmlspecialchars($product->pro_qty); ?>" data-id="<?php echo htmlspecialchars($product->id); ?>">
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-primary update-cart" data-id="<?php echo htmlspecialchars($product->id); ?>">UPDATE</a>
                                            </td>
                                            <td class="total_price">
                                                USD <?php echo number_format($subtotal, 2); ?>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" class="text-danger delete-cart-item" data-id="<?php echo htmlspecialchars($product->id); ?>"><i class="fa fa-times"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div class="alert alert-success bg-success text-white text-center">
                                        There are no products in the cart just yet.
                                    </div>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col">
                    <a href="<?php echo APPURL; ?>/shop.php" class="btn btn-default">Continue Shopping</a>
                </div>
                <div class="col text-right">
                    <div class="clearfix"></div>
                    <form method="POST" action="cart.php">
                        <?php if (count($allProducts) > 0) : ?>
                            <input class="inp_price form-control" type="hidden" value="<?php echo number_format($totalAmount, 2); ?>" name="inp_price">
                            <button type="submit" name="submit" class="btn-lg btn-primary">Checkout <i class="fa fa-long-arrow-right"></i></button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Function to calculate subtotal and total amounts
        function calculateTotals() {
            var totalAmount = 0;

            // Loop through each product row to update subtotal and total
            $('.pro_qty').each(function() {
                var $el = $(this).closest('tr');
                var qty = parseInt($(this).val());
                var price = parseFloat($el.find('.pro_price').attr('data-price'));

                if (isNaN(price)) {
                    price = 0;
                }

                var subtotal = qty * price;

                // Update the subtotal for the current product
                $el.find('.total_price').text('USD ' + subtotal.toFixed(2));

                // Add to total amount
                totalAmount += subtotal;
            });

            // Update the total amount
            $('#totalAmount').text(totalAmount.toFixed(2));
            $('input[name="inp_price"]').val(totalAmount.toFixed(2));
        }

        // Function to update the cart count in the navbar
        function updateCartCount() {
            $.ajax({
                url: '/freshcery/includes/cart_count.php', // Endpoint to get the cart count
                method: 'GET',
                success: function(response) {
                    $('.badge').text(response.cartCount);
                },
                error: function() {
                    console.error('Failed to update cart count.');
                }
            });
        }

        // Handle the quantity change event
        $('.pro_qty').on('input', function() {
            // Recalculate totals when quantity is changed
            calculateTotals();
        });

        // Handle update cart functionality
        $('.update-cart').click(function() {
            var id = $(this).data('id');
            var qty = $(this).closest('tr').find('.pro_qty').val();

            // Ensure the quantity is at least 1
            if (qty < 1) {
                qty = 1;
                $(this).closest('tr').find('.pro_qty').val(qty);
            }

            $.ajax({
                url: '/freshcery/products/update-product.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'update',
                    id: id,
                    qty: qty,
                },
                success: function(response) {
                    if (response.status === 'success') {
                        calculateTotals();
                        alert('Product quantity updated successfully.');
                    } else {
                        alert('Error: ' + (response.message || 'Failed to update the cart. Please try again.'));
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX error:", textStatus, errorThrown);
                    alert('An error occurred while updating the cart. Please try again.');
                }
            });
        });

        // Handle delete cart item functionality
        $('.delete-cart-item').click(function() {
            var id = $(this).data('id');

            $.ajax({
                url: '/freshcery/products/delete_product.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'delete',
                    id: id
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Refresh the page after successful deletion
                        location.reload();
                    } else {
                        alert(response.message || 'Failed to delete the item. Please try again.');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX error:", textStatus, errorThrown);
                    alert('An error occurred while deleting the item. Please try again.');
                }
            });
        });
    });
</script>

<?php require "../includes/footer.php"; ?>
