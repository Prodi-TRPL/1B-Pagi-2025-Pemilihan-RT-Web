<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
// Cek apakah user memiliki session login
if (!isset($_SESSION["login"])) {
  header("Location: /admin");
  exit;
}
$title_web = "Sesi Pemilihan";
$pesan = "";

if (isset($_GET['id_sesi'])) {

  // Ambil nilai ID yang baru saja diketik user
  $id_param_saved = htmlspecialchars($_GET['id_sesi']);
  $rt_param_saved = htmlspecialchars($_GET['rt']);
  $query = "SELECT * FROM sesi_pemilihan where rt_panitia = ? AND id_sesi = ? ORDER BY id_sesi ASC";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("si", $rt_param_saved, $id_param_saved);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($_SESSION['rt_panitia'] != "RW") {
    if ($_SESSION['rt_panitia'] != $rt_param_saved) {
      header("Location: ../rt/index.php?rt=" . $_SESSION['saved_rt_panitia_param'] . "&id_sesi=" . $_SESSION['saved_id_sesi_param']);
      exit;
    }
  }
  if (mysqli_num_rows($result) == 0) {
    header("Location: ../rt/index.php?rt=" . $_SESSION['saved_rt_panitia_param'] . "&id_sesi=" . $_SESSION['saved_id_sesi_param']);
    exit;
  }

  // 2. Simpan ID yang SEKARANG ke dalam session untuk digunakan nanti
  $_SESSION['saved_rt_panitia_param'] = $rt_param_saved;
  $_SESSION['saved_id_sesi_param'] = $id_param_saved;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title><?php echo $title_web ?></title>
  <link rel="icon" type="image/x-icon" href="/assets/img/logo-2.ico">
  <link rel="stylesheet" href="/assets/css/data/style.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>

<body>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/header.php'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/sidebar.php'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/navbar.php'; ?>

  <div class="menu-container">
    <div class="tab-header">
      <button class="tab-btn active" data-tab="data">Data</button>
      <button class="tab-btn" data-tab="waktu">Waktu Nyata</button>
      <button class="tab-btn" data-tab="hasilkan">Hasilkan</button>
      <div class="tab-underline"></div>
    </div>
  </div>
  <div class="tab-pane active" id="data">
    <div class="box-container">
      <div class="box">
        <div class="collapsible-section">
          <div class="section-header" onclick="toggleSection(this)">
            <span class="arrow">▶</span>
            <span class="title">Data Warga</span>
          </div>
          <div class="section-content">
            <div class="form-row">
              <label>
                <h2>List Data Warga</h2>
              </label>
            </div>
            <div class="button-group">
              <button class="addDataWarga" id="addDataWarga">Tambah Data Warga</button>
            </div>
            <div class="form-row">
              <div class="table-container">
                <table id="wargaTable">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>Kode Unik</th>
                      <th>Status</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = "SELECT * FROM warga where rt_panitia = ? AND id_sesi = ? ORDER BY id_warga ASC";
                    $list_nama_warga = [];
                    $list_status_warga = [];
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("si", $_GET['rt'], $_GET['id_sesi']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $no = 0;
                    if (mysqli_num_rows($result) > 0) {
                      // 4. Looping: Mengulang kode HTML sebanyak jumlah baris data (i kali)
                      while ($row = mysqli_fetch_assoc($result)) {
                        $list_nama_warga[] = $row["nama_warga"];
                        $list_status_warga[] = $row["status"];
                        $no++;
                        // Menampung data database ke variabel agar mudah dibaca

                        $nama = $row['nama_warga'];
                        $token = $row['token'];
                        $status = $row['status'];

                        // Tentukan warna tombol status (Opsional: Agar tampilan lebih bagus)
                        // Jika status 'Sudah Memilih' tombol jadi hijau, jika belum jadi kuning/merah
                        $class_status = ($status == 'Sudah Memilih') ? 'status-selesai' : 'status-belum';
                    ?>

                        <tr>
                          <td><?php echo htmlspecialchars($no, ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><?php echo htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?></td>
                          <td>
                            <button class="status-btn <?php echo htmlspecialchars($class_status, ENT_QUOTES, 'UTF-8'); ?>">
                              <?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>
                            </button>
                          </td>
                          <td class="aksi-icons">
                            <i class="fa fa-edit editBtn" title="Edit" name="id_warga" data-value=<?php echo htmlspecialchars($no, ENT_QUOTES, 'UTF-8'); ?>></i>
                            <i class="fa fa-trash delBtn" title="Hapus" data-value=<?php echo htmlspecialchars($no, ENT_QUOTES, 'UTF-8'); ?>></i>
                          </td>
                        </tr>

                    <?php
                      } // Akhir dari loop while
                    } else {
                      // Jika data kosong (0 baris)
                      echo "<tr><td colspan='5' class='text-center'>Belum ada data warga.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="form-row">
              <h2>Upload Data Warga</h2>
            </div>
            <div class="form-row">
              <div class="upload-section">
                <div class="upload-header">
                  <i class="fa fa-upload icon-fallback"></i>
                  <span>Files</span>
                </div>
                <div class="upload-area" id="dropZone">
                  <div class="upload-icon">
                    <i class="fa fa-file icon-fallback"></i><i class="fa fa-plus icon-fallback"></i>
                  </div>
                  <div class="upload-text">Tarik atau tambahkan file disini</div>
                </div>
                <div class="file-info">Jenis file yang diterima: .xlsx, .csv</div>
              </div>
            </div>
          </div>
        </div>
        <div class="collapsible-section">
          <div class="section-header" onclick="toggleSection(this)">
            <span class="arrow">▶</span>
            <span class="title">Data Kandidat</span>
          </div>
          <div class="section-content">
            <div class="form-row">
              <div id="candidatesContainer" class="candidates-container" data-value="<?php echo $_GET['id_sesi'] ?> " data-value2="<?php echo $_GET['rt'] ?>">
                <div class="candidate-box">
                  <?php
                  session_start();
                  if (!isset($_SESSION["login"])) {
                    header("Location: /admin");
                    exit;
                  }
                  $conn = mysqli_connect("localhost", "klikpilih", "PBLkel3~!@#$%%$#@!~", "klikpilih");
                  $id_sesi = $_GET['id_sesi'];
                  $result = $conn->query("SELECT * FROM kandidat where id_sesi = $id_sesi;");
                  $nama_kandidat = [];
                  $visi_kandidat = [];
                  $misi_kandidat = [];
                  while ($row = $result->fetch_assoc()) {
                    $nama_kandidat[] = $row["nama_kandidat"];
                    $visi_kandidat[] = $row["visi"];
                    $misi_kandidat[] = $row["misi"];
                    $foto_kandidat[] = $row["foto_kandidat"];
                  }
                  ?>
                  <h3>Kandidat 1</h3>

                  <div class="input-group">
                    <label for="nama_kandidat_1">Nama Kandidat</label>
                    <input type="text" id="nama_kandidat_1" name="nama_kandidat_1" placeholder="Nama Kandidat" required value='<?php echo htmlspecialchars($nama_kandidat[0] ?? "", ENT_QUOTES, 'UTF-8'); ?>'>
                  </div>

                  <div class="input-group">
                    <label for="visi_kandidat_1">Visi Kandidat</label>
                    <input type="text" id="visi_kandidat_1" name="visi_kandidat_1" placeholder="Visi Kandidat" required value='<?php echo htmlspecialchars($visi_kandidat[0] ?? "", ENT_QUOTES, 'UTF-8'); ?>'>
                  </div>

                  <div class="input-group">
                    <label for="misi_kandidat_1">Misi Kandidat</label>
                    <textarea id="misi_kandidat_1" name="misi_kandidat_1" placeholder="Misi Kandidat" required><?php echo htmlspecialchars($misi_kandidat[0] ?? "", ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>

                  <div class="input-group">
                    <label for="foto_kandidat_1">Foto Kandidat</label>
                    <div class="photo-upload" onclick="this.querySelector('input[type=file]').click()">
                      <img src='<?php echo $foto_kandidat[0] ?? " "; ?>' id="preview_kandidat_1" class="preview-img">

                      <div class="icon-overlay">
                        <svg class="camera-icon" viewBox="0 0 24 24">
                          <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                          <path d="M0 0h24v24H0z" fill="none" />
                          <circle cx="12" cy="10" r="3.2" />
                          <path d="M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z" />
                        </svg>
                        <span>Klik untuk Upload</span>
                      </div>
                      <input type="file" id="foto_kandidat_1" name="foto_kandidat_1" accept="image/*" style="display:none;" onchange="previewImage(this, 'preview_kandidat_1')">
                    </div>
                  </div>
                </div>
                <div class="candidate-box">
                  <h3>Kandidat 2</h3>

                  <div class="input-group">
                    <label for="nama_kandidat_2">Nama Kandidat</label>
                    <input type="text" id="nama_kandidat_2" name="nama_kandidat_2" placeholder="Nama Kandidat" required value='<?php echo htmlspecialchars($nama_kandidat[1] ?? "", ENT_QUOTES, 'UTF-8'); ?>'>
                  </div>

                  <div class="input-group">
                    <label for="visi_kandidat_2">Visi Kandidat</label>
                    <input type="text" id="visi_kandidat_2" name="visi_kandidat_2" placeholder="Visi Kandidat" required value='<?php echo htmlspecialchars($visi_kandidat[1] ?? "", ENT_QUOTES, 'UTF-8'); ?>'>
                  </div>

                  <div class="input-group">
                    <label for="misi_kandidat_2">Misi Kandidat</label>
                    <textarea id="misi_kandidat_2" name="misi_kandidat_2" placeholder="Misi Kandidat" required><?php echo htmlspecialchars($misi_kandidat[1] ?? "", ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>

                  <div class="input-group">
                    <label for="foto_kandidat_2">Foto Kandidat</label>
                    <div class="photo-upload" onclick="this.querySelector('input[type=file]').click()">
                      <img src='<?php echo $foto_kandidat[1] ?? " "; ?>' id="preview_kandidat_2" class="preview-img">

                      <div class="icon-overlay">
                        <svg class="camera-icon" viewBox="0 0 24 24">
                          <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                          <path d="M0 0h24v24H0z" fill="none" />
                          <circle cx="12" cy="10" r="3.2" />
                          <path d="M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z" />
                        </svg>
                        <span>Klik untuk Upload</span>
                      </div>
                      <input type="file" id="foto_kandidat_2" name="foto_kandidat_2" accept="image/*" style="display:none;" onchange="previewImage(this, 'preview_kandidat_2')">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="button">
          <button onclick="saveCandidates()">Selesai</button>
        </div>
      </div>
    </div>
  </div>
  <div class="tab-pane" id="waktu">
    <div class="box-container">
      <div class="chart">
        <h2>Hasil Pemilihan Ketua RT</h2>
        <canvas id="myChart" width="250" height="200"></canvas>
      </div>
    </div>
  </div>
  <div class="tab-pane" id="hasilkan">
    <div class="box-container">
      <div class="box">
        <div class="collapsible-section">
          <div class="section-header" onclick="toggleSection(this)">
            <span class="arrow">▶</span>
            <span class="title">Hasilkan PDF Undangan</span>
          </div>
          <div class="section-content">
            <div class="form-row">
              <label>
                <h2>Klik tombol berikut untuk membuat undangan PDF sebanyak <?php
                                                                            $rt_panitia = $_GET['rt'];
                                                                            $id_sesi = $_GET['id_sesi'];
                                                                            $query = "SELECT * FROM warga WHERE rt_panitia = ? AND id_sesi = ? ORDER BY id_warga ASC";
                                                                            $stmt = $conn->prepare($query);
                                                                            $stmt->bind_param("si", $rt_panitia, $id_sesi);
                                                                            $stmt->execute();
                                                                            $result = $stmt->get_result();
                                                                            echo mysqli_num_rows($result);
                                                                            ?> undangan</h2>
              </label>

            </div>
            <div class="button">
              <button onclick="generate_undangan()">Hasilkan</button>
            </div>
            <div class="form-row">
            </div>
          </div>
        </div>
        <div class="collapsible-section">
          <div class="section-header" onclick="toggleSection(this)">
            <span class="arrow">▶</span>
            <span class="title">Hasilkan PDF Rekap Suara</span>
          </div>
          <div class="section-content">
            <div class="form-row">
              <label>
                <h2>Klik tombol berikut untuk menghasilkan rekapan suara</h2>
              </label>
            </div>
            <div class="button">
              <button onclick="generate_rekapan()">Hasilkan</button>
            </div>
            <div class="form-row">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/popup-alert.html'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/popup-exit.html'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/popup-delete.php'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/popup-manage.php'; ?>




  <script>
    function toggleSection(header) {
      const content = header.nextElementSibling;
      const arrow = header.querySelector('.arrow');

      // Tutup semua section lain
      document.querySelectorAll('.collapsible-section .section-content').forEach(el => {
        if (el !== content) {
          el.style.maxHeight = null;
          el.classList.remove('open');
        }
      });

      document.querySelectorAll('.collapsible-section .section-header').forEach(h => {
        if (h !== header) {
          h.classList.remove('open');
          h.querySelector('.arrow').textContent = '▶';
        }
      });

      // Toggle current section
      if (content.classList.contains('open')) {
        content.style.maxHeight = content.scrollHeight + 'px'; // Set to current height first
        setTimeout(() => {
          content.style.maxHeight = '0';
        }, 10);
        content.classList.remove('open');
        header.classList.remove('open');
        arrow.textContent = '▶';
      } else {
        content.style.maxHeight = content.scrollHeight + 'px';
        content.classList.add('open');
        header.classList.add('open');
        arrow.textContent = '▶';

        // Setelah animasi selesai, set max-height ke auto agar responsif
        setTimeout(() => {
          content.style.maxHeight = 'auto';
        }, 300);
      }
    }

    // Simulasi upload file
    const dropZone = document.getElementById('dropZone');

    // Drag and Drop Events
    dropZone.addEventListener('dragover', (e) => {
      e.preventDefault();
      dropZone.style.borderColor = '#6cd670';
    });

    dropZone.addEventListener('dragleave', () => {
      dropZone.style.borderColor = '#ccc';
    });

    dropZone.addEventListener('drop', (e) => {
      e.preventDefault();
      dropZone.style.borderColor = '#ccc';

      const files = e.dataTransfer.files;
      handleFiles(files);
    });

    // Click to select file
    dropZone.addEventListener('click', () => {
      const input = document.createElement('input');
      input.type = 'file';
      input.accept = '.xlsx,.csv';
      input.multiple = false;
      input.onchange = (e) => handleFiles(e.target.files);
      input.click();
    });

    function handleFiles(files) {
      if (files.length === 0) return;

      const file = files[0];
      const validExtensions = ['.xlsx', '.csv'];
      const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

      if (!validExtensions.includes(fileExtension)) {
        popupalertContainer.classList.toggle('open');
        popupalert.classList.toggle('open');
        textAlert.textContent = 'Tidak dapat mengunggah file selain .xlsx atau .csv';
        return;
      }

      // Di sini bisa ditambahkan logika upload ke server
      const formData = new FormData();
      formData.append('file_warga', file); // 'file_warga' adalah nama key untuk ditangkap di PHP nanti
      formData.append('rt_panitia', "<?php echo $_GET['rt'] ?>")
      formData.append('id_sesi', <?php echo $_GET['id_sesi'] ?>)

      // 4. Kirim ke Server menggunakan Fetch API
      fetch('importDataWarga.php', { // <-- Ganti dengan nama file PHP pemroses kamu
          method: 'POST',
          body: formData
        })
        .then(response => response.text()) // Ubah response server jadi text
        .then(result => {
          // 5. Apa yang terjadi jika sukses/selesai
          console.log(result); // Cek console browser untuk debug
          popupalertContainer.classList.toggle('open');
          popupalert.classList.toggle('open');
          textAlert.textContent = 'Upload Berhasil! Data warga telah ditambahkan.';
          btnOkAlert.onclick = reloadPage;
          // Reload halaman agar tabel warga terupdate otomatis
        })
        .catch(error => {
          // 6. Apa yang terjadi jika error
          console.error('Error:', error);
          alert('Terjadi kesalahan saat mengunggah file.');
        });
    }

    // Edit/Delete action (contoh)
    // document.querySelectorAll('.fa-edit').forEach(btn => {
    //   btn.addEventListener('click', () => {
    //     alert('Fitur Edit belum diimplementasikan.');
    //   });
    // });

    // document.querySelectorAll('.fa-trash').forEach(btn => {
    //   btn.addEventListener('click', () => {
    //     if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
    //       alert('Data dihapus!');
    //       // Implementasi penghapusan data
    //     }
    //   });
    // });
    // Fungsi untuk memperbarui tampilan berdasarkan jumlah kandidat


    function saveCandidates() {
      let i = 0;
      const candidateBoxAll = document.querySelectorAll('.candidate-box');
      const formData = new FormData();
      let send = false;
      candidateBoxAll.forEach((candidateBox) => {
        i++;
        // const file = document.getElementById(`foto_kandidat_${i}`).files[0];
        // const validExtensions = ['.jpg', '.png', '.jpeg', '.gif'];
        // const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

        // if (!validExtensions.includes(fileExtension)) {
        //   // popupalertContainer.classList.toggle('open');
        //   // popupalert.classList.toggle('open');
        //   // textAlert.textContent = 'Tidak dapat mengunggah file selain .jpg, .png, .jpeg, atau .gif';
        //   alert('Tidak dapat mengunggah file selain .jpg, .png, .jpeg, atau .gif');
        //   return;
        // }
        if (candidateBox.querySelector(`#nama_kandidat_${i}`).value != "" && candidateBox.querySelector(`#visi_kandidat_${i}`).value != "" && candidateBox.querySelector(`#misi_kandidat_${i}`).value != "" && candidateBox.querySelector(`#foto_kandidat_${i}`).files[0] != undefined) {
          formData.append('no_kandidat[]', `${i}`);
          formData.append('nama_kandidat[]', document.getElementById(`nama_kandidat_${i}`).value);
          formData.append('visi_kandidat[]', document.getElementById(`visi_kandidat_${i}`).value);
          formData.append('misi_kandidat[]', document.getElementById(`misi_kandidat_${i}`).value);
          formData.append('foto_kandidat[]', document.getElementById(`foto_kandidat_${i}`).files[0]);
          send = true;
        }
      })
      if (send) {
        formData.append('id_sesi', document.getElementById(`candidatesContainer`).getAttribute('data-value'));
        formData.append('rt_panitia', document.getElementById(`candidatesContainer`).getAttribute('data-value2'));
        fetch('saveCandidate.php', {
            method: 'POST',
            body: formData
          }).then(response => response.text())
          .then(data => {
            console.log(data)
            window.location.reload();
          })
      }
    }
    // Fungsi preview gambar
    function previewImage(input, previewId) {
      const preview = document.getElementById(previewId);
      const file = input.files[0];
      const reader = new FileReader();

      reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
      };

      if (file) {
        reader.readAsDataURL(file);
      } else {
        preview.src = '';
        preview.style.display = 'none';
      }
    }

    // Inisialisasi saat halaman dimuat

    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    const tabUnderline = document.querySelector('.tab-underline');

    tabBtns.forEach((btn, index) => {
      btn.addEventListener('click', () => {
        // Remove active class from all buttons and panes
        tabBtns.forEach(b => b.classList.remove('active'));
        tabPanes.forEach(p => p.classList.remove('active'));

        // Add active class to clicked button
        btn.classList.add('active');

        // Show corresponding tab pane
        const tabId = btn.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');

        // Move underline
        tabUnderline.style.left = `${index * 33.333}%`;
      });
    });
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Nanang Suparman', 'Dede Mardede'],
        datasets: [{
          label: '# of Votes',
          data: [50, 45],
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1 // Agar sumbu Y angka bulat (bukan 1.5 suara)
            }
          }
        }
      }
    });
    const allCancel = document.querySelectorAll('.cancel');
    const ok = document.getElementById('ok');
    const popupdeleteContainer = document.querySelector('.popupdelete-container');
    const popupdelete = document.getElementById('popupdelete');
    const popupmanageContainer = document.querySelector('.popupmanage-container');
    const popupmanage = document.getElementById('popupmanage');

    allCancel.forEach(function(cancel) {
      cancel.addEventListener('click', () => {
        popupexitContainer.classList.remove('open');
        popupexit.classList.remove('open');
        popupdeleteContainer.classList.remove('open');
        popupdelete.classList.remove('open');
        popupmanageContainer.classList.remove('open');
        popupmanage.classList.remove('open');
        document.body.style.overflow = 'auto';
      });
    })

    // ok.addEventListener('click', (e) => {
    //   e.preventDefault();
    //   popupmenuContainer.classList.remove('open');
    //   popupmenu.classList.remove('open');
    //   document.body.style.overflow = 'auto';
    // });
    function logoutbtn() {
      window.location.href = '/assets/php/logout.php';
    }
    const allDelBtn = document.querySelectorAll('.delBtn');
    allDelBtn.forEach((delBtn) => {
      delBtn.addEventListener('click', function(e) {
        document.getElementById('idDel').value = delBtn.getAttribute('data-value');
        popupdeleteContainer.classList.toggle('open');
        popupdelete.classList.toggle('open');
        if (popupdeleteContainer.classList.contains('open')) {
          document.body.style.overflow = 'hidden';
        } else {
          document.body.style.overflow = 'auto';
        }
      });
    })
    const alleditBtn = document.querySelectorAll('.editBtn');
    const addDataWarga = document.querySelector('.addDataWarga');
    const menuFormManageAction = document.getElementById('menuFormManage');
    alleditBtn.forEach((editBtn) => {
      editBtn.addEventListener('click', function(e) {
        document.getElementById('idEdit').value = editBtn.getAttribute('data-value');
        popupmanageContainer.classList.toggle('open');
        popupmanage.classList.toggle('open');
        menuFormManageAction.action = '../../assets/php/editDataWarga.php';
        document.getElementById("menu-type-manage").innerText = "Edit Data Warga";
        if (document.getElementById(`nmknddt`)) {
          document.getElementById(`nmknddt`).id = `nmknddt${editBtn.getAttribute('data-value')}`;
        } else {
          document.querySelector('[id^="nmknddt"]').id = `nmknddt${editBtn.getAttribute('data-value')}`;
        }
        if (document.getElementById(`status-warga`)) {
          document.getElementById(`status-warga`).id = `status-warga${editBtn.getAttribute('data-value')}`;
        } else {
          document.querySelector('[id^="status-warga"]').id = `status-warga${editBtn.getAttribute('data-value')}`;
        }
        document.getElementById(`nmknddt${editBtn.getAttribute('data-value')}`).value = <?php echo json_encode($list_nama_warga) ?>[editBtn.getAttribute('data-value') - 1];
        document.getElementById(`status-warga${editBtn.getAttribute('data-value')}`).value = <?php echo json_encode($list_status_warga) ?>[editBtn.getAttribute('data-value') - 1];

        if (popupmanageContainer.classList.contains('open')) {
          document.body.style.overflow = 'hidden';
        } else {
          document.body.style.overflow = 'auto';
        }
      });
    })
    addDataWarga.addEventListener('click', function(e) {
      popupmanageContainer.classList.toggle('open');
      popupmanage.classList.toggle('open');
      menuFormManageAction.action = '../../assets/php/tambahDataWarga.php';
      document.getElementById("menu-type-manage").innerText = "Tambah Data Warga";

      if (popupmanageContainer.classList.contains('open')) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = 'auto';
      }
    });
    // --- BAGIAN 2: LOGIKA WEBSOCKET ---

    // Ambil ID Sesi dari URL
    const params = new URLSearchParams(window.location.search);
    const idSesi = params.get('id_sesi');


    // Koneksi ke Server WebSocket (Sesuaikan IP/Domain Anda)
    const conn = new WebSocket('wss://klikpilih.terpalb25.web.id/ws');

    conn.onopen = function() {

      // Lapor diri ke server
      conn.send(JSON.stringify({
        action: 'subscribe',
        id_sesi: idSesi
      }));
    };

    conn.onmessage = function(e) {
      // 1. Terima Data Mentah dari Server
      // Format data server: [{"no":1, "nama":"Jakk", "suara":10}, ...]
      const dataServer = JSON.parse(e.data);

      // 2. Pisahkan Nama dan Suara ke dalam Array terpisah (Format Chart.js)
      const listNama = dataServer.map(item => item.nama);
      const listSuara = dataServer.map(item => item.suara);

      // 3. Update Data Chart
      myChart.data.labels = listNama; // Update Label Bawah
      myChart.data.datasets[0].data = listSuara; // Update Tinggi Bar

      // 4. Render Ulang Chart (Animasi Otomatis)
      myChart.update();
    };

    // conn.onclose = function() {
    //   document.getElementById('status').innerText = "Terputus";
    //   document.getElementById('status').style.color = "red";
    // };
    function generate_undangan() {
      window.location.href = '/fpdf/generate_undangan.php?rt=<?php echo $_GET['rt'] ?>&id_sesi=<?php echo $_GET['id_sesi'] ?>'
    }

    function generate_rekapan() {
      window.location.href = '/fpdf/cetakan_rekapan.php?rt=<?php echo $_GET['rt'] ?>&id_sesi=<?php echo $_GET['id_sesi'] ?>';
    }
  </script>
</body>

</html>