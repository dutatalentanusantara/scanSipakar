<script>
document.addEventListener('DOMContentLoaded', function() {
    var statsUrl = '<?= base_url('analytics/visitor_stats'); ?>';
    function updateVisitorStatistics() {
        fetch(statsUrl, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
            .then(function(res) { return res.ok ? res.json() : null; })
            .then(function(data) {
                if (!data) return;
                var map = {
                    todayVisitors: data.today,
                    yesterdayVisitors: data.yesterday,
                    thisMonthVisitors: data.this_month,
                    thisYearVisitors: data.this_year,
                    totalVisitors: data.total
                };
                Object.keys(map).forEach(function(id) {
                    var el = document.getElementById(id);
                    if (el && map[id] !== undefined) el.textContent = map[id];
                });
            })
            .catch(function() {});
    }
    setInterval(updateVisitorStatistics, 10000);
});
</script>

 
 <!-- Kolom kiri (Widget) -->

 <div
                  class="news3col__col"
                  style="flex: 1; order: 2; width: 100%"
                >
               
<!-- Widget Menu Navigasi Modern (2-Column Pill Grid) -->
<style>
.menu-navigasi-box {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 24px 18px 20px;
  margin-bottom: 25px;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
}
.menu-navigasi-title {
  font-size: 24px;
  font-weight: 700;
  text-align: center;
  color: #1e293b;
  margin: 0 0 20px 0;
  letter-spacing: -0.3px;
}
.menu-navigasi-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px 14px;
  list-style: none;
  padding: 0;
  margin: 0;
}
.menu-navigasi-pill {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f1f3f5;
  color: #007aff !important;
  font-size: 14.5px;
  font-weight: 600;
  text-decoration: none !important;
  border-radius: 22px;
  padding: 11px 16px;
  text-align: center;
  line-height: 1.25;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
}
.menu-navigasi-pill:hover {
  background-color: #007aff;
  color: #ffffff !important;
  transform: translateY(-2px);
  box-shadow: 0 6px 14px rgba(0, 122, 255, 0.25);
}
@media (max-width: 480px) {
  .menu-navigasi-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<div class="menu-navigasi-box">
  <h2 class="menu-navigasi-title">Menu Navigasi</h2>
  <div class="menu-navigasi-grid">
    <a href="<?= base_url('pelayanan'); ?>" class="menu-navigasi-pill" style="background: #ecfdf5; color: #047857 !important; font-weight: 700; border: 1px solid #a7f3d0;"><i class="fas fa-landmark mr-1"></i> Pelayanan Warga</a>
    <a href="<?= base_url('sipakar'); ?>" class="menu-navigasi-pill" style="background: #eff6ff; color: #1d4ed8 !important; font-weight: 700; border: 1px solid #bfdbfe;"><i class="fas fa-id-card-alt mr-1"></i> SIPAKAR ASN</a>
    <a href="<?= base_url('halaman/detail/kata-sambutan'); ?>" class="menu-navigasi-pill">Kata Sambutan</a>
    <a href="<?= base_url('halaman/detail/struktur-organisasi'); ?>" class="menu-navigasi-pill">Struktur Organisasi</a>
    <a href="<?= base_url('halaman/detail/tugas-dan-fungsi'); ?>" class="menu-navigasi-pill">Tugas dan Fungsi</a>
    <a href="<?= base_url('halaman/detail/visi-misi'); ?>" class="menu-navigasi-pill">Visi Misi</a>
    <a href="<?= base_url('kategori/detail/berita-terbaru'); ?>" class="menu-navigasi-pill">Berita Terbaru</a>
    <a href="<?= base_url('kategori/detail/pengumuman'); ?>" class="menu-navigasi-pill">Pengumuman</a>
  </div>
</div>


<br>
                  
                 <!-- Daftar Pengunjung -->
<!-- <div
  class="visitor-card"
  id="visitorCard"
  style="
    display: flex;
    flex-direction: column;
    align-items: stretch;
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border: 2px solid #007bff;
  "
> -->
  <!-- Judul Card -->
  <!-- <h2
    style="
      font-size: 24px;
      color: #333;
      margin: 0 0 20px 0;
      text-align: center;
    "
  >
    Pengunjung Website
  </h2> -->
  <!-- Statistik Pengunjung -->
  <!-- <div class="visitor-stat" style="margin-bottom: 15px">
    <h3 style="font-size: 14px; color: #333; margin: 0">
      Hari Ini:
      <span id="todayVisitors" style="font-weight: bold; color: #007bff; font-size: 14px;">
        <?php echo (int) ($visitor_statistics['today'] ?? 0); ?>
      </span>
      Pengunjung
    </h3>
  </div>
  <div class="visitor-stat" style="margin-bottom: 15px">
    <h3 style="font-size: 14px; color: #333; margin: 0">
      Kemarin:
      <span id="yesterdayVisitors" style="font-weight: bold; color: #007bff; font-size: 14px;">
        <?php echo (int) ($visitor_statistics['yesterday'] ?? 0); ?>
      </span>
      Pengunjung
    </h3>
  </div>
  <div class="visitor-stat" style="margin-bottom: 15px">
    <h3 style="font-size: 14px; color: #333; margin: 0">
      Bulan Ini:
      <span id="thisMonthVisitors" style="font-weight: bold; color: #007bff; font-size: 14px;">
        <?php echo (int) ($visitor_statistics['this_month'] ?? 0); ?>
      </span>
      Pengunjung
    </h3>
  </div>
  <div class="visitor-stat" style="margin-bottom: 15px">
    <h3 style="font-size: 14px; color: #333; margin: 0">
      Tahun Ini:
      <span id="thisYearVisitors" style="font-weight: bold; color: #007bff; font-size: 14px;">
        <?php echo (int) ($visitor_statistics['this_year'] ?? 0); ?>
      </span>
      Pengunjung
    </h3>
  </div> -->
  <!-- Total Pengunjung -->
  <!-- <div
    class="visitor-total"
    style="
      margin-top: auto;
      border-top: 1px solid #ddd;
      padding-top: 20px;
    "
  >
    <h3 style="font-size: 16px; color: #333; margin: 0">
      Total Pengunjung:
      <span id="totalVisitors" style="font-weight: bold; color: #28a745; font-size: 16px;">
        <?php echo (int) ($visitor_statistics['total'] ?? 0); ?>
      </span>
    </h3>
  </div> -->
<!-- </div> -->

                  <!-- Quisioner Website -->
                  <br />
                  <!-- Kuesioner -->
                  <!-- Kuesioner -->
                  <!-- <div
                    class="poll-card"
                    id="pollCard"
                    style="
                      display: flex;
                      flex-direction: column;
                      align-items: stretch;
                      background-color: #f9f9f9;
                      padding: 20px;
                      border-radius: 10px;
                      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                      border: 2px solid #007bff;
                    "
                  > -->
                    <!-- Judul Card -->
                    <!-- <h2
                      style="
                        font-size: 20px;
                        color: #333;
                        margin: 0 0 20px 0;
                        text-align: center;
                      "
                    >
                      Apakah Website ini bermanfaat?
                    </h2> -->
                    <!-- Opsi Polling -->
                    <!-- <div class="poll-options" style="margin-bottom: 15px">
                      <label
                        style="font-size: 14px; color: #333; margin-bottom: 5px"
                      >
                        <input
                          type="radio"
                          name="poll-option"
                          value="sangat-bermanfaat"
                          style="margin-right: 5px"
                        />Sangat Bermanfaat
                      </label>
                      <label
                        style="font-size: 14px; color: #333; margin-bottom: 5px"
                      >
                        <input
                          type="radio"
                          name="poll-option"
                          value="cukup-bermanfaat"
                          style="margin-right: 5px"
                        />Cukup Bermanfaat
                      </label>
                      <label
                        style="font-size: 14px; color: #333; margin-bottom: 5px"
                      >
                        <input
                          type="radio"
                          name="poll-option"
                          value="bermanfaat"
                          style="margin-right: 5px"
                        />Bermanfaat
                      </label>
                      <label
                        style="font-size: 14px; color: #333; margin-bottom: 5px"
                      >
                        <input
                          type="radio"
                          name="poll-option"
                          value="kurang-bermanfaat"
                          style="margin-right: 5px"
                        />Kurang Bermanfaat
                      </label>
                    </div> -->
                    <!-- Tombol Kirim Polling -->
                    <!-- <button
                      onclick="submitPoll()"
                      style="
                        font-size: 14px;
                        background-color: #007bff;
                        color: #fff;
                        border: none;
                        border-radius: 5px;
                        padding: 8px 16px;
                        cursor: pointer;
                      "
                    >
                      Kirim Polling
                    </button> -->
                    <!-- Hasil Polling -->
                    <!-- <div
                      class="poll-results"
                      style="
                        margin-top: 20px;
                        border-top: 1px solid #ddd;
                        padding-top: 20px;
                      "
                    >
                      <h3
                        style="
                          font-size: 16px;
                          color: #333;
                          margin-bottom: 10px;
                        "
                      >
                        Hasil Polling
                      </h3>
                      <div
                        class="poll-result-item"
                        style="font-size: 14px; color: #333; margin-bottom: 5px"
                      >
                        Sangat Bermanfaat
                        <div
                          class="poll-bar"
                          style="
                            background-color: #007bff;
                            height: 15px;
                            margin-top: 5px;
                            width: 50%;
                          "
                        ></div>
                        <span style="font-weight: bold">50.00%</span>
                      </div>
                      <div
                        class="poll-result-item"
                        style="font-size: 14px; color: #333; margin-bottom: 5px"
                      >
                        Cukup Bermanfaat
                        <div
                          class="poll-bar"
                          style="
                            background-color: #007bff;
                            height: 15px;
                            margin-top: 5px;
                            width: 50%;
                          "
                        ></div>
                        <span style="font-weight: bold">50.00%</span>
                      </div>
                      <div
                        class="poll-result-item"
                        style="font-size: 14px; color: #333; margin-bottom: 5px"
                      >
                        Bermanfaat
                        <div
                          class="poll-bar"
                          style="
                            background-color: #007bff;
                            height: 15px;
                            margin-top: 5px;
                            width: 0%;
                          "
                        ></div>
                        <span style="font-weight: bold">0.00%</span>
                      </div>
                      <div
                        class="poll-result-item"
                        style="font-size: 14px; color: #333; margin-bottom: 5px"
                      >
                        Kurang Bermanfaat
                        <div
                          class="poll-bar"
                          style="
                            background-color: #007bff;
                            height: 15px;
                            margin-top: 5px;
                            width: 0%;
                          "
                        ></div>
                        <span style="font-weight: bold">0.00%</span>
                      </div>
                    </div>
                  </div> -->

                  <!-- Widget GPR Diskominfo -->
                  <br />
                  <div
                          id="gpr-kominfo-widget-container"
                          style="margin: auto"
                        ></div>

                </div>