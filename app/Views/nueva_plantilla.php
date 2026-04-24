
        <section>
            <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="assets/img/Whisky.png" class="d-block w-100" alt="...">
                    </div>
                <div class="carousel-item">
                    <img src="assets/img/Vino.png" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="assets/img/Cerveza.png" class="d-block w-100" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            </div>
        </section>

        <div class="container py-5">
            <div class="row">
                <div class="col-12 text-center">
                    <h3>Bienvenidos a BushmillsDrinks, tu tienda online de bebidas favorita.</h3>
                    <p class="lead mt-4 fs-6">
                    En nuestro sitio encontrarás una amplia variedad de licores, vinos, cervezas y destilados de las mejores marcas (y aquellas no tan buenas), ideales para cada ocasión. Ya sea que busques el trago perfecto para una celebración, una reunión o simplemente disfrutar de un buen momento, tenemos lo que necesitas.
                    </p>
                </div>
            </div>
        </div>

        <section class ="categorias">
            <h2 class="m-5">Explora nuestras categorias destacadas >></h2>

            <div class="container mt-5">
        
                <div class="row mt-4">
                    <!-- Card 1 -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="assets/img/WhiskyCat.png" class="card-img-top img-fluid" alt="Producto 1">
                            <div class="card-body">
                                <h5 class="card-title">Whiskys</h5>
                                <p class="card-text">Tenemos las mejores opciones de whiskys nacionales e importados</p>
                                <a href="productos#whiskys" class="btn btn-dark d-block mx-auto">Ver más</a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="assets/img/CervezaCat.png" class="card-img-top img-fluid" alt="Producto 2">
                            <div class="card-body">
                                <h5 class="card-title">Cervezas</h5>
                                <p class="card-text">Contamos con amplia variedad en cervezas industriales y artesanales</p>
                                <a href="productos#cervezas" class="btn btn-dark d-block mx-auto">Ver más</a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="assets/img/VinoCat.png" class="card-img-top img-fluid" alt="Producto 3">
                            <div class="card-body">
                                <h5 class="card-title">Vinos</h5>
                                <p class="card-text">Explora la gran variedad de vinos que te estan esperando</p>
                                <a href="productos#vinos" class="btn btn-dark d-block mx-auto">Ver más</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- Card 4 -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="assets/img/AperitivoCat.png" class="card-img-top img-fluid" alt="Producto 4">
                            <div class="card-body">
                                <h5 class="card-title">Aperitivos</h5>
                                <p class="card-text">Encontra el aperitivo que mas te guste</p>
                                <a href="productos#aperitivos" class="btn btn-dark d-block mx-auto">Ver más</a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5 -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="assets/img/EspumanteCat.png" class="card-img-top img-fluid" alt="Producto 5">
                            <div class="card-body">
                                <h5 class="card-title">Espumantes</h5>
                                <p class="card-text">Brinda donde sea con nuestros espumantes</p>
                                <a href="productos#espumantes" class="btn btn-dark d-block mx-auto">Ver más</a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 6 -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="assets/img/GinCat.png" class="card-img-top img-fluid" alt="Producto 6">
                            <div class="card-body">
                                <h5 class="card-title">Gin</h5>
                                <p class="card-text">Disfruta de los diversos Gin's que tenemos para vos</p>
                                <a href="productos#gins" class="btn btn-dark d-block mx-auto">Ver más</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="m-5">
                <a href="productos" class="text-decoration-none linkPersonalizado">
                    Ver todas las categorias>>
                </a>
            </h2>
        </section>



<section class="border border-5 p-5" id="Ofertas">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <h1 class="text-center text-dark fw-bold" style="font-family: 'Bebas Neue', sans-serif;">
        OFERTAS
    </h1>
    <div class="row">
        <?php if (!empty($bebidas)): ?>
            <?php foreach ($bebidas as $b): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?= base_url('assets/upload/' . $b['imagen_bebida']) ?>" 
                             class="card-img-top" 
                             alt="<?= esc($b['nombre_bebida']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($b['nombre_bebida']) ?></h5>
                            <p class="card-text"><?= esc($b['descripcion_bebida']) ?></p>
                            <p class="card-text">
                                <span>
                                    $<?= number_format($b['precio_bebida'], 2, ',', '.') ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No hay bebidas disponibles en este momento.</p>
        <?php endif; ?>
    </div>
</section>






