<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//Descomentar a linha abaixo caso problemas com uploads grandes
//ini_set("memory_limit","50M");


function TestImage($arquivo, $larguraMax, $alturaMax, $destino, $nome_destino) {
    //----------------------------------------------------------------
    // Crop-to-fit PHP-GD
    // Revision 2 [2009-06-01]
    // Corrected aspect ratio of the output image
    //----------------------------------------------------------------

    $source_path = $arquivo['tmp_name'];


    list( $source_width, $source_height, $source_type ) = getimagesize($source_path);

    switch ($source_type) {
        case IMAGETYPE_GIF:
            $source_gdim = imagecreatefromgif($source_path);
            break;

        case IMAGETYPE_JPEG:
            $source_gdim = imagecreatefromjpeg($source_path);
            break;

        case IMAGETYPE_PNG:
            $source_gdim = imagecreatefrompng($source_path);
            break;
    }

    $source_gdim = imagetranstowhite($source_gdim);

    $source_aspect_ratio = $source_width / $source_height;
    $desired_aspect_ratio = $larguraMax / $alturaMax;

    if ($source_aspect_ratio > $desired_aspect_ratio) {
        $temp_height = $alturaMax;
        $temp_width = (int) ( $alturaMax * $source_aspect_ratio );
    } else {
        $temp_width = $larguraMax;
        $temp_height = (int) ( $larguraMax / $source_aspect_ratio );
    }

    //
    // Resize the image into a temporary GD image
    //

  $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
    imagecopyresampled(
            $temp_gdim, $source_gdim, 0, 0, 0, 0, $temp_width, $temp_height, $source_width, $source_height
    );

    //
    // Copy cropped region from temporary image into the desired GD image
    //

    $x0 = ( $temp_width - $larguraMax ) / 2;
    $y0 = ( $temp_height - $alturaMax ) / 2;

    $desired_gdim = imagecreatetruecolor($larguraMax, $alturaMax);
    imagecopy(
            $desired_gdim, $temp_gdim, 0, 0, $x0, $y0, $larguraMax, $alturaMax
    );

    //
    // Render the image
    // Alternatively, you can save the image in file-system or database
    //

    //header('Content-type: image/jpeg');
    //imagejpeg($desired_gdim);

    imagejpeg($desired_gdim, $destino . $nome_destino, 100);

    //
    // Add clean-up code here
//
}

function imagetranstowhite($trans) {
    // Create a new true color image with the same size
    $w = imagesx($trans);
    $h = imagesy($trans);
    $white = imagecreatetruecolor($w, $h);

    // Fill the new image with white background
    $bg = imagecolorallocate($white, 255, 255, 255);
    imagefill($white, 0, 0, $bg);

    // Copy original transparent image onto the new image
    imagecopy($white, $trans, 0, 0, 0, 0, $w, $h);
    return $white;
}

function imagegen($arquivo, $larguraMax, $alturaMax, $destino, $nome_destino) {
    TestImage($arquivo, $larguraMax, $alturaMax, $destino, $nome_destino);
}

