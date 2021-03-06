<?php

if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'MQTT');
$eqLogics = eqLogic::byType('MQTT');

?>

<div class="row row-overflow">
  <div class="col-lg-2 col-sm-3 col-sm-4">
    <div class="bs-sidebar">
      <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
        <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un équipement}}</a>
        <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
        <?php
        foreach ($eqLogics as $eqLogic) {
          echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
        }
        ?>
      </ul>
    </div>
  </div>

  <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
    <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
    <div class="eqLogicThumbnailContainer">
      <div class="cursor eqLogicAction" data-action="gotoPluginConf" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
        <center>
          <i class="fa fa-wrench" style="font-size : 5em;color:#767676;"></i>
        </center>
        <span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Configuration}}</center></span>
      </div>
      <div class="cursor" id="bt_healthMQTT" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
        <center>
          <i class="fa fa-medkit" style="font-size : 5em;color:#767676;"></i>
        </center>
        <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Santé}}</center></span>
      </div>
    </div>


    <legend><i class="fa fa-table"></i>  {{Mes MQTT}}
    </legend>
    <div class="eqLogicThumbnailContainer">
      <div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
        <center>
          <i class="fa fa-plus-circle" style="font-size : 7em;color:#00979c;"></i>
        </center>
        <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>{{Ajouter}}</center></span>
      </div>
      <?php
      $dir = dirname(__FILE__) . '/../../doc/images/';
      $files = scandir($dir);
      foreach ($eqLogics as $eqLogic) {
        $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
        echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff ; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
        echo "<center>";
        $test = 'node_' . $eqLogic->getConfiguration('icone') . '.png';
        log::add('MQTT', 'debug', 'test ' . $test);
        if (in_array($test, $files)) {
          $path = 'node_' . $eqLogic->getConfiguration('icone');
        } else {
          $path = 'MQTT_icon';
        }
        echo '<img src="plugins/MQTT/doc/images/' . $path . '.png" height="105" width="95" />';
        echo "</center>";
        echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
        echo '</div>';
      }
      ?>
    </div>
  </div>
<div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
 <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
 <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
 <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a>
 <ul class="nav nav-tabs" role="tablist">
  <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
  <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
  <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
