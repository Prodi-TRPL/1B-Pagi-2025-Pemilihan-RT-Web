<?php
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
// Cek apakah user memiliki session login
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: /admin");
  exit;
}
$title_web = "Beranda";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title><?php echo $title_web ?></title>
  <link rel="icon" type="image/x-icon" href="/assets/img/logo-2.ico">
  <link rel="icon" type="image/x-icon" href="/assets/img/logo-2.ico">
  <link rel="stylesheet" href="/assets/css/dashboard/style.css" />

</head>

<body>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/header.php'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/sidebar.php'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/navbar.php'; ?>
  <main>
    <?php
    $rt_panitia = $_SESSION['rt_panitia'];

    if ($rt_panitia != 'RW') {
      $query = "SELECT * FROM sesi_pemilihan WHERE rt_panitia = ? ORDER BY id_sesi ASC";

      $stmt = $conn->prepare($query);

      $stmt->bind_param("s", $rt_panitia);
    } else {
      $query = "SELECT * FROM sesi_pemilihan ORDER BY id_sesi ASC";

      $stmt = $conn->prepare($query);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $no = 0;
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $list_nama_sesi[] = $row["nama_sesi"];
        $list_foto_sampul[] = $row["foto_sampul"];
        $list_waktu_mulai[] = $row["waktu_mulai"];
        $list_waktu_selesai[] = $row["waktu_selesai"];
        $list_rt_panitia[] = $row["rt_panitia"];
        $no++;

        $nama = $row['nama_sesi'];
        $foto = $row['foto_sampul'];

    ?>

        <div class="box-container">
          <div class="card" style="border-radius: 15px;">
            <a href="/admin/rt/index.php?rt=<?php echo $row['rt_panitia']; ?>&id_sesi=<?php echo $row['id_sesi']; ?>">
              <div class="card-header" style="border-radius: 15px 15px 0 0;">
                <img src="<?php echo $foto ?? " "; ?>">
              </div>
            </a>
            <div class="card-footer" style="border-radius: 0 0 15px 15px;">
              <span class="label"><?php echo $nama; ?></span>
              <div class="dots-container">
                <div class="dots">
                  <div class="dot"></div>
                  <div class="dot"></div>
                  <div class="dot"></div>
                </div>
                <div class="dropdown-menu" id="myDropdown">
                  <a class="edit editBtn" id="editBtn" data-value=<?php echo "$no"; ?>>Edit</a>
                  <a class="delete delBtn" id="delBtn" data-value=<?php echo "$no"; ?>>Delete</a>
                </div>
              </div>
            </div>
          </div>
        </div>

    <?php
      }
    }
    ?>



    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/popup-menu.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/popup-delete-session.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/popup-exit.html'; ?>
  </main>

  <div class="add-button-container">
    <div class="add-button" id="addBtn">
      <div class="plus-icon"></div>
    </div>
  </div>

  <script>
    const addBtn = document.getElementById('addBtn');
    const menuFormMenu = document.getElementById('menuFormMenu');
    addBtn.addEventListener('click', function(e) {
      popupmenuContainer.classList.toggle('open');
      popupmenu.classList.toggle('open');
      document.getElementById("menu-type").innerText = "Tambah Sesi";
      menuFormMenu.action = '../../assets/php/tambahElection.php';
      document.getElementsByClassName('rt-select')[0].style.display = 'block';
      const now = new Date();
      document.getElementById(`waktu1`).value = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;
      document.getElementById(`waktu2`).value = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;
      document.getElementById(`tanggal1`).value = `${now.getFullYear()}-${(now.getMonth() + 1).toString().padStart(2, '0')}-${now.getDate().toString().padStart(2, '0')}`;
      document.getElementById(`tanggal2`).value = `${now.getFullYear()}-${(now.getMonth() + 1).toString().padStart(2, '0')}-${now.getDate().toString().padStart(2, '0')}`;
      if (popupmenuContainer.classList.contains('open')) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = 'auto';
      }
      this.classList.add('clicked');
      setTimeout(() => {
        this.classList.remove('clicked');
      }, 300);
    });
    const allCancel = document.querySelectorAll('.cancel');
    const ok = document.getElementById('ok');
    // const popupdeleteContainer = document.querySelector('.popupdelete-container');
    allCancel.forEach(function(cancel) {
      cancel.addEventListener('click', () => {
        popupexitContainer.classList.remove('open');
        popupexit.classList.remove('open');
        popupmenuContainer.classList.remove('open');
        popupmenu.classList.remove('open');
        popupdeleteContainer.classList.remove('open');
        popupdelete.classList.remove('open');
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


    document.querySelectorAll('.dots-container').forEach((container) => {
      container.querySelector('.dots').addEventListener('click', () => {
        container.querySelector('#myDropdown').classList.toggle("show");
      })
    })

    // Fitur Tambahan: Menutup menu jika user klik di luar menu
    window.onclick = function(event) {
      // alert (event.target.closest('.dots'))
      if (!event.target.closest('.dots')) {
        var dropdowns = document.getElementsByClassName("dropdown-menu");
        for (var i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
      if (event.target.matches('.sidebar')) {
        sidebar.classList.remove('show');
        sidebar.classList.toggle('hide');
      }
    }
    const popupdeleteContainer = document.querySelector('.popupdelete-container');
    const popupdelete = document.getElementById('popupdelete');
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
    const popupmenuContainer = document.querySelector('.popupmenu-container');
    const popupmenu = document.getElementById('popupmenu');

    const semuaedit = document.querySelectorAll('.edit');

    semuaedit.forEach(function(edit) {
      edit.addEventListener('click', () => {
        popupmenuContainer.classList.toggle('open');
        popupmenu.classList.toggle('open');
        document.getElementById("menu-type").innerText = "Edit Sesi";
        document.getElementsByClassName('rt-select')[0].style.display = 'none';
        menuFormMenu.action = '../../assets/php/editElection.php';
        document.getElementById('idEdit').value = edit.getAttribute('data-value');
        if (document.getElementById(`nmssi`)) {
          document.getElementById(`nmssi`).id = `nmssi${edit.getAttribute('data-value')}`;
        } else {
          document.querySelector('[id^="nmssi"]').id = `nmssi${edit.getAttribute('data-value')}`;
        }
        if (document.getElementById(`tanggal1`)) {
          document.getElementById(`tanggal1`).id = `tanggal1_${edit.getAttribute('data-value')}`;
        } else {
          document.querySelector('[id^="tanggal1"]').id = `tanggal1_${edit.getAttribute('data-value')}`;
        }
        if (document.getElementById(`tanggal2`)) {
          document.getElementById(`tanggal2`).id = `tanggal2_${edit.getAttribute('data-value')}`;
        } else {
          document.querySelector('[id^="tanggal2"]').id = `tanggal2_${edit.getAttribute('data-value')}`;
        }
        if (document.getElementById(`waktu1`)) {
          document.getElementById(`waktu1`).id = `waktu1_${edit.getAttribute('data-value')}`;
        } else {
          document.querySelector('[id^="waktu1"]').id = `waktu1_${edit.getAttribute('data-value')}`;
        }
        if (document.getElementById(`waktu2`)) {
          document.getElementById(`waktu2`).id = `waktu2_${edit.getAttribute('data-value')}`;
        } else {
          document.querySelector('[id^="waktu2"]').id = `waktu2_${edit.getAttribute('data-value')}`;
        }
        if (document.getElementById(`foto_sampul`)) {
          document.getElementById(`foto_sampul`).id = `foto_sampul_${edit.getAttribute('data-value')}`;
        } else {
          document.querySelector('[id^="foto_sampul"]').id = `foto_sampul_${edit.getAttribute('data-value')}`;
        }
        if (document.getElementById(`preview_foto_sampul`)) {
          document.getElementById(`preview_foto_sampul`).id = `preview_foto_sampul_${edit.getAttribute('data-value')}`;
        } else {
          document.querySelector('[id^="preview_foto_sampul"]').id = `preview_foto_sampul_${edit.getAttribute('data-value')}`;
        }
        if (document.getElementById(`rt_panitia`)) {
          document.getElementById(`rt_panitia`).id = `rt_panitia_${edit.getAttribute('data-value')}`;
        } else {
          document.querySelector('[id^="rt_panitia"]').id = `rt_panitia_${edit.getAttribute('data-value')}`;
        }
        document.getElementById(`preview_foto_sampul_${edit.getAttribute('data-value')}`).src = <?php echo json_encode($list_foto_sampul) ?>[edit.getAttribute('data-value') - 1];
        document.getElementById(`foto_sampul_${edit.getAttribute('data-value')}`).onchange = previewImage;
        document.getElementById(`nmssi${edit.getAttribute('data-value')}`).value = <?php echo json_encode($list_nama_sesi) ?>[edit.getAttribute('data-value') - 1];
        document.getElementById(`tanggal1_${edit.getAttribute('data-value')}`).value = <?php echo json_encode($list_waktu_mulai) ?>[edit.getAttribute('data-value') - 1].split(' ')[0];
        document.getElementById(`tanggal2_${edit.getAttribute('data-value')}`).value = <?php echo json_encode($list_waktu_selesai) ?>[edit.getAttribute('data-value') - 1].split(' ')[0];
        document.getElementById(`waktu1_${edit.getAttribute('data-value')}`).value = <?php echo json_encode($list_waktu_mulai) ?>[edit.getAttribute('data-value') - 1].split(' ')[1].substring(0, 5);
        document.getElementById(`waktu2_${edit.getAttribute('data-value')}`).value = <?php echo json_encode($list_waktu_selesai) ?>[edit.getAttribute('data-value') - 1].split(' ')[1].substring(0, 5);
        document.getElementById(`rt_panitia_${edit.getAttribute('data-value')}`).value = <?php echo json_encode($list_rt_panitia) ?>[edit.getAttribute('data-value') - 1];
        if (popupmenuContainer.classList.contains('open')) {
          document.body.style.overflow = 'hidden';
        } else {
          document.body.style.overflow = 'auto';
        }
      });
    });

    popupmenuContainer.addEventListener('click', (e) => {
      if (e.target === popupmenuContainer) {
        popupmenuContainer.classList.remove('open');
        popupmenu.classList.remove('open');
        document.body.style.overflow = 'auto';
      }
    });
    document.getElementById(`foto_sampul`).onchange = previewImage;

    function previewImage() {
      const preview = document.querySelector('[id^="preview_foto_sampul"]');
      const file = this.files[0];
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
  </script>
</body>

</html>