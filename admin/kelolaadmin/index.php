<?php

// Cek apakah user memiliki session login
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: /admin");
  exit;
}
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
$title_web = "Kelola Admin";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title_web ?></title>
  <link rel="icon" type="image/x-icon" href="/assets/img/logo-2.ico">
  <link rel="stylesheet" href="/assets/css/kelolaadmin/style.css">
</head>

<body>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/header.php'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/sidebar.php'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/navbar.php'; ?>
  <div class="box-container">
    <div class="box">
      <div class="form-row"></div>
      <div class="form-row">
        <div class="table-container">
          <table id="RTAccount">
            <tbody>
              <?php
              $query = "SELECT * FROM admin";
              $stmt = $conn->prepare($query);
              $stmt->execute();
              $result = $stmt->get_result();
              $no = 0;
              if (mysqli_num_rows($result) > 0) {
                // 4. Looping: Mengulang kode HTML sebanyak jumlah baris data (i kali)
                while ($row = mysqli_fetch_assoc($result)) {
                  $no++;
                  // Menampung data database ke variabel agar mudah dibaca

                  $rt = $row['rt_panitia'];


                  // Tentukan warna tombol status (Opsional: Agar tampilan lebih bagus)
                  // Jika status 'Sudah Memilih' tombol jadi hijau, jika belum jadi kuning/merah
              ?>
                  <?php if ($rt != 'RW') echo '<tr>
                    <td><a href="/admin/suntingprofil/rt/rtedit?id=' . $no . '"><img src="/assets/img/folder.png">' . $rt . '</a></td>
                  </tr>' ?>


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
    </div>
  </div>

  <?php
  if (mysqli_num_rows($result) < 10) {
    echo '<div class="add-button-container">
    <div class="add-button" id="addBtn">
      <div class="plus-icon"></div>
    </div>
  </div>';
  }

  ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/popup-exit.html'; ?>



</body>
<script>
  const addBtn = document.getElementById('addBtn');
  addBtn.addEventListener('click', function(e) {
    window.location.href = "/admin/suntingprofil/rt/rtnew";
  });
  // const bodyTable = document.querySelector('tbody')
  // bodyTable.innerHTML = ''; // Kosongkan kontainer
  // let count = 4;


  // for (let i = 1; i <= count; i++) {
  //   const list = document.createElement('tr')
  //   list.onclick = changeInfoRT;
  //   list.innerHTML = `
  //               <td><img src="/assets/img/folder.png">RT${i}</td>
  //   `;

  //   bodyTable.appendChild(list);
  // }

  function changeInfoRT() {
    window.location.href = "/admin/suntingprofil/rt/rtedit"
  }
  const allCancel = document.querySelectorAll('.cancel');
  const ok = document.getElementById('ok');

  allCancel.forEach(function(cancel) {
    cancel.addEventListener('click', () => {
      popupexitContainer.classList.remove('open');
      popupexit.classList.remove('open');
      popupmenuContainer.classList.remove('open');
      popupmenu.classList.remove('open');
      document.body.style.overflow = 'auto';
    });
  })


  function logoutbtn() {
    window.location.href = '/';
  }
</script>

</html>