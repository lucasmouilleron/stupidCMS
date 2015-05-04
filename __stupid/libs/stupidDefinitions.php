<?php

/////////////////////////////////////////////////////////////////////////////
// DEFINES
/////////////////////////////////////////////////////////////////////////////
define("STUPID_PATH",truepath(__DIR__."/.."));
define("CONTENTS_PATH",truepath(__DIR__."/../_contents"));
define("IMAGES_PATH",truepath(__DIR__."/../_images"));
define("PAGES_PATH",truepath(__DIR__."/../../"));
/////////////////////////////////////////////////////////////////////////////
define("PAGES_EXTENSION",".html");
define("IMG_URL","./__stupid/_images/");
define("CONTENT_TAG","CNT:");
define("IMAGE_TAG","IMG:");
define("DEFINITION_TAG","DEF:");
define("INCLUDE_TAG","INC:");
/////////////////////////////////////////////////////////////////////////////
define("CONTENTS_FILE",CONTENTS_PATH."/__index.json");
define("IMAGES_FILE",IMAGES_PATH."/__index.json");
define("CONTENT_MARKDOWN_PREFIX","***");
/////////////////////////////////////////////////////////////////////////////
define("SMTE_CACHE_FILE_PATH",truepath(__DIR__."/../_cache"));
define("SMTE_CACHE_REDIS_PORT",6379);
define("SMTE_CACHE_CONTENT_PREFIX","CNT__");
/////////////////////////////////////////////////////////////////////////////
define("CONTACT_EMAIL","lucas.mouiilleron@me.com");
define("README_FILE",__DIR__."/../../README.md");

?>