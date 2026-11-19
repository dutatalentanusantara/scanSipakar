<footer class="bg-blue">
        <div class="wrapper">
          <div class="footer">
            <div class="footer__col">
            <div class="footlogo">
    <?php
    $logo = portal_model()->viewOrderingLimit('logo','id_logo','DESC',0,1);
    foreach ($logo as $row) {
        echo "<img src='".base_url()."asset/logo/$row[gambar]' style='max-width: 280px; width: 100%; height: auto;' alt='Logo Siantar Barat' />";
        // Ganti nilai width sesuai dengan ukuran yang diinginkan
    }
    ?>
</div>

              <div class="footalamat">
                
              Jl. Bangau No.3, Sipinggol-Pinggol, Kec. Siantar Bar., Kota Pematang Siantar, Sumatera Utara 21114
              </div>
            </div>

            <div class="footer__col">
              <ul class="footmenu">
                <li><a href="<?= base_url(); ?>halaman/detail/kata-sambutan">Tentang Kami</a></li>
                <li><a href="<?= base_url(); ?>pelayanan">Pelayanan Administrasi Warga</a></li>
                <li><a href="<?= base_url(); ?>sipakar">SIPAKAR (Layanan ASN)</a></li>
                <li><a href="<?= base_url(); ?>hubungi" class="nav-link">Kontak & Lokasi</a></li>
                <li><a href="https://pematangsiantar.go.id/" class="nav-link" target="_blank" rel="noopener noreferrer">Website Resmi Kota Pematangsiantar</a></li>
              </ul>
            </div>

            <div class="footer__col">
              <ul class="sosmedrow">
                <li>
                  <a
                    href="https://www.facebook.com/p/Kec-Siantar-Barat-100017286675121/"
                    target="_blank"
                    ><i class="fab fa-facebook"></i> Facebook</a
                  >
                </li>
                <li>
                  <a
                    href="https://www.instagram.com/kec_siantarbarat_official/?__d=1"
                    target="_blank"
                    ><i class="fab fa-instagram"></i> Instagram</a
                  >
                </li>
                <!-- <li>
                  <a href="#" target="_blank"
                    ><img
                      width="20"
                      src="<?= base_url(); ?>asset/images/tiktok.png"
                      alt=""
                    />&nbsp;&nbsp; tiktok</a
                  >
                </li> -->
                <li>
                  <a
                    href="#"
                    target="_blank"
                    ><i class="fab fa-youtube"></i> youtube</a
                  >
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>