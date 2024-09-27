<?php require "includes/header.php"; ?>
<?php require "includes/db.php"; // Include your database connection file

// Fetch categories from the database
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// products 
$products = $pdo->query("SELECT * FROM products where status = 1");
$allmostproducts = $products->fetchAll(PDO::FETCH_ASSOC);

// vegies
$vegies = $pdo->query("SELECT * FROM products where status = 1 and category_id=1 ");
$allvegies = $vegies->fetchAll(PDO::FETCH_ASSOC);

// meats
$meats = $pdo->query("SELECT * FROM products where status = 1 and category_id=2 ");
$allmeats = $meats->fetchAll(PDO::FETCH_ASSOC);

// fish
$fish = $pdo->query("SELECT * FROM products where status = 1 and category_id=3 ");
$allfishes = $fish->fetchAll(PDO::FETCH_ASSOC);

// fruits
$fruits = $pdo->query("SELECT * FROM products where status = 1 and category_id=4 ");
$allfruits = $fruits->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
/* Add CSS to make all product images the same size */
.card-img-top {
    width: 100%;
    height: 250px; /* Set a fixed height for all images */
    object-fit: cover; /* This ensures the image will cover the area without being distorted */
    border-radius: 8px; /* Optional: add a border radius for a smooth look */
}
</style>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">
                    Shopping Page
                </h1>
                <p class="lead">
                    Save time and leave the groceries to us.
                </p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="shop-categories owl-carousel mt-5">
                    <?php foreach ($categories as $category): ?>
                        <div class="item">
                            <a href="shop.php">
                                <div class="media d-flex align-items-center justify-content-center">
                                    <span class="d-flex mr-2"><i class="sb-<?php echo htmlspecialchars($category['icon']); ?>"></i></span>
                                    <div class="media-body">
                                        <h5><?php echo htmlspecialchars($category['name']); ?></h5>
                                        <p><?php echo htmlspecialchars(substr($category['description'], 0, 20)); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <section id="most-wanted">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title">Most Wanted</h2>
                    <div class="product-carousel owl-carousel">
                        <?php foreach ($allmostproducts as $allmostproduct): ?>
                            <div class="item">
                                <div class="card card-product">
                                    <div class="card-ribbon">
                                        <div class="card-ribbon-container right">
                                            <span class="ribbon ribbon-primary">SPECIAL</span>
                                        </div>
                                    </div>
                                    <div class="card-badge">
                                        <div class="card-badge-container left">
                                            <span class="badge badge-default">
                                                Until <?php echo htmlspecialchars($allmostproduct['exp_date']); ?>
                                            </span>
                                            <span class="badge badge-primary">
                                                20% OFF
                                            </span>
                                        </div>
                                        <img src="assets/img/<?php echo htmlspecialchars($allmostproduct['image']); ?>" alt="Product Image" class="card-img-top">
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title">
                                            <a href="detail-product.php"><?php echo htmlspecialchars($allmostproduct['title']); ?></a>
                                        </h4>
                                        <div class="card-price">
                                            <span class="reguler"><?php echo htmlspecialchars($allmostproduct['price']); ?>$</span>
                                        </div>
                                        <a href="<?php echo APPURL; ?>products/detail-product.php?id=<?php echo htmlspecialchars($allmostproduct['id']); ?>" class="btn btn-block btn-primary">
                                            Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="vegetables" class="gray-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title">Vegetables</h2>
                    <div class="product-carousel owl-carousel">
                        <?php foreach ($allvegies as $allvigi): ?>
                            <div class="item">
                                <div class="card card-product">
                                    <div class="card-ribbon">
                                        <div class="card-ribbon-container right">
                                            <span class="ribbon ribbon-primary">SPECIAL</span>
                                        </div>
                                    </div>
                                    <div class="card-badge">
                                        <div class="card-badge-container left">
                                            <span class="badge badge-default">
                                                Until <?php echo htmlspecialchars($allvigi['exp_date']); ?>
                                            </span>
                                            <span class="badge badge-primary">
                                                20% OFF
                                            </span>
                                        </div>
                                        <img src="assets/img/<?php echo htmlspecialchars($allvigi['image']); ?>" alt="Product Image" class="card-img-top">
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title">
                                            <a href="detail-product.php"><?php echo htmlspecialchars($allvigi['title']); ?></a>
                                        </h4>
                                        <div class="card-price">
                                            <span class="reguler"><?php echo htmlspecialchars($allvigi['price']); ?>$</span>
                                        </div>
                                        <a href="<?php echo APPURL; ?>products/detail-product.php?id=<?php echo htmlspecialchars($allvigi['id']); ?>" class="btn btn-block btn-primary">
                                            Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="meats">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title">Meats</h2>
                    <div class="product-carousel owl-carousel">
                        <?php foreach ($allmeats as $allmeat): ?>
                            <div class="item">
                                <div class="card card-product">
                                    <div class="card-ribbon">
                                        <div class="card-ribbon-container right">
                                            <span class="ribbon ribbon-primary">SPECIAL</span>
                                        </div>
                                    </div>
                                    <div class="card-badge">
                                        <div class="card-badge-container left">
                                            <span class="badge badge-default">
                                                Until <?php echo htmlspecialchars($allmeat['exp_date']); ?>
                                            </span>
                                            <span class="badge badge-primary">
                                                20% OFF
                                            </span>
                                        </div>
                                        <img src="assets/img/<?php echo htmlspecialchars($allmeat['image']); ?>" alt="Product Image" class="card-img-top">
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title">
                                            <a href="detail-product.php"><?php echo htmlspecialchars($allmeat['title']); ?></a>
                                        </h4>
                                        <div class="card-price">
                                            <span class="reguler"><?php echo htmlspecialchars($allmeat['price']); ?>$</span>
                                        </div>
                                        <a href="<?php echo APPURL; ?>products/detail-product.php?id=<?php echo htmlspecialchars($allmeat['id']); ?>" class="btn btn-block btn-primary">
                                            Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fishes" class="gray-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title">Fishes</h2>
                    <div class="product-carousel owl-carousel">
                        <?php foreach ($allfishes as $allfish): ?>
                            <div class="item">
                                <div class="card card-product">
                                    <div class="card-ribbon">
                                        <div class="card-ribbon-container right">
                                            <span class="ribbon ribbon-primary">SPECIAL</span>
                                        </div>
                                    </div>
                                    <div class="card-badge">
                                        <div class="card-badge-container left">
                                            <span class="badge badge-default">
                                                Until <?php echo htmlspecialchars($allfish['exp_date']); ?>
                                            </span>
                                            <span class="badge badge-primary">
                                                20% OFF
                                            </span>
                                        </div>
                                        <img src="assets/img/<?php echo htmlspecialchars($allfish['image']); ?>" alt="Product Image" class="card-img-top">
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title">
                                            <a href="detail-product.php"><?php echo htmlspecialchars($allfish['title']); ?></a>
                                        </h4>
                                        <div class="card-price">
                                            <span class="reguler"><?php echo htmlspecialchars($allfish['price']); ?>$</span>
                                        </div>
                                        <a href="<?php echo APPURL; ?>products/detail-product.php?id=<?php echo htmlspecialchars($allfish['id']); ?>" class="btn btn-block btn-primary">
                                            Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fruits">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title">Fruits</h2>
                    <div class="product-carousel owl-carousel">
                        <?php foreach ($allfruits as $allfruit): ?>
                            <div class="item">
                                <div class="card card-product">
                                    <div class="card-ribbon">
                                        <div class="card-ribbon-container right">
                                            <span class="ribbon ribbon-primary">SPECIAL</span>
                                        </div>
                                    </div>
                                    <div class="card-badge">
                                        <div class="card-badge-container left">
                                            <span class="badge badge-default">
                                                Until <?php echo htmlspecialchars($allfruit['exp_date']); ?>
                                            </span>
                                            <span class="badge badge-primary">
                                                20% OFF
                                            </span>
                                        </div>
                                        <img src="assets/img/<?php echo htmlspecialchars($allfruit['image']); ?>" alt="Product Image" class="card-img-top">
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title">
                                            <a href="detail-product.php"><?php echo htmlspecialchars($allfruit['title']); ?></a>
                                        </h4>
                                        <div class="card-price">
                                            <span class="reguler"><?php echo htmlspecialchars($allfruit['price']); ?>$</span>
                                        </div>
                                        <a href="<?php echo APPURL; ?>products/detail-product.php?id=<?php echo htmlspecialchars($allfruit['id']); ?>" class="btn btn-block btn-primary">
                                            Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require "includes/footer.php"; ?>
