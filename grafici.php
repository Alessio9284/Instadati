<?php

    /**
     * @author Alessio Torricelli
     */
    
    /* -.-.-.-.-.-.- GRAFICI -.-.-.-.-.-.-*/

    /* 1) VISUALIZZA GRAFICO DEI LIKE E DEI COMMENTI DEGLI ULTIMI POST */
    function visualizzaGraficoLikeCommenti($dati)
    {
        $i = 0;

        echo "<script>function graficoLikeCommenti(){ var dati = google.visualization.arrayToDataTable([['', 'Likes', 'Commenti']";

        foreach ($dati as $media)
        {
            echo ",['Post ".++$i."', ".$media->likes->count.", ".$media->comments->count."]";
        }

        echo "]); var chart = new google.visualization.AreaChart(document.getElementById('tabellaGenerale'));
        chart.draw(dati, { title: 'Tabella dei Likes e dei Commenti per Post', hAxis: {title: 'Ultimi Post',  titleTextStyle: {color: '#333'}}, vAxis: {minValue: 0}, colors: ['blue', 'red']});}</script>";
    }

    /* 2) VISUALIZZA IL GRAFICO DELLE PAROLE PIU' USATE */
    function visualizzaGraficoParole($dati, $numero)
    {
        echo "<script>function graficoParoleUsate(){ var data = google.visualization.arrayToDataTable([['', '']";

        $frasi = array();
        $i = 1;

        foreach ($dati as $media)
        {
            array_push($frasi, $media->caption->text);
        }

        $chiavi = ricercaOrdinamento($frasi);

        foreach ($chiavi as $parola => $valore)
        {
            echo ",['$parola', $valore]";
            if($i == $numero) break;
            $i++;
        }

        echo "]);var chart = new google.visualization.PieChart(document.getElementById('paroleUsate'));
        chart.draw(data, {title: 'Parole più usate', is3D: true});}</script>";
    }

    /* 3) VISUALIZZA GRAFICO DELLA MEDIA DEI LIKE */
    function visualizzaGraficoMediaLike($date, $medieLikes)
    {
        echo "<script>function graficoMediaLike(){ var data = google.visualization.arrayToDataTable([['', 'Likes']";

        $i = 0;

        foreach ($medieLikes as $mediaLike)
        {
            echo ",['$date[$i]', $mediaLike]";
            $i++;
        }

        echo "]); var chart = new google.visualization.ComboChart(document.getElementById('mediaLike'));
        chart.draw(data, {title: 'Media dei Likes totali', colors: ['blue']});}</script>";
    }

    /* 4) VISUALIZZA GRAFICO DELLA MEDIA DEI COMMENTI */
    function visualizzaGraficoMediaCommenti($date, $medieCommenti)
    {
        echo "<script>function graficoMediaCommenti(){ var data = google.visualization.arrayToDataTable([['', 'Commenti']";

        $i = 0;

        foreach ($medieCommenti as $mediaCommenti)
        {
            echo ",['$date[$i]', $mediaCommenti]";
            $i++;
        }

        echo "]);var chart = new google.visualization.ComboChart(document.getElementById('mediaCommenti'));
        chart.draw(data, {title: 'Media dei Commenti totali', colors: ['red']});}</script>";
    }

    /* 5) VISUALIZZA LA PREVISIONE DELL'ANDAMENTO DEI LIKE */
    function visualizzaGraficoAndamentoLike($date, $medieLikes, $giorni)
    {
        echo "<script>function graficoPrevisioneLike(){ var data = google.visualization.arrayToDataTable([['', 'Likes']";

        $media = (trovaMin($medieLikes) + trovaMax($medieLikes)) / 2;
        $numMedie = count($medieLikes);
        $ultimo = $medieLikes[$numMedie - 1];
        $andamento = ($ultimo - $media) / 10;

        for($x = 0; $x < $numMedie; $x++)
        {
            echo ",['$date[$x]', $medieLikes[$x]]";
        }

        for($y = 1; $y <= $giorni; $y++)
        {
            $dataFutura = date('Y-m-d h:i:s', strtotime('+'. $y .' days'));
            
            if($andamento < -0.09 || $andamento > 0.09)
            {
                $ultimo += $andamento;
                echo ",['$dataFutura', ". $ultimo ."]";
            }
            else
            {
                echo ",['$dataFutura', $ultimo]";
            }
        }

        echo "]);var chart = new google.visualization.LineChart(document.getElementById('previsioneLike'));
        chart.draw(data, {title: 'Previsione dei Like', colors: ['blue'], curveType: 'function'});}</script>";
    }

    /* 6) VISUALIZZA LA PREVISIONE DELL'ANDAMENTO DEI COMMENTI */
    function visualizzaGraficoAndamentoCommenti($date, $medieCommenti, $giorni)
    {
        echo "<script>function graficoPrevisioneCommenti(){ var data = google.visualization.arrayToDataTable([['', 'Commenti']";

        $media = (trovaMin($medieCommenti) + trovaMax($medieCommenti)) / 2;
        $numMedie = count($medieCommenti);
        $ultimo = $medieCommenti[$numMedie - 1];
        $andamento = ($ultimo - $media) / 10;

        for($x = 0; $x < $numMedie; $x++)
        {
            echo ",['$date[$x]', $medieCommenti[$x]]";
        }

        for($y = 1; $y <= $giorni; $y++)
        {
            $dataFutura = date('Y-m-d h:i:s', strtotime('+'. $y .' days'));
            
            if($andamento < -0.09 || $andamento > 0.09)
            {
                $ultimo += $andamento;
                echo ",['$dataFutura', ". $ultimo ."]";
            }
            else
            {
                echo ",['$dataFutura', $ultimo]";
            }
        }

        echo "]);var chart = new google.visualization.LineChart(document.getElementById('previsioneCommenti'));
        chart.draw(data, {title: 'Previsione dei Commenti', colors: ['red'], curveType: 'function'});}</script>";
    }
    /* -.-.-.-.-.-.- FINE GRAFICI -.-.-.-.-.-.- */

    /* -.-.-.-.-.-.- SOTTOFUNZIONI -.-.-.-.-.-.- */
    function ricercaOrdinamento($frasi)
    {
        $parole = array();

        foreach ($frasi as $frase)
        {
            $frase = preg_replace("/[^a-zA-Z\ ]/", "", $frase);

            $parole = array_merge($parole, explode(" ", $frase));
        }

        $parole = array_count_values(array_map("strtolower", $parole));
        arsort($parole);

        return rimozioneNonParole($parole);
    }

    function rimozioneNonParole($parole)
    {
        $schifezze = array("il", "lo", "la", "i", "gli", "le", "del", "dello", "dei", "degli", "della", "delle", "al", "allo", "ai", "agli", "alla", "alle", "dal", "dallo", "dai", "dagli", "dalla", "dalle", "nel", "nello", "nei", "negli", "nella", "nelle", "sul", "sullo", "sui", "sugli", "sulla", "sulle", "per", "con", "tra", "fra", "di", "a", "da", "in", "su", "e", "che", "un", "una", "sono", "si", "è", "é", "mio", "tuo", "suo", "nostro", "vostro", "loro", "no", "mi", "ma", "me", "più", "così", "non", "ho", "ci", "abbiamo", "se", "so", "questo", "questi", "queste", "quelle", "quelli", "quelle", "quei", "io", "tu", "lui", "lei", "egli", "noi", "voi", "essi", "cui", "anche", "fa", "ti", "pi", "cos", "fu", "");

        foreach ($schifezze as $roba) unset($parole[$roba]);

        return $parole;
    }
    
    function trovaMax($arr)
    {
        $max = $arr[0];
        for ($i = 0; $i< count($arr); $i++)
        {
            if ($max < $arr[$i]) $max = $arr[$i];
        }
        return $max;
    }
         
    function trovaMin($arr)   
    {   
        $min = $arr[0];
        for ($i = 0; $i< count($arr); $i++)
        {
            if ($min>$arr[$i]) $min=$arr[$i];
        }
        return $min;
    }
    /* -.-.-.-.-.-.- FINE SOTTOFUNZIONI -.-.-.-.-.-.- */
?>