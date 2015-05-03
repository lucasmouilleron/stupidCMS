<?php

/////////////////////////////////////////////////////////////////////////////
// DEFINES
/////////////////////////////////////////////////////////////////////////////
define("CONTENTS_PATH",truepath(__DIR__."/../_contents"));
define("IMAGES_PATH",truepath(__DIR__."/../_images"));
define("PAGES_PATH",truepath(__DIR__."/../../"));
define("PAGES_EXTENSION",".html");
define("SMTE_CACHE_PATH",truepath(__DIR__."/../_cache"));
define("CONTENT_TAG","CNT:");
define("IMAGE_TAG","IMG:");
define("DEFINITION_TAG","DEF:");
define("INCLUDE_TAG","INC:");
define("CONTENTS_FILE",CONTENTS_PATH."/__index.json");
define("IMAGES_FILE",IMAGES_PATH."/__index.json");
define("IMG_URL","./__stupid/_images/");
define("README_FILE",__DIR__."/../../README.md");
define("CONTENT_MARKDOWN_PREFIX","***");

?>