<?php

/**
 * @package		GA Outbound Click Tracking - Plugin for Joomla 2.5!
 * @author		No More 404
 * @copyright	Copyright (c) 2012 NoMore404.nl
 * @license		MIT license: http://opensource.org/licenses/MIT
 * @version     Joomla 1.5
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );


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
		<script type='text/javascript'>
		
		// GA Outbound Cick Tracking - by No More 404
		
		if( typeof(console) === 'undefined' ) {
    		var console = {}
    		console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = function() {};
		};
		
		if (typeof jQuery === 'undefined') {
			loadjQuery('https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js', verifyJQueryCdnLoaded);
		} else {
			main();
		}
		
		function verifyJQueryCdnLoaded() {
			if (typeof jQuery === 'undefined') {
				loadjQuery('http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js', main);
				console.log('trying fallback microsoft CDN');
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
				console.log('Error Loading jQuery from CDN')
			}
			document.getElementsByTagName('head')[0].appendChild(script_tag);
		}
		
		function main() {
			if (typeof jQuery === 'undefined') {
				throw 'ERROR: jQuery not loaded.';
			}
		
			(function($) {
				$(function() {
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