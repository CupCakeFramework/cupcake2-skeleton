<?php
/* @var $this Site */
?>
<!DOCTYPE HTML>
<html>     
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="content-language" content="pt-br" />
        <meta name="author" content="Daniel Concon" />
        <meta name="robots" content="index,follow" />
        <link href="<?= $this->publicAssetsUrl ?>css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?= $this->publicAssetsUrl ?>css/default.css" />
        <link rel="stylesheet" type="text/css" href="<?= $this->publicAssetsUrl ?>css/typicons.min.css" />
        <link rel="icon" href="<?= $this->baseUrl ?>favicon.ico" type="image/x-icon"> 

    </head>
    <body class="page-<?= $this->paginaAtual ?>">
        <div id="fundo">
            <header id="top">
                <div class="container_12 top">
                    <div id="logo" class="grid_7"><a href="<?= $this->url(array('home')) ?>"><img src="<?= $this->publicAssetsUrl ?>images/layout/logo.png" alt=""></a></div> 
                    <div class="box400">
                        <div class="itens icone grid_2">
                            <p>itens: <a href="<?= $this->url(array('orcamento')) ?>"><?= $this->carrinho->quantidadeDeItensNoCarrinho() ?></a></p>
                        </div>
                        <div class="fone icone grid_3">
                            <p><?= $this->siteDados('telefone') ?></p>
                        </div>
                        <div class="box400">
                            <form action="<?= $this->url(array('buscar')) ?>" method="post" class="formulario form-busca">
                                <div class="grid_4">
                                    <input type="text" name="Busca" placeholder="Buscar em todo site" />
                                </div>
                                <div class="grid_1">
                                    <button type="submit" class="btnLupa"></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <nav>
                    <div class="bg-transp"></div>
                    <ul class="container_12">
                        <li><a href="<?= $this->url(array('home')) ?>">Inicial </a><span class="esq"></span> <span class="dir"></span></li>
                        <li><a href="<?= $this->url(array('quem-somos')) ?>">Quem Somos </a><span class="esq"></span> <span class="dir"></span></li>
                        <li><a href="<?= $this->url(array('produtos')) ?>">Produtos </a><span class="esq"></span> <span class="dir"></span></li>
                        <li><a href="<?= $this->url(array('atletas')) ?>">Atletas </a><span class="esq"></span> <span class="dir"></span></li>
                        <li><a href="<?= $this->url(array('representantes')) ?>">Representantes </a><span class="esq"></span> <span class="dir"></span></li>
                        <li><a href="<?= $this->url(array('noticias')) ?>">Notícias </a><span class="esq"></span> <span class="dir"></span></li>
                        <li class="last"><a href="<?= $this->url(array('fale-conosco')) ?>">Fale Conosco </a><span class="esq"></span> <span class="dir"></span></li>
                    </ul>
                </nav>
            </header>
            <section id="corpo" class="container_12">
                <?php echo $conteudo; ?>
                <div class="clear"></div> 
            </section> 
            <footer>
                <div class="container_12">
                    <div class="bloco-1 box480">
                        <div class="grid_4">
                            <h2>Newsletter</h2>
                        </div>

                        <form action="<?= $this->url(array('newsletter')) ?>" method="post" class="formulario form-news">
                            <div class="campo grid_4">
                                <input type="text" name="NomeNews" placeholder="Nome" />
                            </div>
                            <div class="campo grid_4">
                                <input type="text" name="EmailNews" placeholder="E-mail" />
                            </div>
                            <div class="grid_1">
                                <button type="submit" class="btnOk">OK</button>
                            </div>
                        </form>
                        <address class="grid_4 endereco">
                            <?= $this->siteDados('endereco') ?>
                        </address>
                    </div>
                    <div class="grid_6 likebox">
                        <iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Ftruda.kimonos%3Ffref%3Dts&amp;width=460&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:460px; height:258px;" allowTransparency="true"></iframe>
                    </div>
                    <copyright class="grid_12">Kimonos ©2014 - Todos os direitos reservados</copyright>
                </div>
            </footer>
        </div>
    </body>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?= $this->publicAssetsUrl ?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= $this->publicAssetsUrl ?>js/plugins.js"></script>
    <script type="text/javascript" src="<?= $this->publicAssetsUrl ?>js/slider.js"></script>
    <script type="text/javascript" src="<?= $this->publicAssetsUrl ?>js/funcoes.js"></script>
    <?php if ($this->paginaAtual == 'representantes') { ?>
        <script type="text/javascript" src="<?= $this->publicAssetsUrl ?>js/jquery.svgdom.js"></script>
        <script type="text/javascript" src="<?= $this->publicAssetsUrl ?>js/jquery.svg.js"></script>
    <?php } ?>
</html>