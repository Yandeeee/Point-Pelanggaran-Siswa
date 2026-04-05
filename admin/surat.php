<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_app/index.php");
    exit;
}

include "../login_app/database.php";

// Fetch students for the dropdown
$students = [];
$query_siswa = "SELECT id, nama_siswa, nis, kelas, nama_orangtua FROM siswa ORDER BY nama_siswa ASC";
$result_siswa = mysqli_query($conn, $query_siswa);
if ($result_siswa) {
    while ($row = mysqli_fetch_assoc($result_siswa)) {
        $students[] = $row;
    }
}

// Extract base64 image from surat_orangtua.html dynamically
$html_template = @file_get_contents('surat_orangtua.html');
preg_match('/src="(data:image\/jpeg;base64,[^"]+)"/', $html_template, $matches);
$base64_image = isset($matches[1]) ? $matches[1] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../login_app/asset/css/style-main.css">
    <style>
        /* Form styling */
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        
        /* Print Template Styling */
        #print-template {
            display: none;
        }
        
        .d-none {
            display: none !important;
        }
        
        @media print {
            .sidebar, .header, .form-container {
                display: none !important;
            }
            .main-content {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                left: 0 !important;
            }
            #print-template {
                display: block !important;
                width: 100%;
                font-family: "Times New Roman", serif;
                font-size: 12pt;
                line-height: 1.5;
            }
            @page {
              size: A4;
              margin: 0cm 2cm;
            }

            #print-template table {
              border-collapse: collapse;
            }

            #print-template p {
              margin: 0;
            }

            #print-template .kop {
                text-align: center;
                margin-bottom: 20px;
            }

            #print-template .kop img {
                width: 100%;
                object-fit: contain;
            }

            #print-template .header td {
              vertical-align: top;
            }

            #print-template .header td:first-child {
              width: 80px;
            }

            #print-template .identitas{
                margin-left: 30px;
            }

            #print-template .identitas td {
              padding: 2px 6px;
            }

            #print-template .identitas td:first-child {
              width: 200px;
            }

            #print-template .pembuka {
              margin-top: 20px;
            }

            #print-template .detail {
              margin-left: 30px;
              margin-top: 10px;
            }

            #print-template .detail td {
              padding: 2px 6px;
            }

            #print-template .detail td:first-child {
              width: 150px;
            }

            #print-template .keperluan {
              margin-top: 10px;
            }

            #print-template .penutup {
              margin-top: 20px;
              text-align: justify;
            }

            #print-template .ttd {
              width: 100%;
              margin-top: 50px;
              text-align: center;
            }

            #print-template .ttd td {
              width: 50%;
              vertical-align: top;
            }

            #print-template .spasi-ttd {
              height: 70px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header" style="text-align: center; padding: 20px 0; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px;">
                <img src="../login_app/asset/gambar/images.png" alt="Logo" style="width: 60px; height: 60px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; margin-bottom: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                <h2 style="font-size: 20px; color: #fff; margin: 0; padding: 0;">Admin Panel</h2>
                <div style="color: #c4c8ceff; font-size: 14px; margin-top: 10px; font-weight: 500;">Sistem Poin Pelanggaran</div>
            </div>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="riwayat_pelanggaran.php">Riwayat Pelanggaran</a></li>
                <li><a href="rekap_pelanggaran.php">Rekap Pelanggaran</a></li>
                <li><a href="pelanggaran.php">Data Pelanggaran</a></li>
                <li><a href="guru.php">Data Guru</a></li>
                <li><a href="siswa.php">Data Siswa</a></li>
                <li><a href="surat.php" class="active">Cetak Laporan</a></li>
                <li><a href="users.php">Manajemen User</a></li>
                <li style="margin-top: auto;"><a href="../login_app/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

    <div class="main-content">
        <div class="header">
            <div class="header-title">
                <h2>Cetak Surat</h2>
            </div>
            <div class="user-info">
                <span>Selamat datang, <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
            </div>
        </div>

        <div class="form-container">
            <h3>Form Input Cetak Surat</h3>
            <form id="form-cetak">
                
                <div class="form-group">
                    <label for="template_surat">Pilih Jenis Surat</label>
                    <select id="template_surat" class="form-control" onchange="changeTemplate()" required>
                        <option value="orangtua">Surat Pemanggilan Orang Tua</option>
                        <option value="teguran">Surat Teguran </option>
                        <option value="pindah">Surat Pindah</option>
                    </select>
                </div>
                
                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                
                <!-- Common Fields -->
                <div class="form-group">
                    <label for="nomor_surat">Nomor Surat</label>
                    <input type="text" id="nomor_surat" class="form-control" placeholder="Contoh: 020/SMKTI/B/I/2026" required>
                </div>
                
                <div class="form-group">
                    <label for="id_siswa">Pilih Siswa</label>
                    <select id="id_siswa" class="form-control" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php foreach($students as $s): ?>
                            <option value="<?php echo htmlspecialchars($s['id']); ?>" 
                                    data-nama="<?php echo htmlspecialchars($s['nama_siswa']); ?>"
                                    data-nis="<?php echo htmlspecialchars($s['nis']); ?>"
                                    data-kelas="<?php echo htmlspecialchars($s['kelas']); ?>"
                                    data-ortu="<?php echo htmlspecialchars($s['nama_orangtua'] ? $s['nama_orangtua'] : 'Bapak / Ibu'); ?>">
                                <?php echo htmlspecialchars($s['nama_siswa'] . ' (' . $s['kelas'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Specific Fields: Surat Orang Tua -->
                <div id="form-orangtua">
                    <div class="form-group">
                        <label for="perihal_ortu">Perihal</label>
                        <input type="text" id="perihal_ortu" class="form-control input-sync" value="Pemanggilan Orang Tua / Wali Siswa">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_ortu">Hari / Tanggal Pemanggilan</label>
                        <input type="text" id="tanggal_ortu" class="form-control input-sync" placeholder="Contoh: Kamis, 08 Januari 2026">
                    </div>
                    <div class="form-group">
                        <label for="pukul_ortu">Pukul</label>
                        <input type="text" id="pukul_ortu" class="form-control input-sync" placeholder="Contoh: 08.00 WITA">
                    </div>
                    <div class="form-group">
                        <label for="tempat_ortu">Tempat</label>
                        <input type="text" id="tempat_ortu" class="form-control input-sync" value="SMK TI Bali Global Denpasar">
                    </div>
                    <div class="form-group">
                        <label for="keperluan_ortu">Keperluan</label>
                        <input type="text" id="keperluan_ortu" class="form-control input-sync" value="Masalah Disiplin Siswa">
                    </div>
                    <div class="form-group">
                        <label for="nama_waka_ortu">Nama Waka Kesiswaan</label>
                        <input type="text" id="nama_waka_ortu" class="form-control input-sync" value="Bagus Putu Eka Wijaya, S.Kom">
                    </div>
                    <div class="form-group">
                        <label for="nama_bk_ortu">Nama Guru BK</label>
                        <input type="text" id="nama_bk_ortu" class="form-control input-sync" value="I Gusti Ayu Rinjani, M.Pd">
                    </div>
                </div>

                <!-- Specific Fields: Surat Teguran -->
                <div id="form-teguran" class="d-none">
                    <div class="form-group">
                        <label for="alasan_teguran">Alasan Teguran</label>
                        <input type="text" id="alasan_teguran" class="form-control input-sync" placeholder="Contoh: Sering terlambat masuk sekolah">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_pelanggaran_teguran">Tanggal Terjadinya Pelanggaran</label>
                        <input type="text" id="tanggal_pelanggaran_teguran" class="form-control input-sync" placeholder="Contoh: Senin, 05 Januari 2026">
                    </div>
                    <div class="form-group">
                        <label for="nama_kepsek_teguran">Nama Kepala Sekolah</label>
                        <input type="text" id="nama_kepsek_teguran" class="form-control input-sync" value="Drs. I Gusti Made Murjana,M.Pd">
                    </div>
                </div>

                <!-- Specific Fields: Surat Pindah -->
                <div id="form-pindah" class="d-none">
                    <div class="form-group">
                        <label for="sekolah_tujuan_pindah">Sekolah Tujuan</label>
                        <input type="text" id="sekolah_tujuan_pindah" class="form-control input-sync" placeholder="Contoh: SMA Negeri 1 Denpasar">
                    </div>
                    <div class="form-group">
                        <label for="alasan_pindah">Alasan Pindah</label>
                        <input type="text" id="alasan_pindah" class="form-control input-sync" placeholder="Contoh: Mengikuti orang tua pindah tugas">
                    </div>
                    <div class="form-group">
                        <label for="nama_kepsek_pindah">Nama Kepala Sekolah</label>
                        <input type="text" id="nama_kepsek_pindah" class="form-control input-sync" value="Drs. I Gusti Made Murjana,M.Pd">
                    </div>
                </div>

                <!-- Common Date/Place Field -->
                <div class="form-group">
                    <label for="tanggal_ttd">Tempat, Tanggal Surat</label>
                    <input type="text" id="tanggal_ttd" class="form-control input-sync" placeholder="Contoh: Denpasar, 07 Januari 2026" required>
                </div>

                <button type="button" class="btn-primary" onclick="cetakSurat()">
                    <i class="fas fa-print"></i> Cetak Surat
                </button>
            </form>
        </div>
    </div>

    <!-- Print Template Section -->
    <div id="print-template">
        <!-- Template Surat Orang Tua -->
        <div id="print-orangtua-wrapper">
            <div class="kop">
                <?php if($base64_image): ?>
                    <img src="<?php echo $base64_image; ?>" alt="Kop Surat">
                <?php else: ?>
                    <h2>KOP SURAT SMK TI BALI GLOBAL DENPASAR</h2>
                <?php endif; ?>
            </div>
            <table class="header">
              <tr>
                <td>No.</td>
                <td>: <span class="sync-nomor_surat"></span></td>
              </tr>
              <tr>
                <td>Lamp.</td>
                <td>: -</td>
              </tr>
              <tr>
                <td>Perihal</td>
                <td>: <span id="print_perihal_ortu"></span></td>
              </tr>
            </table>

            <br />
            <div class="tujuan">
              <p>Kepada</p>
              <p>Yth. <span class="sync-ortu"></span></p>

              <table class="identitas">
                <tr>
                  <td>Orang Tua / Wali dari</td>
                  <td>: <b class="sync-nama_siswa"></b></td>
                </tr>
                <tr>
                  <td>Kelas / NIS</td>
                  <td>: <span class="sync-kelas_nis"></span></td>
                </tr>
              </table>
            </div>
            
            <p class="pembuka">Dengan hormat,</p>
            <p class="isi">Bersama surat ini, kami mengharapkan kehadiran Bapak / Ibu pada :</p>

            <table class="detail">
              <tr>
                <td>Hari / Tanggal</td>
                <td>: <span id="print_tanggal_ortu"></span></td>
              </tr>
              <tr>
                <td>Pukul</td>
                <td>: <span id="print_pukul_ortu"></span></td>
              </tr>
              <tr>
                <td>Tempat</td>
                <td>: <span id="print_tempat_ortu"></span></td>
              </tr>
              <tr>
                <td>Keperluan</td>
                <td>: <span id="print_keperluan_ortu"></span></td>
              </tr>
            </table>

            <p class="penutup">
              Demikian surat ini kami sampaikan, besar harapan kami pertemuan ini agar tidak diwakilkan. Atas perhatian dan kerjasamanya, kami ucapkan terimakasih.
            </p>

            <table class="ttd">
              <tr>
                <td>
                  Mengetahui,<br />
                  Waka Kesiswaan
                  <div class="spasi-ttd"></div>
                  <b id="print_nama_waka_ortu"></b>
                </td>
                <td>
                  <span class="sync-tanggal_ttd"></span><br />
                  Guru BK
                  <div class="spasi-ttd"></div>
                  <b id="print_nama_bk_ortu"></b>
                </td>
              </tr>
            </table>
        </div>

        <!-- Template Surat Teguran -->
        <div id="print-teguran-wrapper" class="d-none">
            <div class="kop">
                <?php if($base64_image): ?>
                    <img src="<?php echo $base64_image; ?>" alt="Kop Surat">
                <?php else: ?>
                    <h2>KOP SURAT SMK TI BALI GLOBAL DENPASAR</h2>
                <?php endif; ?>
            </div>
            <table class="header">
              <tr>
                <td>No.</td>
                <td>: <span class="sync-nomor_surat"></span></td>
              </tr>
              <tr>
                <td>Lamp.</td>
                <td>: -</td>
              </tr>
              <tr>
                <td>Perihal</td>
                <td>: <b>Pemberitahuan Surat Teguran</b></td>
              </tr>
            </table>

            <br />
            <div class="tujuan">
              <p>Kepada Yth.</p>
              <p><span class="sync-ortu"></span></p>
              <p>Orang Tua / Wali Murid dari:</p>
              <p><b class="sync-nama_siswa"></b> (NIS: <span class="sync-kelas_nis"></span>)</p>
            </div>
            
            <p class="pembuka">Dengan hormat,</p>
            <p class="penutup">
              Melalui surat ini, kami dari pihak sekolah ingin menyampaikan teguran resmi kepada anak Bapak/Ibu karena telah melakukan pelanggaran kedisiplinan sekolah. Adapun pelanggaran yang dilakukan adalah:
            </p>
            <p style="margin: 10px 0; font-weight: bold; text-align: center;">"<span id="print_alasan_teguran"></span>"</p>
            <p class="penutup">
              Tindakan indisipliner tersebut telah terjadi pada tanggal <span id="print_tanggal_pelanggaran_teguran"></span>. Kami berharap Bapak/Ibu dapat memberikan bimbingan dan pengawasan yang lebih intensif kepada ananda agar hal serupa tidak terjadi di kemudian hari.
            </p>
            <p class="penutup">
              Demikian surat teguran ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.
            </p>

            <table class="ttd">
              <tr>
                <td></td>
                <td>
                  <span class="sync-tanggal_ttd"></span><br />
                  Kepala Sekolah
                  <div class="spasi-ttd"></div>
                  <b id="print_nama_kepsek_teguran"></b>
                </td>
              </tr>
            </table>
        </div>

        <!-- Template Surat Pindah -->
        <div id="print-pindah-wrapper" class="d-none">
            <div class="kop">
                <?php if($base64_image): ?>
                    <img src="<?php echo $base64_image; ?>" alt="Kop Surat">
                <?php else: ?>
                    <h2>KOP SURAT SMK TI BALI GLOBAL DENPASAR</h2>
                <?php endif; ?>
            </div>
            
            <h3 style="text-align: center; text-decoration: underline; margin-bottom: 5px;">SURAT KETERANGAN PINDAH SEKOLAH</h3>
            <p style="text-align: center; margin-bottom: 30px;">Nomor: <span class="sync-nomor_surat"></span></p>

            <p class="pembuka">Yang bertanda tangan di bawah ini, Kepala Sekolah menerangkan bahwa:</p>
            
            <table class="detail" style="margin-left: 0; margin-top:20px; margin-bottom: 20px; width: 100%;">
              <tr>
                <td style="width: 200px;">Nama Siswa</td>
                <td>: <b class="sync-nama_siswa"></b></td>
              </tr>
              <tr>
                <td>NIS / Kelas</td>
                <td>: <span class="sync-kelas_nis"></span></td>
              </tr>
              <tr>
                <td>Nama Orang Tua/Wali</td>
                <td>: <span class="sync-ortu"></span></td>
              </tr>
              <tr>
                <td>Sekolah Tujuan</td>
                <td>: <b id="print_sekolah_tujuan_pindah"></b></td>
              </tr>
              <tr>
                <td>Alasan Pindah</td>
                <td>: <span id="print_alasan_pindah"></span></td>
              </tr>
            </table>

            <p class="penutup">
              Telah disetujui pindah/mutasi dari SMK TI Bali Global Denpasar sesuai dengan permohonan dari pihak orang tua/wali murid dengan alasan tersebut di atas. Adapun rekam jejak akademik dan administrasi siswa bersangkutan telah diselesaikan.
            </p>
            <p class="penutup">
              Demikian surat keterangan pindah ini dibuat agar dapat dipergunakan sebagaimana mestinya.
            </p>

            <table class="ttd">
              <tr>
                <td></td>
                <td>
                  <span class="sync-tanggal_ttd"></span><br />
                  Kepala Sekolah
                  <div class="spasi-ttd"></div>
                  <b id="print_nama_kepsek_pindah"></b>
                </td>
              </tr>
            </table>
        </div>
    </div>

    <script>
        const idSiswaSelect = document.getElementById('id_siswa');
        const inputNomorSurat = document.getElementById('nomor_surat');
        const inputTanggalTtd = document.getElementById('tanggal_ttd');
        const templateSuratSelect = document.getElementById('template_surat');

        // Toggle Form Group Visibility
        function changeTemplate() {
            const selected = templateSuratSelect.value;
            
            // Hide all specific forms & print wrappers
            document.getElementById('form-orangtua').classList.add('d-none');
            document.getElementById('form-teguran').classList.add('d-none');
            document.getElementById('form-pindah').classList.add('d-none');
            
            document.getElementById('print-orangtua-wrapper').classList.add('d-none');
            document.getElementById('print-teguran-wrapper').classList.add('d-none');
            document.getElementById('print-pindah-wrapper').classList.add('d-none');

            // Show active form and print wrapper
            document.getElementById('form-' + selected).classList.remove('d-none');
            document.getElementById('print-' + selected + '-wrapper').classList.remove('d-none');
            
            updatePrintTemplate();
        }

        function updatePrintTemplate() {
            // Update common fields everywhere they exist
            document.querySelectorAll('.sync-nomor_surat').forEach(el => el.textContent = inputNomorSurat.value || '-');
            document.querySelectorAll('.sync-tanggal_ttd').forEach(el => el.textContent = inputTanggalTtd.value || '-');

            const selectedOption = idSiswaSelect.options[idSiswaSelect.selectedIndex];
            let ortu = 'Bapak / Ibu', nama_siswa = '-', kelas_nis = '- / -';
            
            if (idSiswaSelect.value) {
                ortu = selectedOption.getAttribute('data-ortu') || 'Bapak / Ibu';
                nama_siswa = selectedOption.getAttribute('data-nama') || '-';
                kelas_nis = (selectedOption.getAttribute('data-kelas') || '-') + ' / ' + (selectedOption.getAttribute('data-nis') || '-');
            }
            
            document.querySelectorAll('.sync-ortu').forEach(el => el.textContent = ortu);
            document.querySelectorAll('.sync-nama_siswa').forEach(el => el.textContent = nama_siswa);
            document.querySelectorAll('.sync-kelas_nis').forEach(el => el.textContent = kelas_nis);

            // Update specific fields based on classes dynamically
            document.querySelectorAll('.input-sync').forEach(input => {
                const targetId = 'print_' + input.id;
                const targetEl = document.getElementById(targetId);
                if(targetEl) {
                    targetEl.textContent = input.value || '-';
                }
            });
        }

        // Attach listeners
        inputNomorSurat.addEventListener('input', updatePrintTemplate);
        inputTanggalTtd.addEventListener('input', updatePrintTemplate);
        idSiswaSelect.addEventListener('change', updatePrintTemplate);
        
        document.querySelectorAll('.input-sync').forEach(input => {
            input.addEventListener('input', updatePrintTemplate);
        });
        
        function cetakSurat() {
            if (!idSiswaSelect.value) {
                alert("Silakan pilih siswa terlebih dahulu.");
                idSiswaSelect.focus();
                return;
            }
            updatePrintTemplate();
            window.print();
        }
        
        // Initialize once
        changeTemplate();
    </script>
    </div> <!-- End container -->
</body>
</html>