function gera_sql($tabela, $acao, $info) {
    global $thumb_size;
    global $sys_this_page;

    /* Imagens */
    if (isset($_FILES)) {
        foreach ($_FILES as $key => $value) {
            if ((!empty($value['name'])) && ($value['error'] == 0)) {
                $size = $value['size'];
                $code = substr(md5(rand() . time()), 0, 15) . $counter;
                $ext = strtolower(end(explode('.', $value['name'])));
                $caminho = '../../uploads/' . $sys_this_page . '/';

                //Se nÃ£o existir a pasta uploads
                if (!file_exists('../../uploads/')) {
                    mkdir('../../uploads/');
                }
                //Se nÃ£o existir a pasta desta pÃ¡gina dentro de uploads
                if (!file_exists($caminho)) {
                    mkdir($caminho);
                }

                /* Imagens */
                $imagens = array('jpg', 'jpeg', 'bmp', 'png', 'gif');
                /* ------- */
                if (in_array(strtolower($ext), $imagens)) {
                    imagegen($value, 80, 60, $caminho, $code . "_admin." . $ext);
                    $thumb_count = 1;

                    foreach ($thumb_size as $key2 => $value2) {
                        imagegen($value, $value2[0], $value2[1], $caminho, $code . "_" . $thumb_count . "." . $ext);
                        ++$thumb_count;
                    }
                } else {
                    //die('Not image : ' . $ext);
                    /* Se nÃ£o for imagem apenas move o arquivo upload */
                }

                move_uploaded_file($value['tmp_name'], $caminho . $code . "." . $ext);
                $arquivo = $code . "." . $ext;
            }
            if (!empty($arquivo)) {
                if (false !== strpos($key, '_file')) {
                    $uploads[str_replace('_file', '', $key)] = $arquivo;
                } else if ($key == 'imagem_file') {
                    $uploads['imagem'] = $arquivo;
                } else if ($key == 'imagem_destaque_file') {
                    $uploads['imagem_destaque'] = $arquivo;
                } else if ($key == 'arquivo_file') {
                    $uploads['arquivo'] = $arquivo;
                } else if (in_array($ext, array('jpg', 'jpeg', 'gif', 'png'))) {
                    $uploads['galeria'] .= $arquivo . ';';
                } else {
                    //Neste caso nÃ£o Ã© uma imagem, e agora doutor ?
                }
            }
            unset($arquivo);
        }
    }

    //Caso jÃ¡ tenham imagens
    if (isset($_POST['files'])) {
        foreach ($_POST['files'] as $chave => $valor) {
            if (!empty($valor)) {
                $uploads['galeria'] .= $valor . ';';
            }
        }
    }

    /* Fim imagens */
    /* TRATA CAMPOS */
    foreach ($_POST as $key => $value) {
        if ($key == 'id') {
            if (empty($value)) {
                $campos['id'] = "NULL";
            } else {
                $campos['id'] = $value;
            }
        } else if ($key == 'data') {
            $exp_data = explode("/", $value);
            $ano_data = $exp_data[2];
            $mes_data = $exp_data[1];
            $dia_data = $exp_data[0];
            $campos['data'] = "'" . $ano_data . '-' . $mes_data . '-' . $dia_data . "'";
        } else if ($key == 'datahora') {
            $data = reset(explode(' ', $value));
            $hora = end(explode(' ', $value));
            /* Data */
            $exp_data = explode("/", $data);
            $ano_data = $exp_data[2];
            $mes_data = $exp_data[1];
            $dia_data = $exp_data[0];

            $campos['datahora'] = "'" . $ano_data . '-' . $mes_data . '-' . $dia_data . ' ' . $hora . "'";
        } elseif ($key == 'senha') {
            $campos['senha'] = "'" . md5($value) . "'";
        } elseif ((false !== strpos($key, 'arquivo')) ||
                (false !== strpos(strtolower($key), 'imagem')) ||
                (false !== strpos(strtolower($key), 'foto')) ||
                ( $key == 'imagem_destaque') ||
                ( $key == 'galeria') ||
                ( $key == 'arquivo')) {
            $campos[$key] = "'" . $uploads[$key] . "'";
        } else {
            $value = strval($value);
            if (strpos($value, "'") === true) {
                $campos[$key] = "'" . addslashes(utf8_decode($value)) . "'";
            } else {
                $campos[$key] = "'" . utf8_decode($value) . "'";
            }
        }
        $i++;
    }

    if ($acao == 'inserir') {
        foreach ((array) $campos as $key => $values) {
            if (empty($cmp)) {
                $cmp = "`" . $key . "`";
            } else {
                $cmp .= ",`" . $key . "`";
            }
            if (empty($vls)) {
                $vls = $values;
            } else {
                $vls .= "," . $values;
            }
        }

        if (mysql_query('select ordem from ' . $tabela)) {
            mysql_query('update ' . $tabela . ' set ordem = (ordem+1)');
            $ordem_n = 0;
            $cmp .= ",`ordem`";
            $vls .= "," . $ordem_n;
        }

        $sql = 'INSERT INTO ' . $tabela . ' (' . $cmp . ') VALUES (' . $vls . ')';
    } else {
        foreach ($campos as $key => $values) {
            if (($key != 'id') && ($key != 'files')) {
                if (((false !== strpos($key, 'arquivo')) || ($key == 'imagem') or ( $key == 'imagem_destaque')) and ( empty($values) or ( $values == "''"))) {
                    //MÃ¡gica ** :}
                } else {
                    if (empty($cmp)) {
                        $cmp = "`" . $key . "` = " . $values;
                    } else {
                        $cmp .= ",`" . $key . "` = " . $values;
                    }
                }
            }
        }
        $sql = "UPDATE `" . $tabela . "` SET " . $cmp . " WHERE `" . $tabela . "`.`id` = " . $campos['id'] . " limit 1 ;";
    }

    return array('campos' => $campos, 'sql' => $sql, 'uploads' => $uploads, 'acao' => $acao, 'info_adici' => $InfoAdic);
}

function dbg($nome, $var) {
    echo '<pre>';
    echo '<h1>' . $nome . '</h1>';
    var_dump($var);
    echo '</pre></hr>';
}

function painel_redirect() {
    
}

function inserir_banco($info, $table) {
    $dados = gera_sql($table, 'inserir', $info);
//    die('<pre style="background:black;color:white;">' . print_r($dados, true) . '</pre>');

    if (mysql_query($dados['sql']) or die('<br><br><br><br>' . $query . " |inserir| " . mysql_error() . '<br><br><br><br>' . print_r($fields) . '<br><br><br><br><br>')) {
        $id_retorno = mysql_insert_id();
        return $id_retorno;
    }
}

