<?php
// Sprawdzamy, czy dane zostały przesłane za pomocą metody POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Odbieramy dane z formularza
    $imie = $_POST["imie"];
    $nazwisko = $_POST["nazwisko"];
    $email = $_POST["email"];
    $tel = $_POST["tel"];
    $dataprzyjazdu = $_POST['dataprzyjazdu'];
    $datawyjazdu = $_POST['datawyjazdu'];

    // Zapisujemy dane w sesji
    session_start();
    $_SESSION['imie'] = $imie;
    $_SESSION['nazwisko'] = $nazwisko;
    $_SESSION['email'] = $email;
    $_SESSION['tel'] = $tel;
    $_SESSION['dataprzyjazdu'] = $dataprzyjazdu;
    $_SESSION['datawyjazdu'] = $datawyjazdu;
}

// Połączenie z bazą danych
$host = 'localhost';
$dbname = 'rycerska';
$username = 'root';
$password = 'Ariel';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Połączono z bazą danych.";
} catch (PDOException $e) {
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
}

// Sprawdzenie czy formularz został wysłany metodą POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataprzyjazdu = $_POST['dataprzyjazdu'];
    $datawyjazdu = $_POST['datawyjazdu'];

    // Sprawdzenie czy data jest dostępna
    $stmt = $pdo->prepare("SELECT * FROM rezerwacja WHERE dataprzyjazdu <= :datawyjazdu AND datawyjazdu >= :dataprzyjazdu");
    $stmt->execute(['dataprzyjazdu' => $dataprzyjazdu, 'datawyjazdu' => $datawyjazdu]);
    $wynik = $stmt->fetch();

    if ($wynik) {
        // Data zajęta, wyświetl komunikat
        echo '<script>alert("Data rezerwacji jest już zajęta. Proszę wybrać inną datę.");</script>';
    } else {
        // Data dostępna, zapisz rezerwację do bazy danych

        // Utwórz nową tabelę na dane rezerwacji
        $createTable = "CREATE TABLE IF NOT EXISTS nowa_tabela (
            id INT AUTO_INCREMENT PRIMARY KEY,
            imie VARCHAR(50),
            nazwisko VARCHAR(50),
            email VARCHAR(100),
            telefon VARCHAR(15),
            dataprzyjazdu DATE,
            datawyjazdu DATE
        )";
        $pdo->exec($createTable);

        // Zapisz dane w nowej tabeli
        $stmt = $pdo->prepare("INSERT INTO goscie(imie, nazwisko, email, telefon ) 
                       VALUES (:imie, :nazwisko, :email, :telefon)");
        $stmt->execute([
            'imie' => $_SESSION['imie'],
            'nazwisko' => $_SESSION['nazwisko'],
            'email' => $_SESSION['email'],
            'telefon' => $_SESSION['tel'],
            'dataprzyjazdu' => $dataprzyjazdu,
            'datawyjazdu' => $datawyjazdu,
        ]);

        // Wyświetl komunikat potwierdzający rezerwację
        echo '<script>alert("Rezerwacja została dokonana. Dziękujemy!");</script>';
    }
}
?>
<?php
// Połączenie z bazą danych
$host = 'localhost';
$dbname = 'hotel';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Połączono z bazą danych.";
} catch (PDOException $e) {
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
}

// Sprawdzenie czy formularz został wysłany metodą POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nazwa_pokoju = $_POST['gdanska'];
    $nazwa_pokoju = $_POST['rycerska'];
    $data_przyjazdu = $_POST['data_przyjazdu'];
    $data_wyjazdu = $_POST['data_wyjazdu'];

    // Sprawdzenie czy pokój jest dostępny w podanych terminach
    $stmt = $pdo->prepare("SELECT * FROM rezerwacje WHERE rycerska = :rycerska AND 
                          (:data_przyjazdu BETWEEN data_przyjazdu AND data_wyjazdu OR 
                          :data_wyjazdu BETWEEN data_przyjazdu AND data_wyjazdu)");
    $stmt->execute(['gdanska' => $nazwa_pokoju, 'data_przyjazdu' => $data_przyjazdu, 'data_wyjazdu' => $data_wyjazdu]);
    $stmt->execute(['rycerska' => $nazwa_pokoju, 'data_przyjazdu' => $data_przyjazdu, 'data_wyjazdu' => $data_wyjazdu]);
    $wynik = $stmt->fetch();

    if ($wynik) {
        // Pokój zajęty, wyświetl komunikat
        echo '<script>alert("Pokój o tej nazwie jest już zarezerwowany w podanym terminie. Proszę wybrać inny termin lub inny pokój.");</script>';
    } else {
        // Pokój dostępny, zapisz rezerwację do bazy danych
        $stmt = $pdo->prepare("INSERT INTO rezerwacje (nazwa_pokoju, data_przyjazdu, data_wyjazdu) 
                               VALUES (:nazwa_pokoju, :data_przyjazdu, :data_wyjazdu)");
        $stmt->execute(['nazwa_pokoju' => $nazwa_pokoju, 'data_przyjazdu' => $data_przyjazdu, 'data_wyjazdu' => $data_wyjazdu]);
      
        // Wyświetl komunikat potwierdzający rezerwację
        echo '<script>alert("Rezerwacja została dokonana. Dziękujemy!");</script>';
    }
}
?>

<!-- Formularz rezerwacji pokoju -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Nazwa pokoju: <input type="text" name="nazwa_pokoju"><br>
    Data przyjazdu: <input type="date" name="data_przyjazdu"><br>
    Data wyjazdu: <input type="date" name="data_wyjazdu"><br>
    <input type="submit" value="Zarezerwuj">
</form>
 