</ul>
<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
  <div role="tabpanel" class="tab-pane active" id="eqlogictab">
        <form class="form-horizontal">
          <fieldset>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
              <div class="col-sm-3">
                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement MQTT}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" >{{Objet parent}}</label>
              <div class="col-sm-3">
                <select class="form-control eqLogicAttr" data-l1key="object_id">
                  <option value="">{{Aucun}}</option>
                  <?php
                  foreach (object::all() as $object) {
                    echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Catégorie}}</label>
              <div class="col-sm-8">
                <?php
                foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                  echo '<label class="checkbox-inline">';
                  echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                  echo '</label>';
                }
                ?>

              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" ></label>
              <div class="col-sm-8">
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
              </div>
            </div>


            <div class="form-group expertModeVisible">
              <label class="col-sm-3 control-label">{{Délai max entre 2 messages}}</label>
              <div class="col-sm-3">
                <input class="eqLogicAttr form-control" data-l1key="timeout" placeholder="{{Délai maximum autorisé entre 2 messages (en mn)}}"/>
              </div>
            </div>
            <div class="form-group expertModeVisible">
              <label class="col-sm-3 control-label">{{Type de piles}}</label>
              <div class="col-sm-3">
                <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="battery_type" placeholder="{{Doit être indiqué sous la forme : 3xAA}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Commentaire}}</label>
              <div class="col-sm-3">
                <textarea class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="commentaire" ></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">{{Inscrit au Topic}}</label>
              <div class="col-sm-3">
				
				<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="topic" placeholder="{{Topic principal de l'équipement MQTT}}"/>
              </div>
            </div>
			
			<div class="form-group">
              <label class="col-sm-3 control-label">{{Qos}}</label>
              <div id="mqttqos" class="col-sm-3">
				<select style="width : 40pxpx;" class="eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="Qos">
					<option value="0">0</option>
					<option value="1" selected>1</option>
					<option value="2">2</option>


				</select>
              </div>
            </div>
			<div class="form-group">
				<label class="col-sm-3 control-label">{{Topic Enfant}}</label>
				<div class="col-sm-8">	
					<label class="checkbox-inline"><input id="isChild" type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="isChild" disabled readonly /></label>
				</div>
			</div>
            
			<div class="form-group ">
				<label class="col-sm-3 control-label">{{Profondeur du Topic: }}</label>
					<div id="mqttdepth" class="col-sm-3">
						<select style="width : 140pxpx;" class="eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="wcard">
							<option value="+" selected>{{Ce topic seulement (+)}}</option>
							<option value="#">{{Tout les sous-topics} (#)}}</option>
							
						</select>
					</div>
				</label>
			</div>
			
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Dernière Activité}}</label>
              <div class="col-sm-3">
                <span class="eqLogicAttr" data-l1key="configuration" data-l2key="updatetime"></span>
              </div>
            </div>


            <div class="form-group">
              <label class="col-sm-3 control-label">{{Catégorie du topic}}</label>
              <div class="col-sm-3">
                <select id="sel_icon" class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="icone">
                  <option value="">{{Aucun}}</option>
                  <option value="433">{{RF433}}</option>
                  <option value="barometre">{{Baromètre}}</option>
                  <option value="boiteauxlettres">{{Boite aux Lettres}}</option>
                  <option value="chauffage">{{Chauffage}}</option>
                  <option value="compteur">{{Compteur}}</option>
                  <option value="contact">{{Contact}}</option>
                  <option value="feuille">{{Culture}}</option>
                  <option value="custom">{{Custom}}</option>
                  <option value="dimmer">{{Dimmer}}</option>
                  <option value="energie">{{Energie}}</option>
                  <option value="garage">{{Garage}}</option>
                  <option value="humidity">{{Humidité}}</option>
                  <option value="humiditytemp">{{Humidité et Température}}</option>
                  <option value="hydro">{{Hydrométrie}}</option>
                  <option value="ir2">{{Infra Rouge}}</option>
                  <option value="jauge">{{Jauge}}</option>
                  <option value="light">{{Luminosité}}</option>
                  <option value="meteo">{{Météo}}</option>
                  <option value="motion">{{Mouvement}}</option>
                  <option value="multisensor">{{Multisensor}}</option>
                  <option value="prise">{{Prise}}</option>
                  <option value="relay">{{Relais}}</option>
                  <option value="rfid">{{RFID}}</option>
                  <option value="teleinfo">{{Téléinfo}}</option>
                  <option value="temp">{{Température}}</option>
                  <option value="thermostat">{{Thermostat}}</option>
                  <option value="volet">{{Volet}}</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div style="text-align: center">
                <img name="icon_visu" src="" width="160" height="200"/>
              </div>
            </div>

          </fieldset>
        </form>
      </div>
      <div role="tabpanel" class="tab-pane" id="commandtab">

        <form class="form-horizontal">
          <fieldset>
            <div class="form-actions">
              <a class="btn btn-success btn-sm cmdAction" id="bt_addMQTTAction"><i class="fa fa-plus-circle"></i> {{Ajouter une commande action}}</a>
            </div>
          </fieldset>
        </form>
        <br />
        <table id="table_cmd" class="table table-bordered table-condensed">
          <thead>
            <tr>
              <th style="width: 50px;">#</th>
              <th style="width: 150px;">{{Nom}}</th>
              <th style="width: 110px;">{{Sous-Type}}</th>
              <th>{{Topic}}</th>
              <th style="width: 100px;">{{Valeur}}</th>
              <th style="width: 200px;">{{Paramètres}}</th>
              <th style="width: 100px;"></th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>

<?php include_file('desktop', 'MQTT', 'js', 'MQTT'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>

<script>
$( "#sel_icon" ).change(function(){
  var text = 'plugins/MQTT/doc/images/node_' + $("#sel_icon").val() + '.png';
  //$("#icon_visu").attr('src',text);
  document.icon_visu.src=text;
});
</script>
