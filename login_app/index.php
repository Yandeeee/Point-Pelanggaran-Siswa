<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>APP POINT PELANGGARAN SISWA/I</title>
    <link rel="stylesheet" href="asset/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<!-- Form Login -->
<div class="login-container">
    <div class="login-box">
        <img src=asset/gambar/images.png alt="Logo">
        <h3>SISTEM POINT PELANGGARAN SISWA<br><span><small><font color="grey">SMKS TI BALI GLOBAL DENPASAR</font></small></span></h3>

        <form action="verifikasi/proses_login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required style="box-sizing: border-box;">
            <div style="position: relative; margin-top: 12px; width: 100%; box-sizing: border-box;">
                <input type="password" id="password" name="password" placeholder="Password" required style="margin-top: 0; box-sizing: border-box; padding-right: 40px;">
                <i class="fas fa-eye" id="togglePassword" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888; font-size: 16px;"></i>
            </div>

            <select name="role" required style="box-sizing: border-box;">
                <option value="">Masuk Sebagai</option>
                <option value="admin">Admin</option>
                <optgroup label="Guru">
                    <option value="kepala_sekolah">Kepala Sekolah</option>
                    <option value="waka_kesiswaan">Waka Kesiswaan</option>
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
    document.getElementById('togglePassword').addEventListener('click', function() {
        var pwdInput = document.getElementById('password');
        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            pwdInput.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });
</script>

</body>
</html>
