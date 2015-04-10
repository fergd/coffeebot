<?php
/*
Plugin Name: Easy Review Builder
Version: 0.3
Plugin URI: http://www.dyerware.com/main/products/easy-review-builder
Description: Creates a customizable star-based review summary table from a shortcode
Author: dyerware
Author URI: http://www.dyerware.com
*/
/*  Copyright © 2009, 2010  dyerware
    Support: support@dyerware.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
  
class wpEasyReview
{
    private $reviewNum = 0;
    
	// Database Settings
    var $DEF_TITLE = "Review Score";
	var $DEF_RATINGMAX = 5;
	var $DEF_OVERALL = true;
	var $DEF_ICON = "star";
	var $DEF_SUMMARY = "Average score from all categories.";
	var $DEF_TABLECSS = "easyReviewWrapper";
	
	var $op; 
    
	public function __construct()
    { 
       $jsDir = plugins_url ( plugin_basename ( dirname ( __FILE__ ) ) ) . 'js';       
       $this->init_options_map();
       $this->load_options();

	   if (is_admin()) 
	   {
			add_action('admin_menu', array(&$this, 'add_admin_menu'));
	   }                    
    }

    function CTXID() 
    { 
        return get_class($this); 
    }
    
	function addCSS() 
	{
		echo '<link type="text/css" rel="stylesheet" href="' . plugins_url ( plugin_basename ( dirname ( __FILE__ ) ) ) .'/easy-review-builder.css" />';
	
	}
	
 	function add_admin_menu() 
 	{
		$title = 'Easy Review Builder';
		add_options_page($title, $title, 10, __FILE__, array(&$this, 'handle_options'));
	}
	
	function init_options_map() 
	{
		$opnames = array(
			'DEF_TITLE', 'DEF_RATINGMAX', 'DEF_OVERALL', 'DEF_ICON', 'DEF_SUMMARY', 'DEF_TABLECSS'
		);
		$this->op = (object) array();
		foreach ($opnames as $name)
			$this->op->$name = &$this->$name;
	}
	
	function load_options() 
	{
		$context = $this->CTXID();
		$options = $this->op;
		$saved = get_option($context);
		if ($saved) foreach ( (array) $options as $key => $val ) 
		{
			if (!isset($saved->$key)) continue;
			$this->assign_to($options->$key, $saved->$key);
		}
		// Backward compatibility hack, to be removed in a future version
		//$this->migrateOptions($options, $context);
	}
		
	function handle_options() 
	{
		$actionURL = $_SERVER['REQUEST_URI'];
		$context = $this->CTXID();
		$options = $this->op;
		$updated = false;
		$status = '';
		if ( $_POST['action'] == 'update' ):
			check_admin_referer($context);
			if (isset($_POST['submit'])):
				foreach ($options as $key => $val):
					$bistate = is_bool($val);
					if ($bistate):
						$newval = isset($_POST[$key]);
					else:
						if ( !isset($_POST[$key]) ) continue;
						$newval = trim( $_POST[$key] );
					endif;
					if ( $newval == $val ) continue;
					$this->assign_to($options->$key, $newval);
					$updated = true; $status = 'updated';
				endforeach;
				if ($updated): update_option($context, $options); endif;
			elseif (isset($_POST['reset'])):
				delete_option($context);
				$updated = true; $status = 'reset';
			endif;
		endif;
		include 'easy-review-settings.php';
	}
	
	private function assign_to(&$var, $value) 
	{
		settype($value, gettype($var));
		$var = $value;
	}	
 
    private function translate_numerics(&$value, $key) 
    {
        if ($value == 'false') {
        	$value = false;
        } elseif ($value == 'true') {
            $value = true;
        }
    }        
            
	public function process_shortcode($atts) 
	{	
	    $haveIssue = FALSE;
	    $nearKey = "";
	    $nearValue = "";
	    
	    if ($atts)
	    {
    	    foreach ($atts as $key => $att)
    	    {
    	       $keyval = (int)$key;
    	       if ($keyval != 0 || strpos($key, "0") === 0)
    	       {
                    $haveIssue = TRUE;
                    $nearKey = $keyval;
                    $nearValue = $att;
                    break;
    	       }
    	    }
	    }
	    	
	    if ($haveIssue === TRUE)
	       return "<p><b>EASY REVIEW BUILDER SHORTCODE ERROR</b><lu><li>Check for misspelled parameters (case matters)</li><li>Check for new lines (all must reside on one long line)</li><li>Error near [" . $key . "], [" . $att . "]</li></lu><br/>For assistance, please visit <a>http://www.dyerware.com/main/products/easy-review-builder</a></p>";
	    
	             
        $chartConfig = shortcode_atts( array(
                'title' => $this->DEF_TITLE,
                'ratingmax' => $this->DEF_RATINGMAX,
                'overall' => ($this->DEF_OVERALL == true)?'true':'false',
                'icon' => $this->DEF_ICON,
                'summary' => $this->DEF_SUMMARY,
                'cat1title' => NULL,
                'cat2title' => NULL,
                'cat3title' => NULL,
                'cat4title' => NULL,
                'cat5title' => NULL,
                'cat6title' => NULL,
                'cat7title' => NULL,
                'cat8title' => NULL,
                'cat1detail' => 'Summarize why you chose this rating',
                'cat2detail' => 'Summarize why you chose this rating',
                'cat3detail' => 'Summarize why you chose this rating',
                'cat4detail' => 'Summarize why you chose this rating',
                'cat5detail' => 'Summarize why you chose this rating',
                'cat6detail' => 'Summarize why you chose this rating',
                'cat7detail' => 'Summarize why you chose this rating',
                'cat8detail' => 'Summarize why you chose this rating',
                'cat1rating' => 0,
                'cat2rating' => 0,
                'cat3rating' => 0,
                'cat4rating' => 0,
                'cat5rating' => 0,
                'cat6rating' => 0,
                'cat7rating' => 0,
                'cat8rating' => 0,
                'tablecss' => $this->DEF_TABLECSS,)
			    , $atts );

	    // Translate strings to numerics
	    array_walk($chartConfig, array($this, 'translate_numerics'));
	          
	    $this->reviewNum++;
		$reviewDiv = 'easyReviewDiv' . $this->reviewNum;
	
    	$ratingMax = (int)$chartConfig["ratingmax"];
    	if ($ratingMax > 15)
    	   $ratingMax = 15;

   	   $starFullImg = plugins_url ( plugin_basename ( dirname ( __FILE__ ) ) ) .'/icons/' .$chartConfig['icon'].'_full.png';
       $starHalfImg = plugins_url ( plugin_basename ( dirname ( __FILE__ ) ) ) .'/icons/' .$chartConfig['icon'].'_half.png';
       $starEmptyImg = plugins_url ( plugin_basename ( dirname ( __FILE__ ) ) ) .'/icons/' .$chartConfig['icon'].'_empty.png';
  	
	   $output = "<div class='" . $chartConfig["tablecss"] . "'> <table class='easyReviewTable'  border='0' style='text-align:center;' align='center' bgcolor='FFFFFF'>";
	
       // Optional title   post-footer postwrap
       if (strlen($chartConfig["title"]))
           $output .= "<head ><tr><th class='easyReviewTitle' style='vertical-align:middle;font-size:120%' colspan='2'>" . $chartConfig["title"] . "</th></tr></head>";
           
        
       // For each valid entry
       $firstRow = TRUE;
       $average = 0;
       $numRows = 0;
    		
	   for ($x = 0; $x < 8; $x++)
       {
      	   $keyTitle = "cat" . ($x+1) . "title";
      	   $keyDetail = "cat" . ($x+1) . "detail";
      	   $keyRating = "cat" . ($x+1) . "rating";
      	   $rating = (float)$chartConfig[$keyRating];
      	   $halfStar = FALSE;
      	   if ($rating < round($rating))
      	       $halfStar = TRUE;
      	   $ratingFloor = floor($rating);
      	   
      	   $rowStr = "";
      	   if (strlen($chartConfig[$keyTitle]))
      	   {
      	       if ($firstRow == FALSE)
      	         $rowStr .= "<tr><td colspan='2' style='border-bottom:1px #ddd solid;'></td></tr>";     
            
      	       $rowStr .= "<tr><th class='easyReviewRow' style='width:100%'>".$chartConfig[$keyTitle]."</th>"	                      
      	               .  "<td class='easyReviewRow' style='white-space:nowrap;'>";
          	           
          	           
      	       for ($y = 0; $y < $ratingMax; $y++)
      	       {
      	          if ($y + 1 <= $ratingFloor)
      	               $rowStr .= "<img class='easyReviewImage' src='" . $starFullImg . "'/>";
                    else if ($y + 1 == $ratingFloor + 1 && $halfStar)
                         $rowStr .= "<img class='easyReviewImage'  src='" . $starHalfImg . "'/>";
                    else            
                         $rowStr .= "<img class='easyReviewImage' src='" . $starEmptyImg . "'/>";
      	       }
 
               $rowStr .= "</td></tr>";
       	       $rowStr .= "<tr><td colspan='2' class='easyReviewRow'>".$chartConfig[$keyDetail]."</td></tr>";     	       
      	       
      	                 	           
      	       if ($firstRow)
      	           $firstRow = FALSE;
      	           
      	       $numRows++;
      	       $average +=  $rating;
      	   }
      	   
      	   $output .= $rowStr;
         }
	
	  $output .= "</table>";
	  	  
      // Add conclusion
      if ($firstRow == false && $chartConfig['overall'] == true)
       {
           $halfStar = false;
           
           $average = $average / $numRows;
    
           if ($average < round($average))
               $halfStar = TRUE;
           $average = floor($average);
      
           $output .= "<div class='easyReviewConclude'><table class='easyReviewTable'  border='0' style='text-align:center;' frame='box' align='center' bgcolor='FFFFFF'>";
           $output .= "<tr><th class='easyReviewEnd' style='width:100%'>Overall</th>"
                   . "<td class='easyReviewEnd' style='white-space:nowrap;'>";	           
              
       	   $endStr = "";   
           for ($y = 0; $y < $ratingMax; $y++)
           {
               if ($y + 1 <= $average)
                $endStr .= "<img class='easyReviewImage' src='" . $starFullImg . "'/>";
               else if ($y + 1 == $average + 1 && $halfStar)
                $endStr .= "<img class='easyReviewImage' src='" . $starHalfImg . "'/>";
               else            
                $endStr .= "<img class='easyReviewImage' src='" . $starEmptyImg . "'/>";
           }
           
           $output .= $endStr . "</td></tr>";
           
           $output .= "<tr><td colspan='2' class='easyReviewEnd'>" . $chartConfig['summary'] . "</td></tr></table></div>";	
       }
       
    $output .= "</div>";
    return $output;  
   }
}  

// Instantiate our class
$wpEasyReview = new wpEasyReview();

/**
 * Add filters and actions
 */

add_action('wp_head', array($wpEasyReview, 'addCSS'));
add_shortcode('easyreview',array($wpEasyReview, 'process_shortcode'));
?>