function alterar_banco($info, $table) {
    $dados = gera_sql($table, 'alterar', $info);
//    echo '<pre style="background:black;color:white;">';
//    var_dump($dados);
//    echo '</pre>';
//    die();


    if (mysql_query($dados['sql']) or die($dados['sql'] . " |alterar| " . mysql_error())) {
        $id_retorno = $dados['campos']['id'];
        return $id_retorno;
    }
}

function cria_campo($comment, $field, $type, $extra, $value, $join) {
    if ($comment != 'invisible') {
        switch ($type) {
            case "int(11)":
                $type = "hidden";
                break;
            case "int(12)":
                $type = "text";
                break;
            case "mediumint(9)":
                $type = "join";
                break;

            case (preg_match("/(varchar\()+(.*)(\))/", $type, $match) == 1):
                //$type = "text";
                $limit = $match[2];
                if ($limit == '20') {
                    $type = 'youtube';
                } if ($limit == '50') {
                    $type = 'password';
                } else {
                    $type = "text";
                }
                break;
            case (preg_match("/(char\()+(.*)(\))/", $type, $match) == 1):
                $type = "hidden";
                $limit = $match[2];
                break;
            case (preg_match("/(enum\()+(.*)(\))/", $type, $match) == 1):
                $type = "select";
                break;

            case "float":
                $type = "float";
                break;
            case "text":
                if ($field == 'descricao') {
                    $type = "textarea2";
                } else {
                    $type = "galeria";
                }
                break;
            case "tinytext":
                $type = "file";
                $readonly = "readonly";
                break;
            case "longtext":
                if (($field == 'codigo_gmaps') or ( $field == 'script_analytics') or ( $field == 'embeed_youtube') or ( $field == 'metatags') or ( $field == 'video_url') or ( strpos($field, 'embeed') !== false))
                    $type = "textarea2";
                else if ($field == 'tags')
                    $type = "tags";
                else if ($field == 'descricao_log')
                    $type = "pre";
                else
                    $type = "textarea";
                break;
            case "mediumtext":
                $type = "textarea2";
                //$readonly = "readonly";
                break;
            case "date":
                $type = "date";
                break;
            case "timestamp":
                $type = "datahora";
                break;
            case "decimal(10,2)":
                $type = "text";
                $limit = "13";
                break;
            case "time":
                $type = "text";
                $limit = "13";
                break;
        }
        if (isset($limit)) {
            $return_limit = " maxlength=\"" . $limit . "\" ";
        }
        if (isset($readonly)) {
            $return_readonly = " readonly=\"readonly\" ";
        }

        if ($type != "hidden") {
            /* ------------- */
            $return = "<label for=\"" . $field . "\">" . utf8_encode($comment) . "</label>";
        }
        switch ($type) {

            case "file":
                global $sys_this_page;
                //if(strtolower($comment) == 'foto'){
//                if (substr(strtolower($comment), 0, 4) == 'foto') {
                $lowerComment = strtolower($comment);
                if (false !== strpos($lowerComment, 'foto') || false !== strpos($lowerComment, 'imagem')) {

                    $return .= "<input style=\"float:left;width:560px;\" name=\"" . $field . "_file\" id=\"form_" . $field . "\" type=\"file\" " . $return_limit . " " . '' . " /><input name=\"" . $field . "\" type=\"hidden\" value=\"" . $field . "\"/>";
                    $nm_img = "../../uploads/" . $sys_this_page . "/" . str_replace(".", "_admin.", $value);

                    if (file_exists($nm_img)) {
                        $return .= "<img style=\"float:left;margin-top:-20px;\" src=\"" . $nm_img . "\">";
                    }
                    if ($sys_tabela == 'tbl_banner_home') {
                        $return .= '</div>';
                    }
                } else if (strtolower($comment) == 'video' || strtolower($comment) == 'video da home') {
                    if (!empty($value) && file_exists("../upload/" . $value)) {
                        $return .= "
						<input name=\"" . $field . "\" id=\"form_" . $field . "\" type=\"hidden\" " . $return_limit . " " . $return_readonly . " value=\"" . utf8_encode($value) . "\"  />
						<a href=\"javascript:void(0)\" id=\"thumb_foto\" onclick=\"view_video('../../upload/" . utf8_encode($value) . "')\" class=\"thumb_video\">Visualizar Video</a>
						<a href=\"javascript:upload_video()\" id=\"incluir_foto\" style=\"margin-left:0;clear:both\" >Alterar video</a>";
                    } else {
                        $return .= "
						<a href=\"javascript:void(0)\" id=\"thumb_foto\"')\" class=\"thumb_video\"  style=\"display:none;\">Visualizar Video</a>
						<input name=\"" . $field . "\" id=\"form_" . $field . "\" type=\"hidden\" " . $return_limit . " " . $return_readonly . " value=\"" . utf8_encode($value) . "\"  />
						<a href=\"javascript:upload_video()\" id=\"incluir_foto\" style=\"margin-left:0;clear:both\" >Incluir video</a>";
                    }
//                } else if (substr(strtolower($comment), 0, 7) == 'arquivo') {
                } else if (false !== strpos(strtolower($comment), 'arquivo')) {
                    $array = explode(";", $value);
                    $return .= "<input name=\"" . $field . "_file\" id=\"form_" . $field . "\" type=\"file\" " . $return_limit . " " . '' . " /><input name=\"" . $field . "\" type=\"hidden\" value=\"" . $value . "\"/>";
                    foreach ($array as $key => $value) {
                        if (!empty($value)) {
                            $return .= '<div class="container_file"><a href="../../uploads/' . $sys_this_page . '/' . $value . '">Clique para ver arquivo: ' . $value . '</a></div>';
                        }
                    }
                }
                break;
            case "galeria":

                $return .= "<input id=\"my_file_element\" type=\"file\" name=\"file_1\" >
							<div id=\"files_list\" style=\"display:block;\"></div>
							<script src=\"" . PAINEL_BASE_URL . "js/multifile_compressed.js\"></script>
							<script>
								<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->
								var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 15 );
								multi_selector.setFieldName('" . $field . "');
								<!-- Pass in the file element -->
								multi_selector.addElement( document.getElementById( 'my_file_element' ) );
							</script>
							<input name=\"" . $field . "\" type=\"hidden\" value=\"" . $field . "\"/>";
                $array = explode(";", $value);
                $return .= '<script type="text/javascript">
                                $(function() {
                                        $( "#sortable" ).sortable();
                                        $( "#sortable" ).disableSelection();
                                });
                            </script>';

                if (!empty($array)) {
                    $return .= "<div class=\"arrs_reordena\">Arraste as fotos para reordenar </div><div id=\"sortable\" style=\"margin-top: 10px; float:left; width: 100%\">";
                    foreach ($array as $key => $value) {
                        if ($value) {
                            global $sys_this_page;
                            $pagina = $sys_this_page;
                            $return .= "<li style=\"float:left;list-style: none;\"><div style=\"float:left;\"><input name=\"files[$key]\" type=\"checkbox\" value=\"$value\" checked=\"checked\" style=\"width: auto; background: transparent; border: 0;\" /></div><div style=\"float:left; margin: 3px 0px; margin-left: 5px;\"><img src=\"../../uploads/" . $pagina . "/" . str_replace(".", "_admin.", $value) . "\"></div></li>";
                        }
                    }
                    $return .= "</div>";
                }


                if ($sys_tabela == 'tbl_banner_home') {
                    $return .= '</div>';
                }

                $return .= '<br clear="all">';
                break;
            case "hidden":
                $return .= "<input name=\"" . $field . "\" id=\"form_" . $field . "\" type=\"hidden\" " . $return_limit . " " . $return_readonly . " value=\"" . $value . "\"  />";
                break;
            case "text":
                $return .= "<input name=\"" . $field . "\" id=\"form_" . $field . "\" type=\"text\" " . $return_limit . " " . $return_readonly . " value=\"" . utf8_encode($value) . "\" />";
                break;
            case "pre":
                $return .= '<pre style="background: black;color: white;font-size: 15px;z-index: 999;width: 3000px;">' . utf8_encode($value) . '</pre>';
                break;
            case "float":
                $return .= "<input name=\"" . $field . "\" id=\"form_" . $field . "\" type=\"text\" " . $return_limit . " " . $return_readonly . " value=\"" . utf8_encode((!empty($value) ? $value : '0.00')) . "\" />";
                $return .=
                        '<script type="text/javascript">
                            $(document).ready(function(){
                                
                                function valida_' . $field . '(){
                                    var string = $("#form_' . $field . '").val()+"";
                                    string = string.replace(",",".");
                                    string = parseFloat(string).toFixed(2);
                                    if(isNaN(string)){
                                        alert("Por favor informe um valor numÃ©rico");
                                        string = "0";
                                    }
                                    $("#form_' . $field . '").val(string);
                                }
                                $("#form_' . $field . '").blur(function(){
                                    valida_' . $field . '();
                                });
                                valida_' . $field . '(); //Para executar a primeira vez

                            })
                        </script>';
                break;
            case "password":
                $return .= "<input name=\"" . $field . "\" id=\"form_" . $field . "\" type=\"password\" " . $return_limit . " " . $return_readonly . "/>";
                break;
            case "date":
                $data = explode('-', (empty($value)) ? date('Y-m-d') : $value);
                $dia = $data[2];
                $mes = $data[1];
                $ano = $data[0];
                $return .= "<input rel=\"data_publicacao\" name=\"" . $field . "\" id=\"form_" . $field . "\" type=\"text\" " . $return_limit . " " . $return_readonly . " value=\"" . utf8_encode($dia . '/' . $mes . '/' . $ano) . "\" />";
                break;

            case "datahora":
                $data = reset(explode(' ', (empty($value)) ? date('Y-m-d') : $value));
                $hora = end(explode(' ', $value));
                /* Data */
                $exp_data = explode("-", $data);
                $ano_data = $exp_data[0];
                $mes_data = $exp_data[1];
                $dia_data = $exp_data[2];

                $tmp_vlr = $dia_data . '/' . $mes_data . '/' . $ano_data . ' ' . $hora;

                $return .= "<input rel=\"data_hora\" name=\"" . $field . "\" id=\"form_" . $field . "\" type=\"text\" " . $return_limit . " " . $return_readonly . " value=\"" . utf8_encode($tmp_vlr) . "\" />";
                break;

            case "textarea":
                $return .= "<textarea class=\"redactor\" name=\"" . $field . "\" id=\"form_" . $field . "\" " . $return_limit . " " . $return_readonly . " >" . utf8_encode($value) . "</textarea>";
                break;

            case "tags" :
                $return .= "<input class=\"tagit_container\" name=\"" . $field . "\" id=\"form_" . $field . "\" value=\"" . $value . "\"  type=\"text\" " . $return_limit . " " . $return_readonly . "/>";
                $return .= "<script type=\"text/javascript\">
                                $(document).ready(function(){
                                    $('.tagit_container').tagit();
                                });
                            </script>";
                break;


            case "textarea2":
                if (!empty($value)) {
                    $value_exp = explode(";", $value);
                    for ($i = 0; $i < count($value_exp); $i++) {
                        $valor .= $value_exp[$i];
                        if ($i < sizeof($value_exp)) {
                            $valor .= "\n";
                        }
                    }
                } else {
                    $valor = "";
                }
                $return .= "<textarea rows=\"10\" name=\"" . $field . "\" " . $return_limit . " " . $return_readonly . " >" . utf8_encode($valor) . "</textarea>";
                break;
            case "join":
                if (!empty($join[$field])) {
                    $join_exp = explode("|", $join[$field]);
                    $join_tbl = $join_exp[0];
                    $join_joins = explode(":", $join_exp[1]);
                    $join_id1 = $join_joins[0];
                    $join_id2 = $join_joins[1];


                    $query_join = "select * from " . $join_tbl . ' order by nome';

                    $query_join = mysql_query($query_join) or die("#func " . mysql_error() . " <br># " . $query_join);
                    if ($field == 'id_cidade') {
                        $return .= "<select class=\"select_estilizado\" name=\"" . $field . "\">";
                    } else {
                        $return .= "<select class=\"select_estilizado\" name=\"" . $field . "\">";
                    }


                    while ($dados_join = mysql_fetch_array($query_join)) {
                        $return .= "<option value=\"" . $dados_join['id'] . "\" ";
                        if (!empty($value) && $value == $dados_join['id']) {
                            $return .= " selected=\"selected\" ";
                        }
                        if (isset($dados_join['nome_pt'])) {
                            $return .= ">" . utf8_encode($dados_join['nome_pt']) . "</option>\n";
                        } else {
                            $return .= ">" . utf8_encode($dados_join['nome']) . "</option>\n";
                        }
                    }



                    $return .= "</select>";
                }
                break;
            case "select":
                $return .= "<select id=\"" . $field . "\" name=\"" . $field . "\">";
                $variaveis = preg_replace("/(enum\()*(.*)(\))/", $type, $match);
                $valores = $match[2];
                $exp_valores = explode(",", str_replace("'", "", $valores));
                for ($i = 0; $i < count($exp_valores); $i++) {
                    if (!empty($exp_valores[$i])) {
                        $return .= "<option value=\"" . utf8_encode($exp_valores[$i]) . "\" ";
                        if ($value == $exp_valores[$i]) {
                            $return .= " selected=\"selected\" ";
                        }
                        $return .= ">" . utf8_encode($exp_valores[$i]) . "</option>";
                    } else if (empty($exp_valores[$i])) {
                        echo "<option value=\"\" disabled=\"disabled\" selected=\"selected\"></option>";
                    } else {
                        echo "<option value=\"\" disabled=\"disabled\"></option>";
                    }
                }
                $return .= "</select>";
                if ($sys_tabela == 'tbl_banner_home') {
                    $return .= '</div>';
                }
                break;

            case "youtube":
                if ($value) {
                    $return .= "<input name=\"" . $field . "\" id=\"form_" . $field . "\" type=\"text\" " . $return_readonly . " value=\"http://www.youtube.com/watch?v=" . utf8_encode($value) . "\" />";
                } else {
                    $return .= "<input name=\"" . $field . "\" id=\"form_" . $field . "\" type=\"text\" " . $return_readonly . "/>";
                }
                break;
        }
        if ($type != "hidden") {
            /* ------------- */
            $return .= "<div style=\"clear:both;\"></div>";
        }
        return $return;
    }
}

