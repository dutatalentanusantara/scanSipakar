<!-- Left navbar links -->
<ul class="navbar-nav">
    <li class="nav-item">
    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item">
    <a class="nav-link" target='_BLANK' href="<?php echo base_url(); ?>"> Kunjungi Situs</a>
    </li>
</ul>


<!-- Right navbar links -->
<ul class="navbar-nav ml-auto">
    <!-- Messages Dropdown Menu -->
    <?php if (session()->get('level')=='admin'){ ?>
    <li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-comments"></i>
        <?php 
            $jumlah_pesan = count(portal_model()->viewWhere('hubungi', array('dibaca'=>'N'))); 
            echo "<span class='badge badge-danger navbar-badge'>$jumlah_pesan</span>";
        ?>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <?php 
        $pesan = portal_model()->viewOrderingLimit('hubungi','id_hubungi','DESC',0,5);
        foreach ($pesan as $row) {
            $isi_pesan = esc(substr((string) ($row['pesan'] ?? ''),0,20));
            $namaSender = esc((string) ($row['nama'] ?? ''));
            $waktukirim = cek_terakhir(($row['tanggal'] ?? '').' '.($row['jam'] ?? ''));
            if ($row['dibaca']=='N'){ $color = 'warning'; }else{ $color = 'muted'; }
            echo "<a class='dropdown-item' href='".base_url().uri_segment(1, 'administrator')."/detail_pesanmasuk/$row[id_hubungi]'>
                    <div class='media'>
                        <img src='".base_url()."asset/foto_user/blank.png'  class='img-size-50 mr-3 img-circle' alt='User Image'>
                        <div class='media-body'>
                            <h3 class='dropdown-item-title'>
                                $namaSender
                                <span class='float-right text-sm text-$color'><i class='fas fa-star'></i></span> 
                            </h3>
                            <p class='text-sm'>$isi_pesan...</p>
                            <p class='text-sm text-muted'><i class='far fa-clock mr-1'></i> $waktukirim</p>
                        </div>
                    </div>
                    </a>
                    <div class='dropdown-divider'></div>";
        }
        ?>
        <a href="<?php echo base_url().uri_segment(1, 'administrator'); ?>/pesanmasuk" class="dropdown-item dropdown-footer">See All Messages</a>
    </div>
    </li>
    <?php } ?>
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item">
    <a class="nav-link" href="<?php echo base_url().uri_segment(1, 'administrator'); ?>/edit_manajemenuser/<?php echo session()->get('username'); ?>"><i class="nav-icon fas fa-user-edit"></i></a></li>
    <li class="nav-item">
    <a class="nav-link" data-widget="control-sidebar" data-slide="true" target='_BLANK' href="<?php echo base_url(); ?>"><i
        class="fas fa-cog"></i></a>
    </li>
</ul>
