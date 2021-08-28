

<?php 





include("includes/header.php");

include("includes/navbar.php");

include("includes/sidebar.php");

require("classes/functions.php");

require("classes/prestador.php");

require("classes/usuario.php");

require("classes/reserva.php");

require("classes/salidas.php");

require("classes/categoria.php");

require("classes/servicio.php");

require("classes/comprobantes.php");

require("classes/convierte_monedas.php");





if (!$_SESSION["login"]["rol"]==1) {

  alertar("Usted no tiene acceso a esta seccion del software", "error");

  redireccionarLento("index");

}



 ?>

  <!-- Content Wrapper. Contains page content -->


    <!-- Content Header (Page header) -->




        <!-- SELECT2 EXAMPLE -->

 <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 4 -->

  <!-- Font Awesome -->
  <link rel="stylesheet" href="../admin/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https:/admin/code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../admin/dist/css/adminlte.min.css">

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">



        <!-- /.Responsive -->

        <!-- SELECT2 EXAMPLE -->


<div class="table-responsive">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Voucher</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Financiero</a></li>
              <li class="breadcrumb-item active">Voucher de Servicio</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="callout callout-info">
              <h5><i class="fas fa-info"></i> Importante:</h5>
              This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
            </div>

            <div class="callout callout-info">
              <h5><i class="fas fa-info"></i> Politicas do voucher:</h5>
              Este voucher nao pode ser transferido para outras pessoas <br>
              O servicos nao rembolsavel depois dos 7 dias da compra na podem ser transferidos, (sem exepcao)<br>
              Este voucher nao pode ser transferido para outras pessoas <br>
              O servicos nao rembolsavel depois dos 7 dias da compra na podem ser transferidos, (sem exepcao)<br>
            </div>


            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <h3 class="page-header">
           <img src="../img/favicon.png"><strong>  METELE BRASIL</strong>
        </h3>
                    <small class="float-right">Date: 2/12/2021</small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  Responsavél da reserva
                  <address>
               <strong>Jorge Manzione</strong><br>
                    <b>Usuario:</b> jorge@hotmail.com<br>
                    <b>Responsavél do pagamento:</b>Jorge Manzione<br>
                    <b>Fecha de compra:</b> 10/5/2021<br>
                    <b>Validade do Voucher:</b> 2/12/2021<br>
                    
                    
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  Pais
                  <address>
               <strong>Brasil</strong><br>
                    <b>Idioma:</b> PT<br>
                    <b>Data do Evento:</b> 2/12/2021<br>
                    <b>Horario de check in:</b> 10:30<br>
                    <b>Horario de saída:</b>11:00br>
                    

                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Numero de comprovante</b><b> #007612</b><br>
                  <b>Numero de ordem ID:</b> 4F3S8J<br>
                  <b>Telefone do prestador:</b><b> (804) 123-5432</b><br>
                  <b>Email do prestador:</b><b> info@almasaeedstudio.com</b>
                  
                  
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->


<div class="callout callout-info">
              <h3><i class="fas fa-info"></i> Ponto de sáida:</h3>
              <br>
              <br>
             <br>
             <br>
            </div>

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Nombre y apellido</th>
                      <th>Tipo de tarifa</th>
                      <th>Codigo</th>
                      <th>Comentario</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>1</td>
                      <td>Call of Duty</td>
                      <td>455-981-221</td>
                      <td>El snort testosterone trophy driving gloves handsome</td>
                      <td>$64.50</td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Need for Speed IV</td>
                      <td>247-925-726</td>
                      <td>Wes Anderson umami biodiesel</td>
                      <td>$50.00</td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Monsters DVD</td>
                      <td>735-845-642</td>
                      <td>Terry Richardson helvetica tousled street art master</td>
                      <td>$10.70</td>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>Grown Ups Blue Ray</td>
                      <td>422-568-642</td>
                      <td>Tousled lomo letterpress</td>
                      <td>$25.99</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
     <div class="callout callout-info">
              <h5><i class="fas fa-info"></i> Politicas do voucher:</h5>
              Este voucher nao pode ser transferido para outras pessoas <br>
              O servicos nao rembolsavel depois dos 7 dias da compra na podem ser transferidos, (sem exepcao)<br>
              Este voucher nao pode ser transferido para outras pessoas <br>
              O servicos nao rembolsavel depois dos 7 dias da compra na podem ser transferidos, (sem exepcao)<br>
            </div>
              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  <p class="lead">Metodos de Pagamento:</p>
                  <img src="../admin/dist/img/credit/visa.png" alt="Visa">
                  <img src="../admin/dist/img/credit/mastercard.png" alt="Mastercard">
                  <img src="../admin/dist/img/credit/american-express.png" alt="American Express">
                  <img src="../admin/dist/img/credit/paypal2.png" alt="Paypal">

                  <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                   Para sua segurança, caso haja qualquer divergência entre as informações cadastrais e de pagamento, nos reservamos o direito de não aprovar o seu pedido, ou, de entrar em contato para confirmar seus dados.
                  </p>
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <p class="lead">Data de Validade 2/22/2014</p>

                  <div class="table-responsive">
                    RESUMO
                    <table class="table">
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>$250.30</td>
                      </tr>
                      <tr>
                        <th>Impostos (9.3%)</th>
                        <td>$10.34</td>
                      </tr>
                      <tr>
                        /<th>Comissão/(nao mostrar para cliente final) :</th>
                        <td>$5.80</td>
                      </tr>
                      <tr>
                        <th>Total:</th>
                        <td>$265.24</td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <a href="voucherClienteFinal-print.html" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Imprimir</a>
                  <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Enviar cobrança
                  </button>
                  <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fas fa-download"></i> Generar PDF
                  </button>
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>


          <!-- /.card-body -->





          <div class="card-footer">

         <!-- <div align="center"> <button type="submit" id="uploadfiles" value="Crear servicio" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Continuar</button>   <a href="servicios.php" class="btn btn-danger" ><i class="fa fa-times" aria-hidden="true"></i> Salir sin guardar</a>

</div>-->











          </div>

        </div>

        <!-- /.card -->



      </div><!-- /.container-fluid -->

    </section>

        </div>

        <!-- /.row (main row) -->

      </div><!-- /.container-fluid -->

    </section>

    <!-- /.content -->

  </div>

  <!-- /.content-wrapper -->

  <?php 

  include("includes/footer.php"); ?>