function nome_imagem($imagem, $id_imagem = 1) {
    $partes = explode($imagem, '.');
    return $partes[0] . '_' . $id_imagem . '.' . $partes[1];
}

function mes($mes, $lang) {
    switch ($lang) {
        case 'br':
            switch ($mes) {
                case 1: $mes = "Janeiro";
                    break;
                case 2: $mes = "Fevereiro";
                    break;
                case 3: $mes = "Mar&ccedil;o";
                    break;
                case 4: $mes = "Abril";
                    break;
                case 5: $mes = "Maio";
                    break;
                case 6: $mes = "Junho";
                    break;
                case 7: $mes = "Julho";
                    break;
                case 8: $mes = "Agosto";
                    break;
                case 9: $mes = "Setembro";
                    break;
                case 10: $mes = "Outubro";
                    break;
                case 11: $mes = "Novembro";
                    break;
                case 12: $mes = "Dezembro";
                    break;
            }
            break;
        case 'en':
            switch ($mes) {
                case 1: $mes = "Jan";
                    break;
                case 2: $mes = "Feb";
                    break;
                case 3: $mes = "Mar";
                    break;
                case 4: $mes = "Apr";
                    break;
                case 5: $mes = "May";
                    break;
                case 6: $mes = "Jun";
                    break;
                case 7: $mes = "Jul";
                    break;
                case 8: $mes = "Aug";
                    break;
                case 9: $mes = "Sep";
                    break;
                case 10: $mes = "Oct";
                    break;
                case 11: $mes = "Nov";
                    break;
                case 12: $mes = "Dez";
                    break;
            }
            break;
        case 'es':
            switch ($mes) {
                case 1: $mes = "Ene";
                    break;
                case 2: $mes = "Feb";
                    break;
                case 3: $mes = "Mar";
                    break;
                case 4: $mes = "Abr";
                    break;
                case 5: $mes = "May";
                    break;
                case 6: $mes = "Jun";
                    break;
                case 7: $mes = "Jul";
                    break;
                case 8: $mes = "Agoo";
                    break;
                case 9: $mes = "Sep";
                    break;
                case 10: $mes = "Oct";
                    break;
                case 11: $mes = "Nov";
                    break;
                case 12: $mes = "Dic";
                    break;
            }
            break;
    }

    return $mes;
}

