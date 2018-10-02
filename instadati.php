<?php

    /**
     * Instagram PHP API
     *
     * @link https://github.com/cosenary/Instagram-PHP-API
     * @author Christian Metz
     * @since 01.10.2013
     *
     * @author Alessio Torricelli
     */

    require "funzioni.php";
    require "grafici.php";

    $connessione = creaConnessione();
    $arrayDati = datiUtente(20);
    $tot = medieLikeCommentiTotali($arrayDati);
    
    $arr = array();
    $date = array();
    $medieLike = array();
    $medieCommenti = array();

    if ($connessione->query("INSERT INTO dati (mediaLike, mediaCommenti) VALUES ($tot[0], $tot[1]);"))
    { 
        $conteggio = mysqli_num_rows($connessione->query("SELECT * FROM dati")) + 1;

        for($i = 1; $i < $conteggio; $i++)
        {
            $arr = mysqli_fetch_array($connessione->query("SELECT data, mediaLike, mediaCommenti FROM dati WHERE id = '$i'"));

            array_push($date, $arr["data"]);
            array_push($medieLike, $arr["mediaLike"]);
            array_push($medieCommenti, $arr["mediaCommenti"]);
        }
    }
    else
    {
        echo "<!DOCTYPE html><html><head><title>Errore Server</title></head><body><h1>Errore Server!</h1></body></html>";
        header("refresh:5;url=index.php");
    }
?>
<!DOCTYPE html>
<html lang="it-IT">
    <head>
        <title>InstaDati</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- CSS VIDEO -->
        <link href="https://vjs.zencdn.net/4.2/video-js.css" rel="stylesheet" type="text/css">
        <!-- STILE PAGINA -->
        <link href="css/style.css" rel="stylesheet" type="text/css">
        <!-- GRAFICI API GOOGLE -->
        <script src="https://www.gstatic.com/charts/loader.js"></script>
        <!-- JS VIDEO -->
        <script src="https://vjs.zencdn.net/4.2/video.js"></script>
        <!-- JQUERY -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <!-- FUNZIONI GRAFICI -->
        <script src="js/script.js"></script>
    </head>
    <body id="sfondo">
        <header class="clearfix">
            <img src="img/instagram.png" alt="Instagram logo">
            <h1>Account di <?php echo $arrayDati[0]->user->username; ?></h1>
        </header>
        <main>
            <table>
                <tr>
                    <td><div id="tabellaGenerale"></div></td>
                </tr>
					<tr>
                    <td><div id="paroleUsate"></div></td>
                </tr>
                <tr>
                    <td><div id="mediaLike"></div></td>
                </tr>
                <tr>
                    <td><div id="mediaCommenti"></div></td>
                </tr>
                <tr>
                    <td><div id="previsioneLike"></div></td>
                </tr>
                <tr>
                    <td><div id="previsioneCommenti"></div></td>
                </tr>
                <tr id="bianco">
                    <td>
                        <ul class="grid">
                            <?php visualizzaGraficoLikeCommenti($arrayDati); ?>
                            <?php visualizzaGraficoParole($arrayDati, 10); ?>
                            <?php visualizzaGraficoMediaLike($date, $medieLike); ?>
                            <?php visualizzaGraficoMediaCommenti($date, $medieCommenti); ?>
                            <?php visualizzaGraficoAndamentoLike($date, $medieLike, 10); ?>
                            <?php visualizzaGraficoAndamentoCommenti($date, $medieCommenti, 10); ?>

                            <h1>Foto e Video Instagram</h1>
                            <?php visualizzaFotoVideo($arrayDati); ?>
                        </ul>
                    </td>
                </tr>
            </table>
        </main>
    </body>
</html>