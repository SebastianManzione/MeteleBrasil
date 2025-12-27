



  <!-- Main Sidebar Container -->

  <aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand Logo -->

    <a href="index" class="brand-link">

      <img src="dist/img/AdminLTELogo.png" class="brand-image img-circle elevation-3"

           style="opacity: .8">

      <span class="brand-text font-weight-light">Reservate</span>

    </a>



    <!-- Sidebar -->

    <div class="sidebar">

      <!-- Sidebar user panel (optional) -->

      <div class="user-panel mt-3 pb-3 mb-3 d-flex">

        <div class="image">

          <!--<img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">-->

        </div>

        <div class="info">

          <a href="#" class="d-block"><?=$_SESSION["login"]["usuario"] ?? $_SESSION["login"]["nombre"] ?? 'Usuario'?></a>

        </div>

      </div>


      <!-- Sidebar Menu desde BD -->
      <?php
      // SOLO menú desde BD - Sin fallback legacy
      require_once(__DIR__ . '/../classes/menu.php');
      require_once(__DIR__ . '/../classes/permisos.php');
      require_once(__DIR__ . '/permisos_helper.php');
      
      // INICIALIZAR $GLOBALS['permisos'] antes de incluir sidebar_db.php
      if (!isset($GLOBALS['permisos']) || !$GLOBALS['permisos']) {
        $GLOBALS['permisos'] = new PermisosManager($GLOBALS['pdo'] ?? null, $_SESSION['login'] ?? []);
      }
      
      try {
        include(__DIR__ . '/sidebar_db.php');
      } catch (Exception $e) {
        echo '<nav class="mt-2"><ul class="nav nav-pills nav-sidebar flex-column">';
        echo '<li class="nav-item"><a href="#" class="nav-link">';
        echo '<i class="nav-icon fas fa-exclamation-triangle text-danger"></i>';
        echo '<p>Error cargando menú: ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '</a></li></ul></nav>';
        error_log("Sidebar DB Error: " . $e->getMessage());
      }
      ?>

    </div>

    <!-- /.sidebar -->

  </aside>

