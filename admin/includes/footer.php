  <script>

    
        $("#example1").DataTable({
           "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "bAutoWidth": true,
      "stateSave":true
        });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "bAutoWidth": false,
      "stateSave":true
    });

    // ==========================================
    // SISTEMA DE TECLAS: F9 MOSTRAR / F8 OCULTAR MENÚ TRANSPORTE
    // ==========================================
    $(document).ready(function() {
        // Leer estado inicial desde localStorage
        var menuTransporteVisible = localStorage.getItem('menuTransporteVisible') === 'true';
        
        // Aplicar estado inicial
        if (menuTransporteVisible) {
            $('#menu-transporte-oculto').show();
            $('#menu-hoteles-oculto').show();
        } else {
            $('#menu-transporte-oculto').hide();
            $('#menu-hoteles-oculto').hide();
        }
        
        // Detectar teclas F9 y F8
        $(document).on('keydown', function(e) {
            // F9 = Mostrar menú (keyCode 120)
            if (e.keyCode === 120) {
                e.preventDefault();
                $('#menu-transporte-oculto').slideDown(300);
                $('#menu-hoteles-oculto').slideDown(300);
                localStorage.setItem('menuTransporteVisible', 'true');
                
                // Notificación visual
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Menús ocultos',
                        text: 'Transporte y Hoteles activados. Presiona F8 para ocultar.',
                        timer: 2000,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true
                    });
                }
            }
            
            // F8 = Ocultar menú (keyCode 119)
            if (e.keyCode === 119) {
                e.preventDefault();
                $('#menu-transporte-oculto').slideUp(300);
                $('#menu-hoteles-oculto').slideUp(300);
                localStorage.setItem('menuTransporteVisible', 'false');
                
                // Notificación visual
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Menús ocultos',
                        text: 'Transporte y Hoteles desactivados. Presiona F9 para mostrar.',
                        timer: 2000,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true
                    });
                }
            }
        });
    });

  
</script>  
<footer class="main-footer">
    <strong>Copyright &copy; 2014-<?=date("Y");?> <a href="https://sistemanz.com.ar">SisteManz</a>.</strong>
    Todos los derechos reservados.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0
    </div>
  </footer>
  
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->



</body>
</html>
           