function semana($semana) {
    switch ($semana) {
        case 0: $semana = "Domingo";
            break;
        case 1: $semana = "Segunda";
            break;
        case 2: $semana = "Ter&ccedil;a";
            break;
        case 3: $semana = "Quarta";
            break;
        case 4: $semana = "Quinta";
            break;
        case 5: $semana = "Sexta";
            break;
        case 6: $semana = "S&aacute;bado";
            break;
    }
    return $semana;
}

function mostradata($data, $lang) {
    $exp_data = explode('-', $data);
    $dd = $exp_data[2];
    $mm = $exp_data[1];
    $yy = $exp_data[0];

    if ($lang == 'br') {
        switch ($mm) {
            case 1: $mes = "Janeiro";
                break;
            case 2: $mes = "Fevereiro";
                break;
            case 3: $mes = "Mar&ccedil;o";
                break;
            case 4: $mes = "Abril";
                break;
            case 5: $mes = "Maio";
                break;
            case 6: $mes = "Junho";
                break;
            case 7: $mes = "Julho";
                break;
            case 8: $mes = "Agosto";
                break;
            case 9: $mes = "Setembro";
                break;
            case 10: $mes = "Outubro";
                break;
            case 11: $mes = "Novembro";
                break;
            case 12: $mes = "Dezembro";
                break;
        }
        return $dd . ' de ' . $mes . ' de ' . $yy;
    } else {
        switch ($mm) {
            case 1: $mes = "January";
                break;
            case 2: $mes = "February";
                break;
            case 3: $mes = "March";
                break;
            case 4: $mes = "Apri";
                break;
            case 5: $mes = "May";
                break;
            case 6: $mes = "June";
                break;
            case 7: $mes = "July";
                break;
            case 8: $mes = "August";
                break;
            case 9: $mes = "September";
                break;
            case 10: $mes = "October";
                break;
            case 11: $mes = "November";
                break;
            case 12: $mes = "December";
                break;
        }
        return $mes . ' ' . $dd . ', ' . $yy;
    }
}

