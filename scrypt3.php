<?php
// Sprawdzamy, czy dane zostały przesłane za pomocą metody POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Odbieramy dane z formularza
    $Imie = $_POST["imie"];
    $Nazwisko = $_POST["nazwisko"];
    $email = $_POST["email"];
    $tel = $_POST["tel"];
    $dataprzyjazdu = $_POST['dataprzyjazdu'];
    $datawyjazdu = $_POST['datawyjazdu'];
    $wybranyApartament = $_POST['wyborApartamentu'];
}

// Dane dostępowe do bazy danych
$host = "localhost";
$nazwa = "apartments";
$user = "root";
$pass = "Ariel";
$kodowanie = "utf8";
$polaczenie = "mysql:host=$host;dbname=$nazwa;charset=$kodowanie";

try {
    // Nawiązanie połączenia z bazą danych
    $polacz = new PDO($polaczenie, $user, $pass);
    // Ustawienie trybu wyświetlania błędów
    $polacz->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Obsługa błędu połączenia z bazą danych
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
}

// Sprawdzenie wybranego apartamentu i zapisanie danych do odpowiedniej tabeli
if ($wybranyApartament === 'gdanska') {
    // Sprawdzenie, czy data przyjazdu jest już zajęta
    $sql = "SELECT * FROM gdanska WHERE dataprzyjazdu = :dataprzyjazdu";
    $stmt = $polacz->prepare($sql);
    $stmt->bindParam(':dataprzyjazdu', $dataprzyjazdu);
    $stmt->execute();
    $wynik = $stmt->fetch();

    if ($wynik) {
        // Jeżeli data jest zajęta, wyświetl komunikat
        echo "Apartament zajęty, spróbuj inny termin.";
    } else {
        // Jeżeli data jest dostępna, zapisz dane do tabeli 'gdanska'
        $sql = "INSERT INTO gdanska (Imie, Nazwisko, email, tel, dataprzyjazdu, datawyjazdu) 
                VALUES (:Imie, :Nazwisko, :email, :tel, :dataprzyjazdu, :datawyjazdu)";
        $stmt = $polacz->prepare($sql);
        $stmt->bindParam(':Imie', $Imie);
        $stmt->bindParam(':Nazwisko', $Nazwisko);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':tel', $tel);
        $stmt->bindParam(':dataprzyjazdu', $dataprzyjazdu);
        $stmt->bindParam(':datawyjazdu', $datawyjazdu);
        
        // Wykonanie zapytania
        try {
            $stmt->execute();
            echo "Rezerwacja zapisana pomyślnie.";
        } catch (PDOException $e) {
            echo "Błąd zapisu danych: " . $e->getMessage();
        }
    }
} elseif ($wybranyApartament === 'rycerska') {
    // Sprawdzenie, czy data przyjazdu jest już zajęta dla apartamentu "Rycerska"
    $sql = "SELECT * FROM rycerska WHERE dataprzyjazdu = :dataprzyjazdu";
    $stmt = $polacz->prepare($sql);
    $stmt->bindParam(':dataprzyjazdu', $dataprzyjazdu);
    $stmt->execute();
    $wynik = $stmt->fetch();

    if ($wynik) {
        // Jeżeli data jest zajęta, wyświetl komunikat
        echo "Apartament zajęty, spróbuj inny termin.";
    } else {
        // Jeżeli data jest dostępna, zapisz dane do tabeli 'rycerska'
        $sql = "INSERT INTO rycerska (Imie, Nazwisko, email, tel, dataprzyjazdu, datawyjazdu) 
                VALUES (:Imie, :Nazwisko, :email, :tel, :dataprzyjazdu, :datawyjazdu)";
        $stmt = $polacz->prepare($sql);
        $stmt->bindParam(':Imie', $Imie);
        $stmt->bindParam(':Nazwisko', $Nazwisko);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':tel', $tel);
        $stmt->bindParam(':dataprzyjazdu', $dataprzyjazdu);
        $stmt->bindParam(':datawyjazdu', $datawyjazdu);

        // Wykonanie zapytania
        try {
            $stmt->execute();
            echo "Rezerwacja zapisana pomyślnie.";
        } catch (PDOException $e) {
            echo "Błąd zapisu danych: " . $e->getMessage();
        }
    }
}


