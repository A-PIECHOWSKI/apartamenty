<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $host="localhost";
    $nazwa="anita";
    $user="root";
    $pass="Ariel";
    $kodowanie="utf8";
    $polaczenie="mysql:host=$host;dbanme=$$nazwa;charset=$kodowanie";
    try {
        $polacz=new PDO($polaczenie,$user,$pass);
    } catch (\Throwable $th) {
        //throw $th;
    }
    $zap="SELECT pracownicy.Imie, pracownicy.Nazwisko FROM pracownicy;";
    ///do zmiennej dane zapisz wynik zapytania
    $dane = $polacz->query($zap);
    //zmienna dane jest juz obiektem a by ja wyswietlic wykonujemy metode fetch
    //fetch zwraca tablice asocjacyjna
    //fetch pobiera kolejny rekord z tabeli 
    while( $rekord=$dane->fetch() ){
        echo "<p> {$rekord['Imie']} {$rekord['Nazwisko']} </p>";
    }



    ?>






</body>
</html>