function encode($string) {
    $base = "u1Astx8CxggMn6OpUt7WyjF4G2cZvb91klthf4RhSiRIIwH3o05mDrEaJdT0B/+0";
    $b64_str = base64_encode($string);
    $i = 0;
    $j = 0;
    while ($i < strlen($b64_str)) {
        if ($i == 64)
            break;
        $k[$i] = $b64_str[$i] . $base[$i];
        $encoded.=$k[$i];
        $i++;
    }
    return $encoded;
}

function decode($string) {
    $i = 0;
    $j = 0;
    while ($i < strlen($string)) {
        $k[$i] = $string[$j];
        $decoded.=$k[$i];
        $i++;
        $j = $j + 2;
    }
    $decoded = base64_decode($decoded);
    return $decoded;
}

function invertedata($string) {
    $e_data = explode('-', $string);
    return $e_data[2] . '/' . $e_data[1] . '/' . $e_data[0];
}

function desinvertedata($string) {
    $e_data = explode('/', $string);
    return $e_data[2] . '-' . $e_data[1] . '-' . $e_data[0];
}

function resumo($string, $chars) {
    if (strlen($string) > $chars) {
        $var = '0';
        while ($var == '0') {
            if ((substr($string, $chars, 1)) == ' ') {
                $var = '1';
            } else {
                $chars++;
            }
        }
    }
    $var = substr($string, 0, $chars);
    if (strlen($string) > $chars) {
        $var .= '...';
    }
    return $var;
}

