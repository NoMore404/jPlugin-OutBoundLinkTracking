<?php

/**
 * @package		GA Outbound Click Tracking - Plugin for Joomla 2.5!
 * @author		No More 404
 * @copyright	Copyright (c) 2012 NoMore404.nl
 * @license		MIT license: http://opensource.org/licenses/MIT
 * @version     Joomla 2.5
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.html.parameter' );

class plgSystemOutboundTracking extends JPlugin {

function plgSystemOutboundTracking(&$subject, $params) { 
 
	parent::__construct($subject, $params); 
    
	$this->plugin = &JPluginHelper::getPlugin('system', 'outboundtracking');
    
	$this->params = new JParameter($this->plugin->params);
}

function onAfterRender(){

		$app =& JFactory::getApplication();
		
		if( $app->isAdmin() ){
			return;
		}
		
		$GAtrackingcode="";
		$outboundtrackingscript="";
		
		$buffer = JResponse::getBody();
		
		$header = explode('</head>',$buffer);
	 
		if ($this->params->get('googleid')){ 
		
		  	$GAtrackingcode .="
				<script type='text/javascript'>

				  var _gaq = _gaq || [];
				  _gaq.push(['_setAccount', '".$this->params->get('googleid')."']);
				  _gaq.push(['_trackPageview']);

				  (function() {
					var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				  })();

				</script>";   
		}
		
		$outboundtrackingscript .="
		<script>
		// GA Outbound Cick Tracking - by No More 404
		(function($) {
			$(function(){
				$('a').each( function() {            
					hostname = new RegExp(location.host);
					
					// Local link...
					if(hostname.test( this.href )){
						// Do Nothing 
					}
					// Anchor link
					else if( this.href.slice(0, 1) == '#'){
  						// Anchor Event?!
					}
					// Link not containing current host
					else {                
						$(this).on('click', function(){
							_gaq.push(['_trackEvent', 'OutboundLink', 'Click', this.href ]);
						});
					}
				});
			});
		}(jQuery));
		</script>
		";
		
		$buffer = preg_replace ("/<\/body>/", "\n".$GAtrackingcode.$outboundtrackingscript."\n</body>", $buffer);
		
		// $buffer = preg_replace ("/<\/head>/", "\n".."\n</head>", $buffer); 
		
		JResponse::setBody($buffer);
	
	return;
 
}
 
}