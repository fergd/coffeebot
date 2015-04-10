<?php
if ( !defined('ABSPATH') )
	exit('Sorry, you are not allowed to access this page directly.');

$infomercials = array(
	array(
		'text' => 'Tutorial',
		'url' => "http://www.dyerware.com/main/products/easy-review-builder/easy-review-builder-plugin-for-wordpress.html",
		'icon' => "http://www.dyerware.com/images/book.png" ),
	array(
		'text' => 'FAQ',
		'url' => "http://www.dyerware.com/main/products/easy-review-builder/easy-review-builder-faq.html",
		'icon' => "http://www.dyerware.com/images/help.png" ),
	array(
		'text' => 'Forum',
		'url' => "http://www.dyerware.com/main/forum/easy-review-builder",
		'icon' => "http://www.dyerware.com/images/pencil.png" ),		
	array(
		'text' => 'Rate',
		'url' => "http://wordpress.org/extend/plugins/easy-review-builder-for-wordpress",
		'icon' => "http://www.dyerware.com/images/favorites.png" ),
);

?>
<style type="text/css">
	.wp-admin form, .wp-admin div.updated {
		margin-right: 180px
	}
	div.dyerware-adminfobar {
		float: right;
		width: 150px;
		border-left: 1px solid #ccc;
		padding-left: 1em;
		margin-left: 1em;
	}
	.dyerware-adminfobar a {
		text-decoration: none
	}
	.dyerware-adminfobar ul {
		list-style: inside;
		padding: 0;
	}
	.dyerware-adminfobar li {
		margin: .75em 0 .75em 0;
	}
	.dyerware-adminfobar hr {
		color: #ccc
	}
</style>

<div class="dyerware-adminfobar">
	<center>plugin by <strong>dyerware</strong></center>
	<hr size="0" />
	<ul>
<?php foreach ($infomercials as $each) : unset($hr) ; extract($each) ?>
		<?php if ($hr) : ?><hr size="0" /><?php endif ?>
		<li style="list-style-image:url(<?php echo $icon ?>)">
		<a target="_blank" href="<?php echo $url ?>" ><?php echo $text ?></a>
		</li>
<?php endforeach ?>
	</ul>
	<hr size="0" />
	<center><small>
		<!-- License --><?php if (@!$license) $license = 'GPL'; ?>
		<?php include "license.$license.php" ?>
		<!-- /License -->
	</small></center>
</div>