function closetags($html) {
    preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU", $html, $result);
    $openedtags = $result[1];

    preg_match_all("#</([a-z]+)>#iU", $html, $result);
    $closedtags = $result[1];
    $len_opened = count($openedtags);

    if (count($closedtags) == $len_opened) {
        return $html;
    }
    $openedtags = array_reverse($openedtags);

    for ($i = 0; $i < $len_opened; $i++) {
        if (!in_array($openedtags[$i], $closedtags)) {
            $html .= "</" . $openedtags[$i] . ">";
        } else {
            unset($closedtags[array_search($openedtags[$i], $closedtags)]);
        }
    }
    return $html;
}

function banner($pais, $local, $limite) {
    switch ($local) {
        case '1': $width = '780';
            $height = '80';
            break; // top
        case '2': $width = '194';
            $height = '225';
            break; // dir
        case '3': $width = '194';
            $height = '225';
            break; // dir
        case '4': $width = '150';
            $height = '395';
            break; // sky

        case '5': $width = '375';
            $height = '80';
            break; // bot dir
        case '6': $width = '375';
            $height = '80';
            break; // bot left
    }

    $query = mysql_query("select * from banners where local = '" . $local . "' and pais = '" . $pais . "' and exibir = 's' ORDER BY RAND() limit " . $limite . "") or die(mysql_error());
    while ($dados = mysql_fetch_array($query)) {
        mysql_query("update banners set impressoes = impressoes + 1 where id = '" . $dados['id'] . "'") or die(mysql_error());

        switch ($dados['tipo']) {
            case 'i':
                if (!empty($dados['link'])) {
                    echo '<a href="' . $dados['link'] . '" target="_blank" onclick="clique(' . $dados['id'] . ')">';
                    echo '<img src="banners/' . $dados['arquivo'] . '" alt="' . $dados['nome'] . '" border="0" width="' . $width . '" height="' . $height . '">';
                    echo '</a>';
                } else {
                    echo '<img src="banners/' . $dados['arquivo'] . '" alt="' . $dados['nome'] . '" border="0" width="' . $width . '" height="' . $height . '">';
                }
                break;
            case 'f':
                $noext = str_replace('.swf', '', $dados['arquivo']);
                ?>
                <script type="text/javascript">
                    AC_FL_RunContent('codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0', 'width', '<?= $width ?>', 'height', '<?= $height ?>', 'id', 'xx', 'src', 'banners/<?= $noext ?>?clicktag=<?= $dados['link'] ?>', 'quality', 'high', 'pluginspage', 'http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash', 'movie', 'banners/<?= $noext ?>?clicktag=<?= $dados['link'] ?>'); //end AC code
                </script><noscript>
                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="<?= $width ?>" height="<?= $height ?>" id="xx">
                    <param name="movie" value="banners/<?= $dados['arquivo'] ?>?clicktag=<?= $dados['link'] ?>">
                    <param name="quality" value="high">
                    <embed src="banners/<?= $dados['arquivo'] ?>?clicktag=<?= $dados['link'] ?>" width="<?= $width ?>" height="<?= $height ?>" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash"></embed>
                </object></noscript>
                <?php
                break;
        }
    }
}

function excel($campos, $table, $destino) {
    $export = mysql_query("SELECT " . $campos . " FROM  " . $table . " order by id desc");
    $fields = mysql_num_fields($export);

    for ($i = 0; $i < $fields; $i++) {
        $header .= mysql_field_name($export, $i) . "\t";
    }

    while ($row = mysql_fetch_row($export)) {
        $line = '';
        foreach ($row as $value) {
            if ((!isset($value)) OR ( $value == "")) {
                $value = "\t";
            } else {
                $value = str_replace('"', '""', $value);
                $value = '"' . $value . '"' . "\t";
            }
            $line .= $value;
        }
        $dados .= trim($line) . "\n";
    }

    $dados = str_replace("\r", "", $dados);

    if ($dados == "") {
        $dados = "\n Nenhum registro encontrado!\n";
    }

    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=" . $destino . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    print "$header\n$dados";
}

function iframex($off) {
    return "<iframe id='iframex' name='iframex' src='images/trans.gif' style='display:none'></iframe>";
}

function scriptinput($campo, $valor) {
    return "<script type='text/javascript'>
function getgoinga(){
parent.document.getElementById('" . $campo . "').style.display = 'none';
}

try{
parent.document.getElementById('" . $campo . "').value = '" . $valor . "';
setTimeout('getgoinga()',5000);
} catch(err){ }
</script>";
}

