<?php
if (!$Read):
    $Read = new Read;
endif;
?>
<div class="container not_found">
    <div class="content">
        <section>
            <header>
                <h1>Desculpe, mas não encontramos o que você procura!</h1>
                <p>A página ou conteúdo acessado não foi encontrado em nosso site. Sentimos muito por isso! Por favor. Faça uma pesquisa, ou ainda veja abaixo uma lista de nossos conteúdos mais acessados!</p>
            </header>

            <form class="search_form" name="search" action="" method="post" enctype="multipart/form-data">
                <input type="text" name="s" placeholder="Pesquisar Artigos:" required/>
                <button class="btn btn_blue">Pesquisar</button>
            </form>

            <?php
            $Read->ExeRead(DB_POSTS, "WHERE post_status = 1 AND post_date <= NOW() ORDER BY post_views DESC LIMIT 4");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $Post):
                    ?>
                    <article class="not_fount_post">
                        <a title="Ler mais sobre <?= $Post['post_title']; ?>" href="<?= BASE; ?>/artigo/<?= $Post['post_name']; ?>">
                            <img title="<?= $Post['post_title']; ?>" alt="<?= $Post['post_title']; ?>" src="<?= BASE; ?>/tim.php?src=uploads/<?= $Post['post_cover']; ?>&w=<?= IMAGE_W / 2; ?>&h=<?= IMAGE_H / 2; ?>"/>
                        </a>
                        <h1><a title="Ler mais sobre <?= $Post['post_title']; ?>" href="<?= BASE; ?>/artigo/<?= $Post['post_name']; ?>"><?= $Post['post_title']; ?></a></h1>
                    </article>
                    <?php
                endforeach;
            endif;
            ?>

        </section>
        <div class="clear"></div>
    </div>
</div>