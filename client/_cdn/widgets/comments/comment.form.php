<form name="add_comment" action="" method="post" enctype="multipart/form-data">
    <label>
        <span>Comentar:</span>
        <textarea name="comment" rows="5" required></textarea>
    </label>

    <label>
        <select name="rank" required>
            <option disabled value="">Qual sua nota para esse conteúdo?</option>
            <option selected value="5">5 - Ótimo!</option>
            <option value="4">4 - Muito Bom!</option>
            <option value="3">3 - Regular!</option>
            <option value="2">2 - Razoável!</option>
            <option value="1">1 - Ruim!</option>
        </select>
    </label>

    <img class="load" alt="Enviando Comentário" title="Enviando Comentário" src="<?= BASE; ?>/_cdn/widgets/comments/load.gif">
    <button class="btn btn_blue">Enviar Comentários!</button>
    <span class="btn btn_red wc_close">Fechar :/</span>
    <div class="clear"></div>
</form>