<script type="text/javascript" src="../js/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.7.custom.min.js"></script>
<script>
    $(document).ready(function() {
        $("#sortable").sortable
                ({
                    update: function(event, ui)
                    {
                        var array_sort = $("#sortable").sortable("toArray");
                        for (var i = 0; i < array_sort.length; i++)
                        {
                            var destino = "salva_pos.php?id=" + array_sort[i] + "&pos=" + i + "&tbl=<?= $tabela ?>";
                            //alert('Enviando....'+destino);
                            $.post(destino);
                        }
                        //$("#carrega").load('edita_ordem.php');
                    }
                });
    });
</script>
<?php
require_once("inc_database.php");

$qry_banner_ord = mysql_query('Select * from ' . $tabela . ' ' . $leftjoin . ' order by ordem');

echo '<ul id="sortable">';
while ($row_banner_ord = mysql_fetch_assoc($qry_banner_ord)) {
    if ($tabela == 'tbl_destaque') {
        $sql_temp = 'select ta.*, tus.nome as nome_user,tus.email as email_user from tbl_anuncio_' . $row_banner_ord['tp_anuncio'] . ' ta left join tbl_usuarios_site tus on ta.id_usuario = tus.id where ta.id = ' . $row_banner_ord['id_anuncio'];
        $qry_temp = mysql_query($sql_temp) or die($sql_temp);
        $row_temp = mysql_fetch_assoc($qry_temp);
        echo '<li id="' . $row_banner_ord['id'] . '" style="display:block;margin-top:5px;border:solid 2px #CCC;cursor:move;width:99%">' . utf8_encode($row_temp['nome_user']) . ' - ' . utf8_encode($row_temp['email_user']) . ' - ' . $row_banner_ord['tp_anuncio'] . '</li>';
    } else {
        echo '<li id="' . $row_banner_ord['id'] . '" style="display:block;margin-top:5px;border:solid 2px #CCC;cursor:move;width:99%">' . utf8_encode($row_banner_ord[$cmp_info1]) . ' - ' . utf8_encode($row_banner_ord[$cmp_info2]) . '</li>';
    }
}
echo '</ul>';
?>
<div id="teste" style="display:none;"></div>