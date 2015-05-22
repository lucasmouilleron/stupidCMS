<div class="container">
	<footer>
		<p>&copy; stupidCMS | <a href="mailto:<?php echo CONTACT_EMAIL?>"><?php echo CONTACT_EMAIL?></a></p>
	</footer>
</div>

<?php if(DEBUG_MODE) :?>
	<div class="debug">DEBUG : <?php echo $stupidBackend->stupid->getDebugInfos();?></div>
<?php endif;?>

<script src="./js/jquery-1.11.2.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/bootstrap.validator.min.js"></script>
<script src="./js/autosize.js"></script>
<script src="./js/Markdown.Converter.js"></script>
<script src="./js/main.js"></script>

</body>
</html>