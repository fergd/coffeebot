<?php

if ( isset($_GET['prefix']) && preg_match('/^[a-zA-Z0-9]{15,17}$/',$_GET['prefix']) ){
   // no session
   $prefix = $_GET['prefix'];

   include 'securimage.php';

   $chars = 'ABCDEFHKLMNPRSTUVWYZ234578';
   $char_length = 4;
   $captcha_word  = '';
   for ( $i = 0; $i < $char_length; $i++ ) {
			$pos = mt_rand( 0, strlen( $chars ) - 1 );
			$char = $chars[$pos];
			$captcha_word .= $char;
   }

   $img = new securimage();
   $img->code_length = 4;

   $img->image_width   = 175;
   $img->image_height  = 60;

   if(isset($_GET['ctf_sm_captcha']) && $_GET['ctf_sm_captcha'] == 1) {
       $img->image_width   = 132;
	   $img->image_height  = 45;
   }

   //set some settings
   $img->nosession = true;
   $img->prefix = $prefix;
   $img->captcha_path = getcwd() . '/captcha-temp/';
   if(file_exists($img->captcha_path . $prefix . '.php') && is_readable( $img->captcha_path . $prefix . '.php' ) ) {
       include( $img->captcha_path . $prefix . '.php' );
       $img->captcha_word = $captcha_word;
   } else {
       $img->captcha_word = $captcha_word;
   }

   $img->use_multi_text = true;
   $img->use_transparent_text = true;
   $img->text_transparency_percentage = 20;
   $img->num_lines = 3;
   $img->perturbation = 0.6; // 1.0 = high distortion, higher numbers = more distortion
   $img->multi_text_color = array(
       '#6666FF','#660000','#3333CC','#993300','#0060CC',
       '#339900','#6633CC','#330000','#006666','#CC3366',
   );
  if (isset($_GET['difficulty']) && $_GET['difficulty'] == 1 ) {
    $img->perturbation = 0.5; // 1.0 = high distortion, higher numbers = more distortion
    $img->num_lines = 2;
    $img->multi_text_color = array('#6666FF','#660000','#3333CC','#993300','#0060CC');
  }
  if (isset($_GET['difficulty']) && $_GET['difficulty'] == 2 ) {
    $img->perturbation = 0.7; // 1.0 = high distortion, higher numbers = more distortion
    $img->num_lines = 6;
  }
  if (isset($_GET['no_trans']) && $_GET['no_trans'] == 1) {
     $img->use_transparent_text = false;
  }
   $img->charset = 'ABCDEFHKLMNPRSTUVWYZ234578';
   $img->ttf_file = getcwd() . '/ttffonts/AHGBold.ttf';   // single font
   $img->line_color = new Securimage_Color(rand(0, 64), rand(64, 128), rand(128, 255));
   $img->image_type = 'png';
   $img->background_directory = getcwd() . '/backgrounds';
   $img->ttf_font_directory  = getcwd() . '/ttffonts';
   $img->show('');
   if(!file_exists($img->captcha_path . $prefix . '.php')) {
      if ( $fh = fopen( $img->captcha_path . $prefix . '.php', 'w' ) ) {
			fwrite( $fh, '<?php $captcha_word = \'' . $captcha_word . '\'; ?>' );
			fclose( $fh );
            @chmod( $img->captcha_path . $prefix . '.php', 0755 );
      }
   }
   unset($img);
   exit;
} else {
       // session
   include 'securimage.php';

   $img = new securimage();
   $img->code_length = 4;

   $img->image_width   = 175;
   $img->image_height  = 60;

   if(isset($_GET['ctf_sm_captcha']) && $_GET['ctf_sm_captcha'] == 1) {
       $img->image_width   = 132;
	   $img->image_height  = 45;
   }

   //set some settings
   $img->form_num = 1;
   if (isset($_GET['ctf_form_num']) && is_numeric($_GET['ctf_form_num']) && $_GET['ctf_form_num'] < 100){
    $img->form_num = $_GET['ctf_form_num'];
   }
   $img->use_multi_text = true;
   $img->use_transparent_text = true;
   $img->text_transparency_percentage = 20;
   $img->num_lines = 3;
   $img->perturbation = 0.6; // 1.0 = high distortion, higher numbers = more distortion
   $img->multi_text_color = array(
       '#6666FF','#660000','#3333CC','#993300','#0060CC',
       '#339900','#6633CC','#330000','#006666','#CC3366',
   );
  if (isset($_GET['difficulty']) && $_GET['difficulty'] == 1 ) {
    $img->perturbation = 0.5; // 1.0 = high distortion, higher numbers = more distortion
    $img->num_lines = 2;
    $img->multi_text_color = array('#6666FF','#660000','#3333CC','#993300','#0060CC');
  }
  if (isset($_GET['difficulty']) && $_GET['difficulty'] == 2 ) {
    $img->perturbation = 0.7; // 1.0 = high distortion, higher numbers = more distortion
    $img->num_lines = 6;
  }
  if (isset($_GET['no_trans']) && $_GET['no_trans'] == 1) {
     $img->use_transparent_text = false;
  }
   $img->charset = 'ABCDEFHKLMNPRSTUVWYZ234578';
   $img->ttf_file = getcwd() . '/ttffonts/AHGBold.ttf';   // single font
   $img->line_color = new Securimage_Color(rand(0, 64), rand(64, 128), rand(128, 255));
   $img->image_type = 'png';
   $img->background_directory = getcwd() . '/backgrounds';
   $img->ttf_font_directory  = getcwd() . '/ttffonts';
   $img->show('');

   unset($img);
   exit;
}

?>