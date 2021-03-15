<?php 
/*
zone admin donc on contrôle si c'est l'administrateur
*/
if(!testetable()){
    create_plugin_database_table() ;
}

$userpi = wp_get_current_user();
//var_dump($userpi);
if($userpi->roles[0]!='administrator'){
    echo langage('Erreur gestion réservé administrateur','Manager reserved management error');
}else{
    $x = $wpdb->get_results('SELECT * FROM '.$table_prefix.'ga where id=1');
    $m = $x[0];
 
?>


<div class="wrap">
  <h1><?php echo langage('Bonjour','Hi');?> <?php echo $userpi->display_name;?>!</h1>
  <?php if(!empty($m->etat) && $m->etat!='no'){?>
  <p><?php echo langage('Votre code google analytic','Your google analytic code');?> <?php echo $m->code;?> Etat <?php echo $m->etat;?></p>
  <?php }?>
  <p>Google Analytic <?php if($m->etat=='no'){ echo langage('est innactif','is off');}else{echo langage('est actif','is on');}?></p>
  <?php if(!empty($m->etat)&& !empty($m->code)){?>
   <p><?php echo langage('Souhaitez vous activer le suivi ?','Would you like to activate tracking ?');?></p>
   <form method="post" action="../wp-admin/admin.php?page=google-analytic%2Fadmin%2Fmfp-first-acp-page.php">
<table class="form-table">
<tbody><tr>
<th scope="row"><label for="etat">Etat <?php if($m->etat=='no'){ echo langage('innactif','off');}else{echo langage('actif','on');}?></label></th>
<td>
	<?php if($m->etat=='no'){ ?>
<input name="etat" type="hidden" id="etat" placeholder="<?php echo langage('Votre code google analytic','Your google analytic code');?>" class="regular-text" value="yes">
<?php }?>	
<?php if($m->etat=='yes'){ ?>
<input name="etat" type="hidden" id="etat" placeholder="<?php echo langage('Votre code google analytic','Your google analytic code');?>" class="regular-text" value="no">
<?php }?>
</td>
</tr>
</tbody></table>
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php if($m->etat=='no'){ echo langage('Activer','Activate');}else{echo langage('Désactiver','Deactivate');}?>"></p>
</form>
  <?php }?>  <?php if($m->etat=='no'){?>
  <p><?php echo langage('Veuillez entrer votre code google analytic','Please enter your google analytic code');?></p>
  <?php } ?>
  <form method="post" action="../wp-admin/admin.php?page=google-analytic%2Fadmin%2Fmfp-first-acp-page.php">
<table class="form-table">

<tbody><tr>
<th scope="row"><label for="token">Identifiant</label></th>
<td><input name="code" type="text" id="code" placeholder="<?php echo langage('Votre code google analytic','Your google analytic code');?>" class="regular-text" value="<?php echo $m->code;?>"></td>
</tr>
</tbody></table>
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo langage('Votre code google analytic','Your google analytic code');?>"></p>
</form>
</div>
  <?php
  
  //fermeture de la condition administrateur
  }
  if(!empty($_POST['etat'])){

    // requête pour mettre à jour l'état du plugin
        $data = [ 'code' => $m->code, 'etat' => $_POST['etat'] ];
        $where = [ 'id' => 1 ];
        $wpdb->update( $wpdb->prefix . 'ga', $data, $where, '', '' );
        echo '<script>setTimeout(function(){ location.reload(); }, 1);</script>';
       }
       if(!empty($_POST['code'])){
    
           // requête pour mettre à jour le code analytic
        $data = [ 'code' => $_POST['code'], 'etat' => $m->etat ];
        $where = [ 'id' => 1 ];
        $wpdb->update( $wpdb->prefix . 'ga', $data, $where, '', '' );
echo '<script>setTimeout(function(){ location.reload(); }, 1);</script>';
        
       }