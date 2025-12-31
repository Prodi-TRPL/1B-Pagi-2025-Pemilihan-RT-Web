<?php
$title_web = "Pemilihan Kandidat";
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
// Cek apakah user memiliki session login
session_start();
if (!isset($_SESSION["login_warga"])) {
  header("Location: /warga");
  exit;
}
// echo $_SESSION['rt_panitia'];
$id_warga = $_SESSION['id_warga'];
// --- Langkah 1: Cari ID Sesi di tabel Warga ---
$stmt = $conn->prepare("SELECT id_sesi, status FROM warga WHERE id_warga = ?");
$stmt->bind_param("i", $id_warga); // "s" = string
$stmt->execute();

$result_warga = $stmt->get_result();
$data_warga = $result_warga->fetch_assoc();

// Simpan hasilnya ke variabel
// Gunakan operator '?? null' untuk mencegah error jika data tidak ditemukan
$status = $data_warga['status'] ?? null;
$id_sesi = $data_warga['id_sesi'] ?? null;

$stmt->close();



$status_sesi = null;

if ($id_sesi) {
  $query_sesi = "SELECT status FROM sesi_pemilihan WHERE id_sesi = ?";
  $stmt = $conn->prepare($query_sesi);
  $stmt->bind_param("i", $id_sesi); // "i" = integer (asumsi id_sesi berupa angka)
  $stmt->execute();

  $result_sesi = $stmt->get_result();
  $data_sesi = $result_sesi->fetch_assoc();

  $status_sesi = $data_sesi['status'] ?? null;

  $stmt->close();
}

// Sekarang variabel $status, $id_sesi, dan $status_sesi sudah siap dipakai
if ($status_sesi == 'Persiapan') {
  header("Location: ../menunggu/index.php");
}
if ($status == 'Sudah Memilih' || $status_sesi == 'Selesai') {
  header("Location: ../selesai/index.php");
}


?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title_web ?></title>
  <link rel="icon" type="image/x-icon" href="/assets/img/logo-2.ico">
  <link rel="stylesheet" href="/assets/css/votingkandidat/style.css">

</head>

<body>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/header.php'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/sidebar.php'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/navbar.php'; ?>
  <div class="container">
    <section class="candidates">
      <?php
      $nama_warga = $_SESSION['nama_warga'];

      $stmt = $conn->prepare("SELECT rt_panitia, id_sesi FROM warga WHERE id_warga = ?");
      $stmt->bind_param("i", $id_warga);
      $stmt->execute();

      $result_warga = $stmt->get_result();
      $data_warga = $result_warga->fetch_assoc();

      $stmt->close();

    
      if ($data_warga) {
        $rt_target = $data_warga['rt_panitia'];
        $sesi_target = $data_warga['id_sesi'];

        $query_kandidat = "SELECT * FROM kandidat WHERE rt_panitia = ? AND id_sesi = ? ORDER BY no_kandidat ASC";

        $stmt = $conn->prepare($query_kandidat);

       
        $stmt->bind_param("si", $rt_target, $sesi_target);

        $stmt->execute();
        $result = $stmt->get_result();

      
      } else {
        $result = null;
      }
      $no = 0;
      if (mysqli_num_rows($result) > 0) {
        // 4. Looping: Mengulang kode HTML sebanyak jumlah baris data (i kali)
        while ($row = mysqli_fetch_assoc($result)) {
          $no++;
          // Menampung data database ke variabel agar mudah dibaca

          $nama = $row['nama_kandidat'];
          $visi = $row['visi'];
          $misi = $row['misi'];
          $foto = $row['foto_kandidat'];

          // Tentukan warna tombol status (Opsional: Agar tampilan lebih bagus)
          // Jika status 'Sudah Memilih' tombol jadi hijau, jika belum jadi kuning/merah
      ?>

          <div class="card">
            <img src="<?php echo $foto ?? " "; ?>" class="candidate-photo">
            <h3><?php echo $nama; ?></h3>
            <h4>Visi</h4>
            <p><?php echo $visi; ?></p>
            <h4>Misi</h4>
            <p id="misi"><?php echo $misi; ?></p>
            <div class="vote">
              <div class="radio-kotak">
                <input type="radio" id="vote<?php echo $no; ?>" name="kandidat" value="<?php echo $nama; ?>">
                <label for="vote<?php echo $no; ?>">Pilih</label>
              </div>
            </div>
          </div>

      <?php
        } // Akhir dari loop while
      } else {
        // Jika data kosong (0 baris)
        echo "<tr><td colspan='5' class='text-center'>Belum ada calon kandidat.</td></tr>";
      }
      ?>

      <!-- <div class="card">
        <img src="/assets/img/malvin1.png" alt="Dede" class="candidate-photo">
        <h3>MalvinTubers</h3>
        <h4>Visi</h4>
        <p>Menjadi manusia yang lebih baik</p>
        <h4>Misi</h4>
        <ul>
          <li>ngerjain series</li>
          <li>tidur.</li>
          <li>makan.</li>
          <li>kuliah.</li>
          <li>menghalu.</li>
        </ul>
        <div class="vote">
          <div class="radio-kotak">
            <input type="radio" id="malvin-tubers" name="kandidat" value="MalvinTubers">
            <label for="malvin-tubers">Pilih</label>
          </div>
        </div>
      </div> -->
    </section>

    <div class="button-group2">
      <button type="button" class="exit-menu" id="exit-menu">Keluar</button>
      <button type="button" class="confirm" id="choose">Pilih</button>
    </div>
  </div>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/popup-exit.html'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/popup-confirm.html'; ?>
</body>
<script>
  const allCancel = document.querySelectorAll('.cancel');
  const choose = document.getElementById('choose');
  const popupconfirmContainer = document.querySelector('.popupconfirm-container');
  const popupconfirm = document.querySelector('.popupconfirm');

  allCancel.forEach(function(cancel) {
    cancel.addEventListener('click', () => {
      popupconfirmContainer.classList.remove('open');
      popupconfirm.classList.remove('open');
      popupexitContainer.classList.remove('open');
      popupexit.classList.remove('open');
      document.body.style.overflow = 'auto';
    });
  })

  choose.addEventListener('click', (e) => {
    const radioTerpilih = document.querySelector('input[name="kandidat"]:checked');
    if (!radioTerpilih) {
      alert('Silahkan Pilih Dulu yaa kandidat nya yang mana');
    } else {
      popupconfirmContainer.classList.toggle('open');
      popupconfirm.classList.toggle('open');
      document.querySelector('.name-selected').innerHTML = radioTerpilih.value
      document.querySelector('#nama_pilihan_kandidat').value = radioTerpilih.value
      if (popupconfirmContainer.classList.contains('open')) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = 'auto';
      }
    }
  });

  function logoutbtn() {
    window.location.href = '/assets/php/logoutwarga.php';
  }
  document.querySelectorAll('#misi').forEach((pTag) => {
    const text = pTag.innerText;

    // Buat elemen UL baru
    const ul = document.createElement('ul');

    text.split('-').forEach(item => {
      // Cek apakah item ada isinya (untuk menghindari string kosong hasil split pertama)
      if (item.trim().length > 0) {
        const li = document.createElement('li');

        // Hapus titik dan spasi, lalu masukkan ke li
        // Tidak perlu nambah "-" manual karena <ul> sudah punya bullet point
        li.textContent = item.replace(/\./g, '').trim();

        ul.appendChild(li);
      }
    });

    // Ganti elemen <p> lama dengan <ul> baru
    pTag.replaceWith(ul);
  })
</script>

</html>