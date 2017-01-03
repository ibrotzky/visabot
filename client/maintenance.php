<style>
.workcontrol_maintenance_content{
    display: block;
    background: #008068;
    position: fixed;
    width: 100%;
    height: 100%;
}

.workcontrol_maintenance_content .maintenance_box{
    display: block;
    width: 600px;
    margin: 10% auto;
    max-width: 90%;
    background: #fff;
    padding: 50px;
}

.workcontrol_maintenance_content .maintenance_box h1{
    font-size: 2em;
    font-weight: 600;
    color: #008068;
    text-shadow: 1px 1px 0 #eee;
}

.workcontrol_maintenance_content .maintenance_box p{
    margin: 15px 0;
}
</style>
<article class="workcontrol_maintenance_content">
    <div class="maintenance_box">
        <h1>Desculpe, estamos em manutenção!</h1>
        <p>Neste momento estamos trabalhando para melhorar ainda mais sua experiência em nosso site.</p>
        <p><b>Por favor, volte em algumas horas para conferir as novidades!</b></p>
        <em>Atenciosamente <?= SITE_NAME; ?></em>
    </div>
</article>
