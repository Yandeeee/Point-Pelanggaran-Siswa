<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>APP POINT PELANGGARAN SISWA/I</title>
    <link rel="stylesheet" href="asset/css/style.css">
</head>
<body>
<!-- Form Login -->
<div class="login-container">
    <div class="login-box">
        <img src=asset/gambar/images.png alt="Logo">
        <h3>SISTEM POINT PELANGGARAN SISWA<br><span><small><font color="grey">SMKS TI BALI GLOBAL DENPASAR</font></small></span></h3>

        <form action="verifikasi/proses_login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            
            <label style="display: flex; align-items: center; justify-content: flex-start; margin-top: 10px; font-size: 13px; color: #555; cursor: pointer;">
                <input type="checkbox" id="showPassword" style="width: auto; margin-top: 0; margin-right: 8px; padding: 0;"> Tampilkan Password
            </label>

            <select name="role" required>
                <option value="">Masuk Sebagai</option>
                <option value="admin">Admin</option>
                <option value="kepala_sekolah">Kepala Sekolah</option>
                <optgroup label="Guru">
                    <option value="guru">Guru Mapel</option>
                    <option value="guru_bk">Guru BK</option>
                </optgroup>
                <option value="siswa">Siswa</option>
            </select>
           <button type="submit">Log In</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('showPassword').addEventListener('change', function() {
        var pwdInput = document.getElementById('password');
        if (this.checked) {
            pwdInput.type = 'text';
        } else {
            pwdInput.type = 'password';
        }
    });
</script>

</body>
</html>
