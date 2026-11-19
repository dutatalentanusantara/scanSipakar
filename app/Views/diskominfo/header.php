<header class="mainmenu">
        <div class="wrapper">
          <nav class="menu">
            <div class="burger" aria-label="Menu Navigasi Mobile">
              <span></span>
            </div>
            <div class="mobile-menutitle">
              <div class="mobile-menutitle-inner">
                <a href="<?= base_url(); ?>" class="mobile-brand-link" style="display: flex; align-items: center; text-decoration: none;">
                  <?php
                  $logo = portal_model()->viewOrderingLimit('logo','id_logo','DESC',0,1);
                  foreach ($logo as $row) {
                      echo "<img src='".base_url()."asset/logo/$row[gambar]' alt='Logo Kecamatan Siantar Barat' class='header-mobile-logo' style='height: 38px; width: auto; max-width: 180px; object-fit: contain; display: block;' />";
                  }
                  ?>
                </a>
              </div>
            </div>

            <div class="menu__outer">
              <div class="menu__wrap">
                <div class="menu__left">
                  <div class="menu__utama">
                  <ul>
                      <?php
                      $menu_rows = portal_model()->viewWhereOrdering('menu', array('position' => 'Bottom', 'aktif' => 'Ya'), 'urutan', 'ASC');
                      $top_level_menus = array();
                      $submenu_by_parent = array();
                      foreach ($menu_rows as $mrow) {
                          $pid = (int) $mrow['id_parent'];
                          if ($pid === 0) {
                              $top_level_menus[] = $mrow;
                          } else {
                              $submenu_by_parent[$pid][] = $mrow;
                          }
                      }
                      $current_uri = uri_string();
                      $rendered_menu_keys = array();
                      foreach ($top_level_menus as $row):
                          $menu_key = strtolower(trim($row['nama_menu']));
                          if (in_array($menu_key, $rendered_menu_keys, true)) {
                              continue; // Cegah duplikasi menu
                          }
                          $rendered_menu_keys[] = $menu_key;
                          $child_menus = isset($submenu_by_parent[(int) $row['id_menu']]) ? $submenu_by_parent[(int) $row['id_menu']] : array();
                          $dropdown = count($child_menus);
                          $is_active = false;
                          if ($row['nama_menu'] == 'Beranda') {
                              $is_active = ($current_uri == '' || $current_uri == 'main');
                          } elseif (!empty($row['link']) && strpos($current_uri, trim($row['link'], '/')) === 0) {
                              $is_active = true;
                          }
                          $active_cls = $is_active ? 'active' : '';

                          if ($row['nama_menu'] == 'Beranda'): ?>
                              <li class="nav-item <?= $active_cls; ?>">
                                  <a href="<?= base_url() . $row['link']; ?>" class="nav-link smuroll">
                                      <i class="fas fa-home" style="margin-right: 5px;"></i>Beranda
                                  </a>
                              </li>
                          <?php elseif ($dropdown == 0): ?>
                              <li class="<?= $active_cls; ?>">
                                  <span>
                                      <a href="<?= base_url() . $row['link']; ?>" class="nav-link">
                                          <?= $row['nama_menu']; ?>
                                      </a>
                                  </span>
                              </li>
                          <?php else: ?>
                              <li class="has-dropdown <?= $active_cls; ?>">
                                  <span>
                                      <a href="<?= base_url() . $row['link']; ?>" class="nav-link dropdown-toggle" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                                          <span><?= esc($row['nama_menu']); ?></span>
                                          <i class="fas fa-chevron-down mobile-caret" aria-hidden="true"></i>
                                      </a>
                                  </span>
                                  <ul>
                                      <?php foreach ($child_menus as $subrow): ?>
                                          <li>
                                              <a href="<?= base_url() . $subrow['link']; ?>" class="dropdown-item">
                                                  <i class="fas fa-chevron-right mobile-sub-icon" aria-hidden="true"></i>
                                                  <span><?= esc($subrow['nama_menu']); ?></span>
                                              </a>
                                          </li>
                                      <?php endforeach; ?>
                                  </ul>
                              </li>
                          <?php endif; ?>
                      <?php endforeach; ?>
                  </ul>

                  </div>
                </div>
              </div>
            </div>

            <div class="menu__right">
              <div class="hujanbahasa">
                <!-- <div class="hujan">21&#176;C Berawan</div> -->
                <div class="bn-label" id="dynamic-day">
                  <?php
                  $hari_list = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
                  $bulan_list = array(1=>'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                  echo $hari_list[(int)date('w')] . ', ' . date('j') . ' ' . $bulan_list[(int)date('n')] . ' ' . date('Y') . '<br>' . date('H:i:s') . ' WIB';
                  ?>
                </div>

                <!-- <div class="bahasa">
                  <a href="<?= base_url(); ?>template/<?= template(); ?>/index9bb4.html?lang=id">ID</a> |
                  <a href="<?= base_url(); ?>template/<?= template(); ?>/index9ed2.html?lang=en">EN</a>
                </div> -->
              </div>
              <!-- <div class="search">
                <div class="search__icon">
                  <i class="scari-icon fas fa-search"></i>
                  <i class="sclose-icon fas fa-times"></i>
                </div>
              </div> -->
            </div>
            <form action="#" method="get">
              <div class="search__form-outer">
                <div class="search__form">
                  <input type="text" name="key" />
                  <button type="submit"><i class="fas fa-search"></i></button>
                </div>
              </div>
            </form>
          </nav>
        </div>

        
      </header>