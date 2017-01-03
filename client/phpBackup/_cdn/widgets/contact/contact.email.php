<?php

$MailContent = '
<table width="550" style="font-family: "Trebuchet MS", sans-serif;">
 <tr><td>
  <font face="Trebuchet MS" size="3">
   #mail_body#
  </font>
  <p style="font-size: 0.875em;">
  <img src="' . BASE . '/admin/_img/mail.jpg" alt="Atenciosamente ' . SITE_NAME . '" title="Atenciosamente ' . SITE_NAME . '" /><br><br>
   ' . SITE_ADDR_NAME . '<br>Telefone: ' . SITE_ADDR_PHONE_A . '<br>E-mail: ' . SITE_ADDR_EMAIL . '<br><br>
   <a title="' . SITE_NAME . '" href="' . BASE . '">' . SITE_ADDR_SITE . '</a><br>' . SITE_ADDR_ADDR . '<br>'
        . SITE_ADDR_CITY . '/' . SITE_ADDR_UF . ' - ' . SITE_ADDR_ZIP . '<br>' . SITE_ADDR_COUNTRY . '
  </p>
  </td></tr>
</table>
<style>body, img{max-width: 550px !important; height: auto !important;} p{margin-botton: 15px 0 !important;}</style>';
