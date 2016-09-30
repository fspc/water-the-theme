<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://freesoftwarepc.com
 * @since      1.0.0
 *
 * @package    Water_The_Theme
 * @subpackage Water_The_Theme/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Water_The_Theme
 * @subpackage Water_The_Theme/public
 * @author     Jonathan Rosenbaum <gnuser@gmail.com>
 */
class Water_The_Theme_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Water_The_Theme_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Water_The_Theme_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/water-the-theme-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Water_The_Theme_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Water_The_Theme_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/water-the-theme-public.js', array( 'jquery' ), $this->version, false );

	}
	
	public function water_the_theme($thePage) {

		$doc = new DOMDocument();
		$doc->loadHTML($thePage);
		$xml_tree = simplexml_import_dom($doc);
		
		$short_code_name = (string)$xml_tree->body->div->attributes()->class;		

		$thePage ="<table id='$short_code_name'>";				
		
		// front page	
		// [usgs_custom name="Timely Information" location='03071590,03071600,03071605' parameters='00010,00065,62614'] 	
		if ($short_code_name === 'Timely Information') {
			foreach ($xml_tree->body->div->div as $div) {
			
				$value = (string)$div;
				$class = (string)$div->attributes()->class;		

				$datetime = (string)$div->attributes()->datetime;

				list($sitename, $parameter) = explode(' ', $class);
				$sitename = ucwords(preg_replace('/_/', ' ', $sitename));
				$parameter = ucwords(preg_replace('/_/', ' ', $parameter));

				if ($parameter === 'Lake Or Reservoir Water Surface Elevation Above Ngvd 1929') {
					$parameter = '(AMSL)';
				}
				if ($sitename === 'Cheat Lake Near Stewartstown Wv') {
					if ($parameter === '(AMSL)') {
						$sitename = 'Lake Level';
					} else { 
						$sitename = 'Lake';
					}				
				}
				if ($sitename === 'Cheat River At Lake Lynn Pa') {
					if ($parameter === 'Gage Height') {
						$sitename = 'Below Dam';
					} else {
						$sitename = 'At Dam';
					}				
				}
				if ($sitename === 'Cheat River At Davidson Pa') {
					$sitename = 'Below Dam';				
				}

				$thePage .= '<tr>';
				$thePage .= "<td>$sitename $parameter</td><td>$value</td><td datetime='$datetime'></td>";
				$thePage .= '</tr>';				
				
			}	
			
			//[nws_custom location='lldp1']
			$att = [
						'name' => 'Timely Information',
						'location' => 'lldp1',
						'parameters' => null,
						'date_range' => 'current',
						'order' => 'asc'
					];			
			
			$nws_custom = Water_The_Theme_Public::nws_custom($att);			
			
			$thePage .= $nws_custom;			
			
		} // end Timely Information
			
		// Statics Page - last 5 lake data
		// [usgs_custom name="Statistics" location='03071590,03071600' parameters='00010,00065,62614' date_range='previous,5,16:00' order='desc'] 		
		if ($short_code_name === 'Statistics') {

			$statistics = [];			
			
			foreach ($xml_tree->body->div->div as $div) {
			
				$value = (string)$div;
				$class = (string)$div->attributes()->class;		

				$datetime = (string)$div->attributes()->datetime;

				list($sitename, $parameter) = explode(' ', $class);
				$sitename = ucwords(preg_replace('/_/', ' ', $sitename));
				$parameter = ucwords(preg_replace('/_/', ' ', $parameter));

				if ($parameter === 'Lake Or Reservoir Water Surface Elevation Above Ngvd 1929') {
					$parameter = '(AMSL)';
				}
				if ($sitename === 'Cheat Lake Near Stewartstown Wv') {
					if ($parameter === '(AMSL)') {
						$sitename = 'Lake Level';
					} else { 
						$sitename = 'Lake';
					}				
				}
				if ($sitename === 'Cheat River At Lake Lynn Pa') {
					$sitename = 'Tail Race Level';
					$parameter = ' (AMSL)';				
				}
				if ($sitename === 'Cheat River At Davidson Pa') {
					$sitename = 'Below Dam';				
				}
	
				if ($sitename === 'Tail Race Level') {
					$value = (float)$value + 776.63;				
				}
				$statistics[] = "<tr><td>$sitename $parameter</td><td>$value</td><td datetime='$datetime'></td></tr>";		
				
				//PC::debug($value . " " . $sitename . " " . $parameter);	
			} // end foreach
			
			list($array1, $array2, $array3) = array_chunk($statistics, ceil(count($statistics) / 3));
			foreach($array1 as $key => $value ) {
				$thePage .= $array2[$key];				
				$thePage .= $array3[$key];				
				$thePage .= $value;
			}

			//PC::debug($count);	
						
		}	// end Statistics	
		
		$thePage .= '</table>';
		return $thePage;
	
	} // function water the theme
	
	public function nws_custom( $att ) {
			
		
		list($name, $location, $parameters, $date_range, $order) = [
																				$att['name'],
																				$att['location'],
																				$att['parameters'],
																				$att['date_range'],
																				$att['order']
																			];
		$locations = explode(',', $location);
		
		// Tail Race Level = Gage Datum + Gage Height
		foreach ($locations as $location) {

			$thePage = get_transient('nws_custom-' . $name . $location . $date_range . $parameters . $order );

			if ( !$thePage ) {
			
			$url = "http://water.weather.gov/ahps2/hydrograph_to_xml.php?gage=$location&output=xml";

			$response = wp_remote_get( $url );
			$data = wp_remote_retrieve_body( $response );

			if ( ! $data ) {
				return 'Nation Weather Service is not Responding.';
			}


			$xml_tree = simplexml_load_string( $data );
			if ( False === $xml_tree ) {
				return 'Unable to parse NWS XML';
			}
			
			// space to underscore; all lower case; only special character allowed is underscored
			$SiteName = (string)$xml_tree->attributes()->name;	
			$SiteName = preg_replace('/[^A-Za-z0-9_]/', '', strtolower(preg_replace('/\s+/', '_', $SiteName)));

			$waterlevel = (string)$xml_tree->zerodatum;								
					
			$c = 0;			
			foreach ( $xml_tree->observed->datum as $datum ) {
					
				// in javascript this works out of the box (* 1000)
				$datetime = strtotime($datum->valid) * 1000;
				$gageheight = $datum->primary;
				$waterflow = (string)$datum->secondary;
				
				if ($waterflow === '-999' || $waterflow === 0) {
					$waterflow = '0 cfs';						
				} else {
					$waterflow = $waterflow * 1000;	
					$waterflow = $waterflow . " cfs";					
				}
						
				$tail_race_level = (float)$waterlevel + (float)$gageheight;

				if($c === 0 && $date_range === 'current') {
					$thePage .= "<tr><td>Tail Race Level (AMSL)</td><td>$tail_race_level ft</td><td datetime='$datetime'></td></tr>";
					//$thePage .= "<tr><td>Gage Datum (AMSL)</td><td>$waterlevel ft</td><td datetime='$datetime'></td></tr>";
					//$thePage .= "<tr><td>Tail Water Flow</td><td>$waterflow</td><td datetime='$datetime'></td></tr>";
					break;			
				} else {
					$thePage .= "<tr><td>Tail Race Level (AMSL)</td><td>$tail_race_evel</td><td datetime='$datetime'></td></tr>";
					//$thePage .= "<tr><td>Gage Datum (AMSL)</td><td>$waterlevel ft</td><td datetime='$datetime'></td></tr>";
					//$thePage .= "<tr><td>Tail Water Flow</td><td>$waterflow</td><td datetime='$datetime'></td></tr>";				
				}				
				$c++;			
					
			} // foreach xml_tree as site data		
			
		} // foreach NWS location		


		set_transient( 'nws_custom-' . $name . $location . $date_range . $parameters . $order, $thePage, 60 * 15 );
		}

		return $thePage;
	}	// function nws_custom	

}
