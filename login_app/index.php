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
        <img src=https://smktibaliglobalsingaraja.sch.id/wp-content/uploads/2022/12/Logo-PNG-Tanpa-Tulisan-300x300-1.png alt="Logo">
        <h3>SISTEM POINT PELANGGARAN SISWA<br><span><h5><font color="grey">SMK TI BALI GLOBAL DENPASAR</font></h5></span></h3>

        <form action="verifikasi/proses_login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <select name="role" required>
                <option value="">Masuk Sebagai</option>
                <option value="admin">Admin</option>
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

</body>
</html>
