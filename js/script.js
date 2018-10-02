/**
 * @author Alessio Torricelli
 */

window.onload = function()
{
  /* CARICAMENTO GRAFICI */
  google.charts.load('current', {'packages':['corechart']});

  google.charts.setOnLoadCallback(graficoLikeCommenti);
  google.charts.setOnLoadCallback(graficoParoleUsate);

  google.charts.setOnLoadCallback(graficoMediaCommenti);
  google.charts.setOnLoadCallback(graficoMediaLike);

  google.charts.setOnLoadCallback(graficoPrevisioneLike);
  google.charts.setOnLoadCallback(graficoPrevisioneCommenti);

  /* EFFETTO SCROLL ALLE FOTO O VIDEO */
  $('li').hover(
    function()
    {
        var $media = $(this).find('.media');
        var height = $media.height();
        $media.stop().animate({marginTop: -(height)}, 900);
    },
    function()
    {
        var $media = $(this).find('.media');
        $media.stop().animate({marginTop: '0px'}, 900);
    }
  );
}