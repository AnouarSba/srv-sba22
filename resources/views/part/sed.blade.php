<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/home') }}" class="brand-link elevation-4">
      <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ config('app.name', 'Laravel') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               @hasrole('super_admin')
               <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-users"></i>
                  <p>
                    Admin 
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
    
                  <li class="nav-item">
                    <a href="{{ url('/users') }}" class="nav-link">
                  
                      <i class="nav-icon fas fa-users"></i>
                      <p>
                        Users 
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </a>
                  </li>
    
                  <li class="nav-item">
                    <a href="{{ url('/roles') }}" class="nav-link">
                  
                      <i class="nav-icon fas fa-users"></i>
                      <p>
                        roles
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </a>
                  </li>
    
                  <li class="nav-item">
                    <a href="{{ url('/permissions') }}" class="nav-link">
                  
                      <i class="nav-icon fas fa-users"></i>
                      <p>
                        permissions
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </a>
                  </li>
  
                </ul>
               </li>
               @endhasrole
               <li class="nav-header"></li>
          @if(Gate::check('add_kabid') || Gate::check('add_controle') || Gate::check('add_vendeurs'))
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                ressources humaines
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('add_kabid')
              <li class="nav-item">
                <a href="{{ url('/kabid') }}" class="nav-link">
              
                  <i class="nav-icon fas fa-money-bill-alt" style='color: rgb(255, 145, 0)'></i>
                  <p>
                    liste des Receveurs 
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
              </li>
              @endcan
              @can('add_controle')
              <li class="nav-item">
                <a href="{{ url('/controle') }}" class="nav-link">
              
                  <i class="nav-icon fas fa-gamepad" style='color: rgba(217, 148, 159, 0.359)'></i>
                  <p>
                    liste des contrôleurs 
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
              </li>
              @endcan
              @can('add_vendeurs')
              <li class="nav-item">
                <a href="{{ url('/vendeurs') }}" class="nav-link">
              
                  <i class="nav-icon fas fa-shopping-cart" style='color: rgb(98, 109, 191)'></i>
                  <p>
                    liste des Vendeurs 
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          @endif

          @can('add_arrets')
          <li class="nav-item">
            <a href="{{ url('/arrets') }}" class="nav-link">
          
             <i class="nav-icon fas fa-map-marker" style='color: rgb(200, 255, 0)'></i>
              <p>
                liste des  Arrets 
                <i class="right fas fa-angle-right"></i>
              </p>
            </a>
          </li>
          @endcan
          @can('add_lignes')
          <li class="nav-item">
            <a href="{{ url('/lignes') }}" class="nav-link">
          
              <i class="nav-icon fas fa-chart-line" style='color: rgb(255, 247, 24)'></i>
              <p>
                Liste des Lignes 
                <i class="right fas fa-angle-right"></i>
              </p>
            </a>
          </li>
          @endcan
          @can('add_buses')
          <li class="nav-item">
            <a href="{{ url('/buses') }}" class="nav-link">
          
              <i class="nav-icon fas fa-bus" style='color: rgb(9, 250, 29)'></i>
              <p>
                liste des Buses 
                <i class="right fas fa-angle-right"></i>
              </p>
            </a>
          </li>
          @endcan
          @can('add_valideurs')
          <li class="nav-item">
            <a href="{{ url('/valideurs') }}" class="nav-link">
          
              <i class="nav-icon fas fa-check" style='color: rgb(0, 255, 187)'></i>
              <p>
                liste des Valideurs 
                <i class="right fas fa-angle-right"></i>
              </p>
            </a>
          </li>
          @endcan
          @can('add_vtickets')
          <li class="nav-item">
            <a href="{{ url('/vtickets') }}" class="nav-link">
          
              <i class="nav-icon fas fa-ticket-alt" style='color: rgb(0, 200, 255)'></i>
              <p>
                billets classiques
                <i class="right fas fa-angle-right"></i>
              </p>
            </a>
          </li>
          @endcan
          @can('add_pcarts')
          <li class="nav-item">
            <a href="{{ url('/pcarts') }}" class="nav-link">
          
              <i class="nav-icon fas fa-id-card" style='color: rgb(0, 149, 255)'></i>
              <p>
                Cartes d'abonnement 
                <i class="right fas fa-angle-right"></i>
              </p>
            </a>
          </li>
          @endcan
          @can('allcarts')
          <li class="nav-item">
            <a href="{{ url('/allcarts') }}" class="nav-link">
          
              <i class="nav-icon fas fa-id-card" style='color: rgb(217, 255, 0)'></i>
              <p>
                 Toutes les cartes  
                <i class="right fas fa-angle-right"></i>
              </p>
            </a>
          </li>
          @endcan
        
          @can('add_spcarts')
          <li class="nav-item">
            <a href="{{ url('/spcarts') }}" class="nav-link">
          
              <i class="nav-icon fas fa-id-card" style='color: rgb(0, 255, 85)'></i>
              <p>
                Cartes speciale 
                <i class="right fas fa-angle-right"></i>
              </p>
            </a>
          </li>
          @endcan
        
          @can('add_clients')
          <li class="nav-item">
            <a href="{{ url('/clients') }}" class="nav-link">
          
              <i class="nav-icon fa fa-mobile" style='color: rgb(234, 0, 255)'></i>
              <p>
                Utilisateurs de l'app
                <i class="right fas fa-angle-right"></i>
              </p>
            </a>
          </li>
          @endcan
          @can('add_soldecarts')
          <li class="nav-item">
            <a href="{{ url('/soldecarts') }}" class="nav-link">
          
              <i class="nav-icon fas fa-id-card" style='color: rgb(106, 0, 255)'></i>
              <p>
                Cartes de solde
                <i class="right fas fa-angle-right"></i>
              </p>
            </a>
          </li>
          @endcan
          @can('add_stats')
          <li class="nav-item">
            <a href="{{ url('/stats') }}" class="nav-link">
          
              <i class="nav-icon fas fa-chart-pie" style="color:rgb(68, 187, 29)"></i>
              <p>
                Comptabilité 
                <i class="right fas fa-angle-right"></i>
              </p>
            </a>
          </li>
          @endcan
          <li class="nav-header"></li>
          @can('add_payget')
          <li class="nav-item">
            <a href="{{ url('/payget') }}" class="nav-link">
          
              <i class="nav-icon fas fa-cog" style='color: rgb(255, 0, 128)'></i>
              <p>
                Passerelles de paiement
              </p>
            </a>
          </li>
          @endcan
          @can('add_server')
          <li class="nav-item">
            <a href="{{ url('/server') }}" class="nav-link">
          
              <i class="nav-icon fas fa-exchange-alt" style='color: rgb(204, 255, 0)'></i>
              <p>
                Interopérabilité
              </p>
            </a>
          </li>
          @endcan
         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>