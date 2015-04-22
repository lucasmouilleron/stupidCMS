<?php

   require __DIR__."/tools.php";
   $files = glob(CACHE_PATH."/*");
   foreach($files as $file) {
      if(is_file($file))
         unlink($file);
   }

   header("Location: .");

   ?>