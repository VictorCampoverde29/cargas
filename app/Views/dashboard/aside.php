<?php
helper('sidebar');
$menuAgrupado = getMenuAgrupadoPorPerfil(session()->get('ca_idperfil'));
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url('dashboard') ?>" class="brand-link text-center">
    <img src="<?= base_url('public/dist/img/logogasiub.png') ?>" alt="Asiu Logo" width="120" >
  </a>

  <!-- Sidebar -->
  <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
    <!-- Sidebar user -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">     
      <div class="info">
        <a href="#" class="d-block"><?= esc(session()->get('ca_nombreusuariocorto')) ?></a>
        <span class="badge badge-warning">Perfil: <?= esc(session()->get('ca_perfil')) ?></span>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Buscar" aria-label="Buscar">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php
        foreach ($menuAgrupado as $modulo): ?>
          <li class="nav-item <?= count($modulo['submodulos']) > 0 ? 'has-treeview' : '' ?>">
            <a href="<?= !empty($modulo['ruta']) ? base_url($modulo['ruta']) : '#' ?>" class="nav-link">
              <i class="nav-icon <?= $modulo['icono'] ?>"></i>
              <p>
                <?= strtoupper($modulo['nombre']) ?>
                <?php if (count($modulo['submodulos']) > 0): ?>
                  <i class="right fas fa-angle-left"></i>
                <?php endif; ?>
              </p>
            </a>
            <?php if (count($modulo['submodulos']) > 0): ?>
              <ul class="nav nav-treeview">
                  <?php
                  $recorrer = function($submodulos) use (&$recorrer) {
                    foreach ($submodulos as $sub) {
                      $tieneHijos = !empty($sub['submodulos']);
                      ?>
                      <li class="nav-item <?= $tieneHijos ? 'has-treeview' : '' ?>">
                        <a href="<?= !empty($sub['ruta']) ? base_url($sub['ruta']) : '#' ?>" class="nav-link">
                          <i class="far fa-circle nav-icon <?= $sub['icono'] ?? '' ?>"></i>
                          <p><?= $sub['nombre'] ?></p>
                          <?php if ($tieneHijos): ?>
                            <i class="right fas fa-angle-left"></i>
                          <?php endif; ?>
                        </p>
                        </a>
                        <?php if ($tieneHijos): ?>
                          <ul class="nav nav-treeview">
                            <?php $recorrer($sub['submodulos']); ?>
                          </ul>
                        <?php endif; ?>
                      </li>
                      <?php
                    }
                  };
                  $recorrer($modulo['submodulos']);
                  ?>
              </ul>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>