function scripthtml($div, $valor) {
    return "<script type='text/javascript'>
function getgoinga(){
parent.document.getElementById('" . $div . "').innerHTML = '';
}

try{
parent.document.getElementById('" . $div . "').innerHTML = '" . $valor . "';
setTimeout('getgoinga()',6000);
} catch(err){ }
</script>";
}

function ativa_submit($valor) {
    return "<script>
parent.document.getElementById('submit').class = '';

</script>";
}

function opcmenu($opc_novo, $opc_ordem, $opc_salvar, $opc_editar, $opc_apagar) {
    if ($opc_novo == 1) {
        echo '<div style="float:left;margin-left:10px; line-height:16px; vertical-align:middle;"><a href="?acao=novo" style="text-decoration: none" title="Clique para incluir novo"> <img src="images/icon_new.png"  style="float:left; margin: 0 5px" /> Inserir novo</a></div>';
    }
    echo '<div style="float:right;margin-right:10px">';


    if ($opc_salvar == 1) {
        echo '<div style="line-height:16px; vertical-align:middle;  text-align:center ; float: right"><img src="images/icon_save.png" style="float:left; margin: 0 5px" /><em>Salvar</em></div>';
    }
    if ($opc_editar == 1) {
        echo '<div style="line-height:16px; vertical-align:middle;  text-align:center ; float: right"><img src="images/icon_edit.png" style="float:left; margin: 0 5px" /><em>Editar</em></div>';
    }
    if ($opc_apagar == 1) {
        echo '<div style="line-height:16px; vertical-align:middle;  text-align:center ; float: right"><img src="images/icon_delete.png" style="float:left; margin: 0 5px" /><em>Apagar</em></div>';
    }
    if ($opc_ordem == 1) {
        echo '<div style="line-height:16px; vertical-align:middle;  text-align:center ; float: right"><img src="images/icon_up.png" style="float:left; margin: 0 2px 0 5px" /><img src="images/icon_down.png" style="float:left; margin: 0 5px 0 0px" /><em>Ordem</em></div>';
    }


    echo '</div>';
}

function opcmsg($sys_erro, $sys_msg) {
    switch ($sys_msg) {
        case 1:
            $sys_mens = 'Item deletado com sucesso.';
            break;
        case 2:
            $sys_mens = 'Item salvo com sucesso.';
            break;
        case 3:
            $sys_mens = 'Item atualizado com sucesso.';
            break;
    }

    if (!empty($sys_erro)) {
        echo '<tr><td colspan="2" height="30" style="background:#FFCC99" valign="middle">&nbsp;<strong>' . $erro . '</strong></td></tr>
<tr><td colspan="2" height="10" bgcolor="#FFFFFF" valign="middle"></td></tr>';
    }

    if (!empty($sys_msg)) {
        echo '
<tr><td colspan="2" height="30" style="background:#B7FFCE" valign="middle">&nbsp;<strong>' . $sys_mens . '</strong></td></tr>
<tr><td colspan="2" height="10" bgcolor="#FFFFFF" valign="middle"></td></tr>';
    }
}

function browser($string) {
    $browsers = "mozilla msie gecko firefox ";
    $browsers.= "konqueror safari netscape navigator ";
    $browsers.= "opera mosaic lynx amaya omniweb";
    $browsers = explode(" ", $browsers);
    $nua = strToLower($string);
    $l = strlen($nua);
    for ($i = 0; $i < count($browsers); $i++) {
        $browser = $browsers[$i];
        $n = stristr($nua, $browser);
        if (strlen($n) > 0) {
            $GLOBALS["ver"] = "";
            $GLOBALS["nav"] = $browser;
            $j = strpos($nua, $GLOBALS["nav"]) + $n + strlen($GLOBALS["nav"]
                    ) + 1;
            for (; $j <= $l; $j++) {
                $s = substr($nua, $j, 1);
                if (is_numeric($GLOBALS["ver"] . $s))
                    $GLOBALS["ver"] .= $s;
                else
                    break;
            }
        }
    }
    return array($GLOBALS["nav"], $GLOBALS["ver"]);
}

function data_agenda($data, $formato = 'd/m/Y - H:i') {
    return date_format(date_create($data), $formato);
}

function trataErro($erro) {
    switch ($erro) {
        case '1':
            $mensagem = '&Eacute; necess&aacute;rio digitar um login e senha.';
            break;
        case '2':
            $mensagem = 'Login n&atilde;o encontrado.';
            break;
        case '3':
            $mensagem = 'Senha incorreta.';
            break;
        case '4':
            $mensagem = 'Ocorreu um erro, tente novamente.';
            break;
        case '5':
            $mensagem = 'SessÃ£o Expirou.';
            break;
        case '6':
            $mensagem = 'Logout.';
            break;
        case '7':
            $mensagem = 'Login expirou.';
            break;
        case '':
            break;
        default :
            $mensagem = 'Algum erro ocorreu ao efetuar o login';
            break;
    }
    return $mensagem;
}
