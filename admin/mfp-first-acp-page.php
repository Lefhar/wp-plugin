<?php 
/*
zone admin donc on contrôle si c'est l'administrateur
*/

//on teste que la table existe bien
if(!testetable()){
    create_plugin_database_table() ;
}

//on utilise l'utilisateur qui est sur la page admin
$userpi = wp_get_current_user();


// on restrein l'accés que à l'admin
if($userpi->roles[0]!='administrator'){
    echo langage('Erreur gestion réservé administrateur','Manager reserved management error');
}else{
  // on recupére notre valeur
    $x = $wpdb->get_results('SELECT * FROM '.$table_prefix.'ga where id=1');
    $m = $x[0];
 
?>


<div class="wrap">
  <h1><?php echo langage('Bonjour','Hi');?> <?php echo $userpi->display_name;?>!</h1>


  <?php 
  if(!empty($m->etat) && $m->etat!='no'){
    //si l'état n'est pas sur no
    
    ?>
  <p><?php echo langage('Votre code google analytic','Your google analytic code');?> <?php echo $m->code;?> Etat <?php echo $m->etat;?></p>
  <?php }?>
  <p>Google Analytic <?php
   if($m->etat=='no'){
     //si désactivé
      echo langage('est innactif','is off');
      }else{
      // si actif
      echo langage('est actif','is on');
        }?></p>

        
          <?php 
          if(!empty($m->etat)&& !empty($m->code)){
            //si état est pas vide et que code n'ont est pas vide
            ?>

          <p><?php echo langage('Souhaitez vous activer le suivi ?','Would you like to activate tracking ?');?></p>
            <form method="post" action="">
          <table class="form-table">
          <tbody><tr>
          <th scope="row"><label for="etat">Etat <?php if($m->etat=='no'){ echo langage('innactif','off');}else{echo langage('actif','on');}?></label></th>
          <td>
            <?php 
            
                  if($m->etat=='no'){ 
                    // si état désactivé
                    echo '<input name="etat" type="hidden" id="etat" value="yes">';
                  }else{
                    // si état activé remplace l'input par yes
                    echo '<input name="etat" type="hidden" id="etat" value="no">';
                  }
            
            ?>


</td>
</tr>
</tbody></table>
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php if($m->etat=='no'){ echo langage('Activer','Activate');}else{echo langage('Désactiver','Deactivate');}?>"></p>
</form>
  <?php }?>
  
  
  <?php if($m->etat=='no'){?>
  <p><?php echo langage('Veuillez entrer votre code google analytic','Please enter your google analytic code');?></p>
  <?php } ?>
  <form method="post" action="">
<table class="form-table">

<tbody><tr>
<th scope="row"><label for="token">Identifiant</label></th>
<td><input name="code" type="text" id="code" placeholder="<?php echo langage('Votre code google analytic','Your google analytic code');?>" class="regular-text" value="<?php echo $m->code;?>"></td>
</tr>
</tbody></table>
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo langage('Envoyer','Send');?>"></p>
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

        // petit js refresh de la page car après le post l'état se met pas à jour 
        echo '<script>setTimeout(function(){ location.reload(); }, 1);</script>';
       }
       if(!empty($_POST['code'])){
    
           // requête pour mettre à jour le code analytic
        $data = [ 'code' => $_POST['code'], 'etat' => $m->etat ];
        $where = [ 'id' => 1 ];
        $wpdb->update( $wpdb->prefix . 'ga', $data, $where, '', '' );

         // petit js refresh de la page car après le post l'état se met pas à jour 
echo '<script>setTimeout(function(){ location.reload(); }, 1);</script>';
        
       }