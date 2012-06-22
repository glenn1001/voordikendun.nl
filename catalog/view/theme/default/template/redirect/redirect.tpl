<?php

/*
  Document   : redirect
  Created on : 20-jun-2012, 21:06:16
  Author     : Glenn Blom <glennblom@gmail.com>
  Copyright © 2012 GB Web Productions
 */
?>
Bedankt voor uw bezoek u wordt binnen 5 seconden doorverwezen naar de webshop.
<script type="text/javascript">
    var url = '<?php echo $url; ?>';
    setTimeout('window.location=url;', 5000);
</script>
