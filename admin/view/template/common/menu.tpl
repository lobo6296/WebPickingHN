<ul id="menu">
  <li id="dashboard"><a href="<?php echo $home; ?>"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo $text_dashboard; ?></span></a></li>
  <li id="catalog"><a class="parent"><i class="fa fa-tags fa-fw"></i> <span><?php echo $text_catalog; ?></span></a>
    <ul>
      <li><a href="<?php echo $offering; ?>"><?php echo $text_offering; ?></a></li>
    </ul>	
  </li>
  <li id="catalog"><a class="parent"><i class="fa fa-book fa-fw"></i> <span><?php echo $text_proyecto; ?></span></a>
    <ul>
      <li><a href="<?php echo $proyecto; ?>"><?php echo $text_abcproyecto; ?></a></li>
    </ul>	
  </li>
   <li id="plan"><a class="parent"><i class="fa fa-map-o fa-fw"></i> <span><?php echo "Plan"; ?></span></a>
    <ul>
    <li><a href="<?php echo $plan; ?>"><?php echo "Agregar/Editar Plan"; ?></a></li>
    <li><a href="<?php echo $asocplan; ?>"><?php echo "Asociar Plan a Prueba"; ?></a></li>	
    </ul>
  </li> 
  
  <li id="system"><a class="parent"><i class="fa fa-check fa-fw"></i> <span><?php echo "Ejecucion Pruebas"; ?></span></a>
    <ul>
	<!--
    <li><a href="<?php echo $desacbs; ?>"><?php echo "CBS Desarrollo"; ?></a></li>
	-->
    <li><a href="<?php echo $prodcbs; ?>"><?php echo "Ejecutar prueba"; ?></a></li>
    </ul>
  </li>

  <li id="monitoreo"><a class="parent"><i class="fa fa-heartbeat fa-fw"></i> <span><?php echo $text_monitoreo; ?></span></a>
    <ul>
      <li><a href="<?php echo $monitoreo_planes; ?>"><?php echo $text_monplanes; ?></a></li>
	  <li><a href="<?php echo $monitoreo_promociones; ?>"><?php echo $text_monpromo; ?></a></li>
	  <li><a href="<?php echo $monitoreo_prestamos; ?>"><?php echo $text_monpresta; ?></a></li>
    </ul>	
  </li>  

  <li id="servicios"><a class="parent"><i class="fa fa-cloud fa-fw"></i> <span><?php echo $text_servicios; ?></span></a>
    <ul> 
	<li id="system"><a class="parent"><?php echo "Tigo Plus";?></a>
    <ul>
      <li><a href="<?php echo $ws_validatecondition; ?>"><?php echo $text_validateCond; ?></a></li>
	   <li><a href="<?php echo $ws_accreditation; ?>"><?php echo $text_accreditation; ?></a></li>
    </ul>	
	</ul>
  </li>  
    <li id="system"><a class="parent"><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_system; ?></span></a>
    <ul>
      <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
      <li><a class="parent"><?php echo $text_users; ?></a>
        <ul>
          <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
          <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
          <li><a href="<?php echo $api; ?>"><?php echo $text_api; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_localisation; ?></a>
        <ul>
          <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_tools; ?></a>
        <ul>
          <li><a href="<?php echo $carga; ?>"><?php echo $text_carga; ?></a></li>
          <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
          <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
		  <li><a href="<?php echo $prerequisitos; ?>"><?php echo $text_prerequisitos; ?></a></li>
        </ul>
      </li>
    </ul>
  </li>
  <!--
   <li id="tools"><a class="parent"><i class="fa fa-wrench fa-fw"></i> <span><?php echo $text_tools; ?></span></a>
    <ul>
    <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
    <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
    </ul>
   </li>
   -->
   <li id="reports"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_reports; ?></span></a>
    <ul>
      <li><a class="parent"><?php echo $text_arservices; ?></a>
        <ul>
		  <li><a href="<?php echo $report_queryrechargelog; ?>"><?php echo $text_queryrechargelog; ?></a></li>
        </ul>
      </li>	
      <li><a class="parent"><?php echo $text_bcservices; ?></a>
        <ul>
          <li><a href="<?php echo $report_querycustomerinfo; ?>"><?php echo $text_querycustomerinfo; ?></a></li>
        </ul>
      </li>
      <li><a class="parent"><?php echo $text_bbservices; ?></a>
        <ul>
          <li><a href="<?php echo $report_querycdr; ?>"><?php echo $text_querycdr; ?></a></li>
        </ul>
      </li>
	  </ul>
  </li>

</ul>
