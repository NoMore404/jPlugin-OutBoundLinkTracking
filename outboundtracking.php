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
	
	//Declaring the debug stuff here to keep my JS clean and easy to read
	
	$debugaction1 = "";	
	$debugaction2 = "";
	$debugaction3 = "";
	$debugaction4 = "";
	$debugaction5 = "";
	$debugaction6 = "";
	
	if ( $this->params->get('debug')){
	
		//Precaution in case console isn't declared
		$debugaction1 = "
			if( typeof(console) === 'undefined' ) {
				var console = {}
				console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = function() {};
			};
		";
		$debugaction2 = "console.log('Debug Mode On');";
		$debugaction3 = "console.log('trying fallback microsoft CDN');";
		$debugaction4 = "console.log('Error Loading jQuery from CDN');";
		$debugaction5 = "console.log('GA Click Tracking Plugin Activated');";
		$debugaction6 = "console.log('Click Event Registerd');";
	
	}
	
	$outboundtrackingscript .="
	<script>
	
	". $debugaction1 ."
	
	// GA Outbound Cick Tracking - by No More 404
	
	". $debugaction2 ."
	
	if (typeof jQuery === 'undefined') {
		loadjQuery('https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js', verifyJQueryCdnLoaded);
	} else {
		main();
	}
	
	function verifyJQueryCdnLoaded() {
		if (typeof jQuery === 'undefined') {
			loadjQuery('http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js', main);
			". $debugaction3 ."
		} else {
			main();
		}
	}
	
	function loadjQuery(url, callback) {
		var script_tag = document.createElement('script');
		script_tag.setAttribute('src', url)
		script_tag.onload = callback;
		script_tag.onreadystatechange = function() {
			// Same but in IE style
			if (this.readyState == 'complete' || this.readyState == 'loaded') callback();
		}
		script_tag.onerror = function() {
			". $debugaction4 ."
		}
		document.getElementsByTagName('head')[0].appendChild(script_tag);
	}
	
	function main() {
		if (typeof jQuery === 'undefined') {
			throw 'ERROR: jQuery not loaded.';
		}
	
		(function($) {
			$(function() {
				var _gaq = _gaq || [];
				
				". $debugaction5 ."
				
				$('a').each(function() {
					hostname = new RegExp(location.host);
	
					// Local
					if (hostname.test(this.href)) {
						// Do Something
					}
					// Anchor
					else if (this.href.slice(0, 1) == '#') {
						// Anchor
					}
					// External
					else {
						$(this).click( function() {
							_gaq.push(['_trackEvent', 'OutboundLink', 'Click', this.href]);
							". $debugaction6 ."
						});
					}
				});
			});
		}(jQuery));
	
	}
	</script>
	";
	
	$buffer = preg_replace ("/<\/body>/", "\n".$GAtrackingcode.$outboundtrackingscript."\n</body>", $buffer);
	
	// $buffer = preg_replace ("/<\/head>/", "\n".."\n</head>", $buffer); 
	
	JResponse::setBody($buffer);

	return;
 
}
 
}