<?php
// Veritabanı bağlantı bilgileri (Örnek)
$servername = "localhost:3306";
$username = "root";
$password = "1234";
$dbname = "aigenerated_analysis";

// Bağlantı kur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantı hatası kontrolü
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Hazırlanmış sorgu (SQL injection önleme)
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Şifre doğrulama (password_verify kullanılması önerilir)
        if (password_verify($password, $row["password"])) {
            session_start();
            $_SESSION["user_id"] = $row["id"]; // Örnek oturum değişkeni
            header("Location: ana_sayfa.php");
            exit();
        } else {
            echo "Yanlış şifre.";
        }
    } else {
        echo "Kullanıcı bulunamadı.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Giriş Yap</title>
</head>
<body>
    <h2>Giriş Yap</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Kullanıcı Adı: <input type="text" name="username"><br>
        Şifre: <input type="password" name="password"><br>
        <input type="submit" value="Giriş">
    </form>
</body>
</html>