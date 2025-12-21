```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Vendedor</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .header {
            background-color: #343a40;
            color: white;
            padding: 1.5rem 0;
            text-align: center;
            margin-bottom: 2rem;
            border-bottom: 5px solid #0d6efd;
        }
        .action-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            cursor: pointer;
            border: none;
            border-radius: 0.75rem;
            text-decoration: none;
            color: inherit;
        }
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            color: inherit;
        }
        .action-card .card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .action-card i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #0d6efd;
        }
        .action-card.primary-action i {
             color: #ffffff;
        }
        .action-card.primary-action {
            background-color: #0d6efd;
            color: white;
        }
        .action-card h5 {
            font-weight: 700;
        }
        .login-section {
            text-align: center;
            margin-top: 3rem;
            padding: 2rem;
            background-color: #e9ecef;
            border-radius: 0.75rem;
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="container">
            <h1>Panel de Vendedor</h1>
            <p class="lead mb-0">Gestión rápida de ventas y actividades.</p>
        </div>
    </header>

    <main class="container py-4">
        <div class="row g-4 justify-content-center">
            <!-- Venta Rápida -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card action-card primary-action h-100" data-bs-toggle="modal" data-bs-target="#quickSaleModal">
                    <div class="card-body text-center">
                        <i class="fas fa-bolt"></i>
                        <h5 class="card-title">Venta Rápida</h5>
                        <p class="card-text">Inicia una reserva con el ID del servicio.</p>
                    </div>
                </div>
            </div>

            <!-- Ver Actividades -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="/app.categorias.php" class="card action-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-search"></i>
                        <h5 class="card-title">Ver Actividades</h5>
                        <p class="card-text">Consulta disponibilidad y reserva.</p>
                    </div>
                </a>
            </div>

            <!-- Mis Ventas -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="/admin/comisionesLista.php" class="card action-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line"></i>
                        <h5 class="card-title">Mis Ventas</h5>
                        <p class="card-text">Revisa tu historial y comisiones.</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Sección de Login/Estado de sesión -->
        <div id="login-section" class="login-section mt-5">
             <p class="text-muted">Para ver comisiones y disponibilidad completa, inicia sesión.</p>
             <a href="/admin" class="btn btn-dark btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i> Acceso Admin
             </a>
        </div>
    </main>

    <!-- Modal de Venta Rápida -->
    <div class="modal fade" id="quickSaleModal" tabindex="-1" aria-labelledby="quickSaleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickSaleModalLabel">Iniciar Venta Rápida</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Introduce el número del servicio para iniciar la reserva.</p>
                    <form id="quickSaleForm" onsubmit="return false;">
                        <div class="mb-3">
                            <label for="service-id-input" class="form-label">Número de Servicio</label>
                            <input type="number" class="form-control form-control-lg" id="service-id-input" placeholder="Ej: 12345" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="start-quick-sale-btn">Iniciar Reserva Rápida</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startSaleBtn = document.getElementById('start-quick-sale-btn');
            const serviceIdInput = document.getElementById('service-id-input');

            const performRedirect = () => {
                const serviceId = serviceIdInput.value.trim();
                if (serviceId && !isNaN(serviceId)) {
                    window.location.href = `/servicio?id=${serviceId}`;
                } else {
                    alert('Por favor, introduce un número de servicio válido.');
                    serviceIdInput.focus();
                }
            };

            if (startSaleBtn) {
                startSaleBtn.addEventListener('click', performRedirect);
            }
            
            if (serviceIdInput) {
                serviceIdInput.addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        performRedirect();
                    }
                });
            }

            // Gestiona la visualización según el estado de la sesión.
            try {
                const loginSection = document.getElementById('login-section');
                if (localStorage.getItem('isAdminLoggedIn') === 'true') {
                    if (loginSection) {
                        loginSection.innerHTML = `
                            <div class="alert alert-success d-flex flex-column align-items-center text-center" role="alert">
                                <h4 class="alert-heading"><i class="fas fa-check-circle me-2"></i>¡Sesión Iniciada!</h4>
                                <p class="mb-3">Has accedido como administrador.</p>
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                     <a href="/admin" class="btn btn-dark">
                                        <i class="fas fa-user-shield me-2"></i> Ir al Panel de Admin
                                     </a>
                                     <button id="logout-btn" class="btn btn-outline-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                     </button>
                                </div>
                            </div>
                        `;
                        
                        const logoutBtn = document.getElementById('logout-btn');
                        if(logoutBtn) {
                            logoutBtn.addEventListener('click', function() {
                                localStorage.removeItem('isAdminLoggedIn');
                                window.location.reload();
                            });
                        }
                    }
                }
            } catch (e) {
                console.error("No se pudo acceder a localStorage. El estado de la sesión no se puede verificar.", e);
            }
        });
    </script>
</body>
</html>
```