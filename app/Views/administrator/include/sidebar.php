<!-- Brand Logo -->
<a href="<?php echo base_url().uri_segment(1, 'administrator'); ?>" class="brand-link navbar-light">
      <img src="<?php echo base_url(); ?>/asset/logo/admin.png" alt="Admin Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light" style='color:#000'>ADMIN WEB OPD</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <?php 
          $usr = portal_model()->findOne('users', array('username'=> (string) session()->get('username'))) ?? array('foto' => '', 'nama_lengkap' => 'Admin');
          if (trim($usr['foto'])==''){ $foto = 'blank.png'; }else{ $foto = $usr['foto']; } 
          echo "<div class='image'>
                  <img src='".base_url()."/asset/foto_user/$foto' class='img-circle elevation-2' alt='$usr[nama_lengkap]'>
                </div>
                <div class='info'>
                  <a href='#' class='d-block'>$usr[nama_lengkap]<br> <span class='badge badge-success'>Online</span></a>
                </div>";
        ?>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat menu-open nav-legacy nav-compact" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header">MENU UTAMA</li>
          <li class="nav-item"><a class="nav-link" href="<?php echo base_url().uri_segment(1, 'administrator'); ?>/home"><i class="nav-icon fas fa-tachometer-alt"></i> <p>Dashboard <span class="right badge badge-danger">Welcome</span></p></a></li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>Menu Utama <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <?php
              $cek=portal_model()->umenuAkses("identitaswebsite",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/identitaswebsite'><i class='far fa-circle nav-icon text-success'></i> <p>Identitas Website</p></a></li>";
              }

              // $cek=portal_model()->umenuAkses("menuwebsite",(string) session()->get('id_session'));
              // if($cek==1 OR session()->get('level')=='admin'){
              //   echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/menuwebsite'><i class='far fa-circle nav-icon text-success'></i> <p>Menu Website</p></a></li>";
              // }

              $cek=portal_model()->umenuAkses("halamanbaru",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/halamanbaru'><i class='far fa-circle nav-icon text-success'></i> <p>Halaman Baru</p></a></li>";
              }
            ?>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>Modul Berita <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <?php
              $cek=portal_model()->umenuAkses("listberita",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/listberita'><i class='far fa-circle nav-icon text-success'></i> <p>Berita</p></a></li>";
              }

              $cek=portal_model()->umenuAkses("kategoriberita",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/kategoriberita'><i class='far fa-circle nav-icon text-success'></i> <p>Kategori Berita</p></a></li>";
              }

              $cek=portal_model()->umenuAkses("tagberita",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/tagberita'><i class='far fa-circle nav-icon text-success'></i> <p>Tag Berita</p></a></li>";
              }

              $cek=portal_model()->umenuAkses("komentarberita",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/komentarberita'><i class='far fa-circle nav-icon text-success'></i> <p>Komentar Berita</p></a></li>";
              }

              $cek=portal_model()->umenuAkses("sensorkomentar",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/sensorkomentar'><i class='far fa-circle nav-icon text-success'></i> <p>Sensor Komentar</p></a></li>";
              }

              $cek=portal_model()->umenuAkses("album",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/album'><i class='far fa-circle nav-icon text-success'></i> <p>Album Berita Foto</p></a></li>";
              }

              $cek=portal_model()->umenuAkses("gallery",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/gallery'><i class='far fa-circle nav-icon text-success'></i> <p>Gallery Berita Foto</p></a></li>";
              }
            ?>
            </ul>
          </li>
          
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-play-circle"></i>
            <p>Modul Video <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <?php
              $cek=portal_model()->umenuAkses("playlist",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/playlist'><i class='far fa-circle nav-icon text-success'></i> <p>Playlist Video</p></a></li>";
              }

              $cek=portal_model()->umenuAkses("video",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/video'><i class='far fa-circle nav-icon text-success'></i> <p>Video</p></a></li>";
              }

              $cek=portal_model()->umenuAkses("tagvideo",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/tagvideo'><i class='far fa-circle nav-icon text-success'></i> <p>Tag Video</p></a></li>";
              }

              $cek=portal_model()->umenuAkses("komentarvideo",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/komentarvideo'><i class='far fa-circle nav-icon text-success'></i> <p>Komentar Video</p></a></li>";
              }
            ?>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chalkboard"></i>
            <p>Modul Iklan <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <?php
              $cek=portal_model()->umenuAkses("iklanatas",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/iklanatas'><i class='far fa-circle nav-icon text-success'></i> <p>Iklan Atas</p></a></li>";
              }

              $cek=portal_model()->umenuAkses("iklanhome",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/iklanhome'><i class='far fa-circle nav-icon text-success'></i> <p>Iklan Home</p></a></li>";
              }

              $cek=portal_model()->umenuAkses("iklansidebar",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/iklansidebar'><i class='far fa-circle nav-icon text-success'></i> <p>Iklan Sidebar</p></a></li>";
              }

              $cek=portal_model()->umenuAkses("banner",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/banner'><i class='far fa-circle nav-icon text-success'></i> <p>Iklan Link</p></a></li>";
              }
            ?>
            </ul>
          </li>

         
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-sitemap"></i>
            <p>Modul Kegiatan & Arsip <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <?php
              $cek=portal_model()->umenuAkses("agenda",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/agenda'><i class='far fa-circle nav-icon text-success'></i> <p>Kegiatan</p></a></li>";
              }

              // $cek=portal_model()->umenuAkses("sekilasinfo",(string) session()->get('id_session'));
              // if($cek==1 OR session()->get('level')=='admin'){
              //   echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/sekilasinfo'><i class='far fa-circle nav-icon text-success'></i> <p>Sekilas Info</p></a></li>";
              // }

              // $cek=portal_model()->umenuAkses("jajakpendapat",(string) session()->get('id_session'));
              // if($cek==1 OR session()->get('level')=='admin'){
              //   echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/jajakpendapat'><i class='far fa-circle nav-icon text-success'></i> <p>Jajak Pendapat</p></a></li>";
              // }

              $cek=portal_model()->umenuAkses("download",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/download'><i class='far fa-circle nav-icon text-success'></i> <p>Dokumen</p></a></li>";
              }

              // $cek=portal_model()->umenuAkses("alamat",(string) session()->get('id_session'));
              // if($cek==1 OR session()->get('level')=='admin'){
              //   echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/alamat'><i class='far fa-circle nav-icon text-success'></i> <p>Alamat Kontak</p></a></li>";
              // }

              // $cek=portal_model()->umenuAkses("pesanmasuk",(string) session()->get('id_session'));
              // if($cek==1 OR session()->get('level')=='admin'){
              //   echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/pesanmasuk'><i class='far fa-circle nav-icon text-success'></i> <p>Pesan Masuk</p></a></li>";
              // }
            ?>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cog"></i>
            <p>Modul Web <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <?php
              $cek=portal_model()->umenuAkses("logowebsite",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/logowebsite'><i class='far fa-circle nav-icon text-success'></i> <p>Logo Website</p></a></li>";
              }

              // $cek=portal_model()->umenuAkses("templatewebsite",(string) session()->get('id_session'));
              // if($cek==1 OR session()->get('level')=='admin'){
              //   echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/templatewebsite'><i class='far fa-circle nav-icon text-success'></i> <p>Template Website</p></a></li>";
              // }

              // $cek=portal_model()->umenuAkses("background",(string) session()->get('id_session'));
              // if($cek==1 OR session()->get('level')=='admin'){
              //   echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/background'><i class='far fa-circle nav-icon text-success'></i> <p>Background Website</p></a></li>";
              // }
            ?>
            </ul>
          </li>

          
          <li class="nav-header">MENU KELUAR</li>
          <!-- <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>Modul Users <i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <?php
              $cek=portal_model()->umenuAkses("manajemenuser",(string) session()->get('id_session'));
              if($cek==1 OR session()->get('level')=='admin'){
                echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/manajemenuser'><i class='far fa-circle nav-icon text-success'></i> <p>Manajemen User</p></a></li>";
              }

              // $cek=portal_model()->umenuAkses("manajemenmodul",(string) session()->get('id_session'));
              // if($cek==1 OR session()->get('level')=='admin'){
              //   echo "<li class='nav-item'><a class='nav-link' href='".base_url().uri_segment(1, 'administrator')."/manajemenmodul'><i class='far fa-circle nav-icon text-success'></i> <p>Manajemen Modul</p></a></li>";
              // }
            ?>
            </ul>
          </li> -->
          <li class="nav-item"><a class="nav-link" href="<?php echo base_url().uri_segment(1, 'administrator'); ?>/logout"><i class="nav-icon fas fa-sign-out-alt"></i> <p>Logout</p></a></li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->