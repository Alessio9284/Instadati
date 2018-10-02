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

    require 'instagramAPI/Instagram.php';

    use MetzWeb\Instagram\Instagram;

    // Istanza di Instagram
    $instagram = new Instagram(
        array(
            'apiKey' => 'a06c9c3906ab416f9ecb0c7a65dbe6f4',
            'apiSecret' => '6e17d9f455d447ac99de133f8fe3ed91',
            'apiCallback' => 'http://localhost/instadati/instadati.php'
        )
    );

    // Creazione accesso alle API di Instagram
    $loginUrl = $instagram->getLoginUrl();

?>
<!DOCTYPE html>
<html lang="it-IT">
    <head>
        <title>Instagram Login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- STILE PAGINA -->
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <header class="clearfix">
            <h1>Instagram <span>display your photo stream</span></h1>
        </header>
        <main>
            <ul class="grid">
                <li><img src="img/instagram-big.png" alt="Instagram logo"></li>
                <li>
                    <a class="login" href="<?php echo $loginUrl ?>">» Login with Instagram</a>
                    <h4>Use your Instagram account to login.</h4>
                </li>
            </ul>
        </main>
    </body>
</html>
