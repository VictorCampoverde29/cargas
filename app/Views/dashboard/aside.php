<?php
$xmlPath = APPPATH . 'Views/dashboard/opciones.xml';
$menu = simplexml_load_file($xmlPath);
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url() ?>" class="brand-link text-center">
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
        <?php foreach ($menu->modulo as $modulo): ?>
          <li class="nav-item <?= count($modulo->children()) > 0 ? 'has-treeview' : '' ?>">
            <a href="#" class="nav-link">
              <i class="nav-icon <?= $modulo['icono'] ?>"></i>
              <p>
                <?= strtoupper($modulo['nombre']) ?>
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php foreach ($modulo->children() as $child): ?>
                <?php if ($child->getName() === 'item'): ?>
                  <li class="nav-item">
                    <a href="<?= base_url((string)$child['ruta']) ?>" class="nav-link">
                      <i class="far fa-circle nav-icon <?= $child['icono'] ?>"></i>
                      <p><?= $child['nombre'] ?></p>
                    </a>
                  </li>
                <?php elseif ($child->getName() === 'submodulo'): ?>
                  <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                      <i class="nav-icon <?= $child['icono'] ?>"></i>
                      <p>
                        <?= $child['nombre'] ?>
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <?php foreach ($child->item as $subitem): ?>
                        <li class="nav-item">
                          <a href="<?= base_url((string)$subitem['ruta']) ?>" class="nav-link">
                            <i class="far fa-dot-circle nav-icon <?= $subitem['icono'] ?>"></i>
                            <p><?= $subitem['nombre'] ?></p>
                          </a>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>