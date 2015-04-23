<div class="container">
	<footer>
		<p>&copy; <?php echo GENERAL_COMPANY ?> | <a href="mailto:<?php echo GENERAL_EMAIL?>"><?php echo GENERAL_EMAIL?></a></p>
	</footer>
</div>

<?php if(DEBUG_MODE) :?>
	<div class="debug">DEBUG : <?php echo getDebugInfos();?></div>
<?php endif;?>