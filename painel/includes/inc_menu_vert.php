<?php
$expld = explode("/", $_SERVER['PHP_SELF']);
$sys_menu_local = end($expld);
$sys_menu_aut = $_SESSION['admin_usuario_tipo'];

function menu_top($local, $name, $page) {
    $i = " style='font-weight:bold' ";
    echo "<a href='" . $page . "' ";
    if ($local == $page) {
        echo $i;
    }
    echo ">" . $name . "</a> &nbsp;&nbsp;";
}

if ($menu_visivel != 'n') {
    global $sys_local;
    ?>			
    <div id="menu" >
        <ul class="topnav" id="accordion">
            <li class="li_main"><a href="<?= PAINEL_BASE_URL ?>index.php" class="link_main"><span class="li_home"></span>Home</a></li>
            <div class="container_menu_left_mgs">
                <?php
                /* Menu Dinâmico */
                $qry_menu = mysql_query('select * from tbl_sys_menu where parent = 1 order by ordem');
                while ($row_menu = mysql_fetch_assoc($qry_menu)) {
                    $qry_submenu = mysql_query('select * from tbl_sys_menu where parent = ' . $row_menu['id'] . ' order by ordem');
                    if (mysql_num_rows($qry_submenu) > 0) {
                        echo '<li class="li_main borda_sub"><h3 class="toggler"><a class="link_main" onclick="return false" href="' . $row_menu['pagina'] . '"><span class="' . $row_menu['class'] . '"></span>' . utf8_encode($row_menu['nome']) . '</a></h3>';
                        echo '<ul class="subnav">';
                        while ($row_submenu = mysql_fetch_assoc($qry_submenu)) {
                            echo '<li class="li_child"><a class="link_child" href="' . PAINEL_BASE_URL . 'paginas/' . $row_submenu['pagina'] . '"><span class="' . $row_submenu['class'] . '"></span>' . utf8_encode($row_submenu['nome']) . '</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<li class="li_main"><a class="link_main" href="' . PAINEL_BASE_URL . 'paginas/' . $row_menu['pagina'] . '"><span class="' . $row_menu['class'] . '"></span>' . utf8_encode($row_menu['nome']) . '</a></li>';
                    }
                }
                /* Fim Menu dinâmico */
                /* Menu Estático, Não alterar !!!-------------------------------------------------------------------------------------- */
                $menu_vert = array(
                    array('nome' => 'SEO', 'pagina' => PAINEL_BASE_URL . 'configuracoes/page_sys_seo.php', 'class' => 'li_configs'),
                    array('nome' => 'Configurações', 'pagina' => PAINEL_BASE_URL . 'configuracoes/page_sys_config.php?a=e&item=MuQ1=A=s', 'class' => 'li_configs'),
                    array('nome' => 'Usuários Admin', 'pagina' => '#', 'class' => 'li_usuarios', 'child' =>
                        array(
                            array('nome' => 'Meu Usuário', 'pagina' => PAINEL_BASE_URL . 'configuracoes/page_sys_usuarios.php?a=e&item=' . encode($_SESSION['admin_usuario_id']), 'class' => 'li_usuarios'),
                            array('nome' => 'Novo Usuário', 'pagina' => PAINEL_BASE_URL . 'configuracoes/page_sys_usuarios.php?a=n', 'class' => 'li_usuarios'),
                            array('nome' => 'Listar todos', 'pagina' => PAINEL_BASE_URL . 'configuracoes/page_sys_usuarios.php', 'class' => 'li_usuarios')
                        )
                    ),
                    array('nome' => 'Sair', 'pagina' => PAINEL_BASE_URL . 'logoff.php', 'class' => 'li_logout')
                );
                ?>
            </div>
            <?php
            foreach ($menu_vert as $key => $value) {
                if (isset($value['child'])) {
                    echo '<li class="li_main"><h3 class="toggler"><a class="link_main" href="#"><span class="' . $value['class'] . '"></span>' . $value['nome'] . '</a></h3>';
                    echo '<ul class="subnav">';
                    foreach ($value['child'] as $key2 => $value2) {
                        echo '<li class="li_child"><a class="link_child" href="' . $value2['pagina'] . '"><span class="' . $value2['class'] . '"></span>' . $value2['nome'] . '</a></li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<li class="li_main"><a class="link_main" href="' . $value['pagina'] . '"><span class="' . $value['class'] . '"></span>' . $value['nome'] . '</a></li>';
                }
            }
            ?>

            <?php if ($_SESSION['admin_usuario_id'] == '1') { ?>
                                        <!--<li class="li_main"><a class="link_main" href="<?= PAINEL_BASE_URL ?>paginas/page_logs.php"><span class='li_padrao'></span>Logs</a></li>-->
                <li class="li_main"><a class="link_main" href="<?= PAINEL_BASE_URL ?>configuracoes/page_sys_menu.php"><span class='li_padrao'></span>Menu Opções</a></li>
                <li class="li_main"><a class="link_main" href="<?= PAINEL_BASE_URL ?>configuracoes/page_sys_geramenu.php"><span class='li_padrao'></span>Gerador de Pastel</a></li>
                <?php }/* FIM DE MENU ESTÁTICO */ ?>
        </ul>
    </div>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.subnav').toggle('fast');

        $('.toggler').click(function() {
            $(this).next('.subnav').toggle('fast');
        });
    });
</script>
