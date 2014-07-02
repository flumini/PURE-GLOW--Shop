{extends file="parent:frontend/index/header.tpl"}
{block name="frontend_index_header_css_screen" append}

	<style type="text/css">{assign var=image value=$sArticle.image.src.7}
		#swop2 { display:none; background-image: url({$sArticle.swagVariantsInListing});
	</style>

	<link type="text/css" media="screen, projection" rel="stylesheet" href="{link file='frontend/_resources/styles/emotion_pureglow.css'}" />
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,600,700' rel='stylesheet' type='text/css'>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function()
		{
		$('#swop1').mouseover(function(){ $('#swop2').fadeIn(500)});
		$('#swop2').mouseout(function(){ $('#swop2').fadeOut(500)});
          	});
</script>

{/block}
 