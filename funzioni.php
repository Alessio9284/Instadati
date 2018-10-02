<?php

    /**
     * @author Alessio Torricelli
     */
    
    require 'instagramAPI/Instagram.php';
    use MetzWeb\Instagram\Instagram;

    /* ESTRAPOLAZIONE FOTO E VIDEO DELL'UTENTE E INFORMAZIONI AGGIUNTIVE */
    function datiUtente($numeroMedia)
    {
        $arrDati = array();
        $instagram = new Instagram(array(
            'apiKey' => 'a06c9c3906ab416f9ecb0c7a65dbe6f4',
            'apiSecret' => '6e17d9f455d447ac99de133f8fe3ed91',
            'apiCallback' => 'http://localhost/instadati/instadati.php'
        ));

        $code = $_GET['code'];

        if (isset($code))
        {
            $dati = $instagram->getOAuthToken($code);

            $instagram->setAccessToken($dati);

            $result = $instagram->getUserMedia("self", $numeroMedia);

            foreach ($result->data as $media)
                array_push($arrDati, $media);

            return $arrDati;
        }
        else
        {
            if (isset($_GET['error']))
            {
                echo 'An error occurred: ' . $_GET['error_description'];
            }
        }
    }

    function creaConnessione()
    {
        /* CREDENZIALI DATABASE */
        $servername = "localhost";
        $email = "root";
        $password = "";
        $dbName = "instagram";

        /* CREAZIONE CONNESSIONE */
        $con = new mysqli($servername, $email, $password, $dbName);

        if ($con->connect_error)
        {
          die('Errore: ' . $con->connect_errno . '-> ' . $con->connect_error);
        }
        else
        {
            return $con;
        }
    }

    /* CALCOLO MEDIA DI LIKE E COMMENTI */
    function medieLikeCommentiTotali($dati)
    {
        $totLikes = 0;
        $totCommenti = 0;

        foreach ($dati as $media)
        {
            $totLikes += $media->likes->count;
            $totCommenti += $media->comments->count;
        }

        return [$totLikes/count($dati), $totCommenti/count($dati)];
    }

    /* STAMPA FOTO E VIDEO (CON COMMENTI, LIKE) */
    function visualizzaFotoVideo($dati)
    {
        foreach($dati as $media)
        {
            $contenuto = '<li>';

            if ($media->type === 'video')
            {
                $poster = $media->images->low_resolution->url;
                $source = $media->videos->standard_resolution->url;
                $contenuto .= "<video class='media video-js vjs-default-skin' width='250' height='250' poster='{$poster}' data-setup='{'controls':true, 'preload': 'auto'}'><source src='{$source}' type='video/mp4' /></video>";
            }
            else
            {
                $image = $media->images->low_resolution->url;
                $contenuto .= "<img class='media' src='{$image}'/>";
            }

            $avatar = $media->user->profile_picture;
            $username = $media->user->username;
            $commento = $media->caption->text;
            $contenuto .= "<div class='content'><div class='avatar' style='background-image: url({$avatar})'></div><p>{$username}</p><div class='comment'>{$commento}</div></div>";

            echo $contenuto . '</li>';
        }
    }
?>