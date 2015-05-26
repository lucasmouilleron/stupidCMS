<div class="container">
	<footer>
		<p>&copy; <?php echo STUPID_NAME?> | <a href="mailto:<?php echo CONTACT_EMAIL?>"><?php echo CONTACT_EMAIL?></a></p>
	</footer>
</div>

<?php if(DEBUG_MODE) :?>
	<div class="debug">DEBUG : <?php echo $stupidBackend->stupid->getDebugInfos();?></div>
<?php endif;?>

<script src="./js/vendors/jquery-1.11.2.min.js"></script>
<script src="./js/vendors/bootstrap.min.js"></script>
<script src="./js/vendors/bootstrap.validator.min.js"></script>
<script src="./js/vendors/autosize.js"></script>
<script src="./js/vendors/Markdown.Converter.js"></script>
<script src="./js/main.js"></script>

</body>
</html>