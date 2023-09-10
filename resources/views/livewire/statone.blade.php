<div>
    <div class="row">
        <div class="col-12 col-sm-4 col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-gamepad"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Controllers</span>
              <span class="info-box-number">
                {{$contr}}
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-4 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-money-bill-alt" ></i>
            </span>

            <div class="info-box-content">
              <span class="info-box-text">Receveurs</span>
              <span class="info-box-number">{{$rece}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-4 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Vendeurs</span>
              <span class="info-box-number">{{$vend}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-4 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1" ><i class="fas fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Utilisateurs d'applications</span>
              <span class="info-box-number">{{$client}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-4 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon   elevation-1" style="background-color: rgb(13, 113, 80)"><i class="fas fa-id-card" style="color: azure"></i></span>
  
              <div class="info-box-content">
                <span class="info-box-text">Nombre de cartes vendues</span>
                <span class="info-box-number">{{$cart}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
                  <!-- /.col -->
        <div class="col-12 col-sm-4 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon elevation-1" style="background-color: blueviolet"><i class="fas fa-qrcode" style="color: azure"></i></span>
  
              <div class="info-box-content">
                <span class="info-box-text">Cartes de recharge vendues</span>
                <span class="info-box-number">{{$scart}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <div class="col-12 col-sm-4 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon elevation-1" style="background-color: rgb(250, 89, 242)"><i class="fa fa-credit-card" style="color: azure"></i></span>
  
              <div class="info-box-content">
                <span class="info-box-text">E-payment </span>
                <span class="info-box-number">{{$epay}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
      </div>
</div>
