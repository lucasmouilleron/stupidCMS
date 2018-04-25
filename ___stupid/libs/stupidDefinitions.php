<?php

/////////////////////////////////////////////////////////////////////////////
// config.php OVERIDABLE CONFIGS
/////////////////////////////////////////////////////////////////////////////
setDefaultConstantValueIfMissing("DEBUG_MODE", true);
setDefaultConstantValueIfMissing("DEVELOPMENT_MODE", false);
setDefaultConstantValueIfMissing("ADMIN_PASSWORD", "password");
setDefaultConstantValueIfMissing("SITE_URL", "http://localhost");
setDefaultConstantValueIfMissing("PAGE_404", false);
setDefaultConstantValueIfMissing("NO_SCAN_FOLDERS", "assets;__trash");
setDefaultConstantValueIfMissing("SMTE_CACHE_ENGINE", "file");
setDefaultConstantValueIfMissing("PAGES_EDITABLE", true);

/////////////////////////////////////////////////////////////////////////////
// GENERAL
/////////////////////////////////////////////////////////////////////////////
define("STUPID_NAME", "stupidCMS");
define("STUPID_VERSION", "1.2");
define("CONTACT_EMAIL", "lucas.mouiilleron@me.com");
define("README_FILE", __DIR__ . "/../../README.md");
/////////////////////////////////////////////////////////////////////////////
// PATHS
/////////////////////////////////////////////////////////////////////////////
define("STUPID_PATH", truepath(__DIR__ . "/.."));
define("CONTENTS_PATH", truepath(__DIR__ . "/../../__contents"));
define("FILES_PATH", truepath(__DIR__ . "/../../__files"));
define("PAGES_PATH", truepath(__DIR__ . "/../../"));
define("PAGE_TEMPLATES_PATH", truepath(__DIR__ . "/../../__templates"));
define("ROOT_PATH", truepath(__DIR__ . "/../.."));
define("SMTE_CACHE_FILE_PATH", truepath(__DIR__ . "/../../__cache"));
/////////////////////////////////////////////////////////////////////////////
define("CONTENT_EXTENSION", ".md");
define("PAGES_EXTENSION", ".html");
define("DYNAMIC_PAGES_EXTENSION", ".php");
define("FILES_URL", SITE_URL . "/__files");
define("CONTENT_TAG", "CNT:");
define("FILE_TAG", "FILE:");
define("DEFINITION_TAG", "DEF:");
define("INCLUDE_TAG", "INC:");
define("IF_TAG", "IF:");
define("END_IF_TAG", "EIF");
define("BEGIN_ECHO", 'echo "');
define("END_ECHO", '";');
define("MULTIPLE_PAGE", "*");
define("CONTENTS_FILE", CONTENTS_PATH . "/__index.json");
define("FILES_FILE", FILES_PATH . "/__index.json");
define("CONTENT_MARKDOWN_PREFIX", "***");
define("SMTE_CACHE_CONTENT_PREFIX", "CNT__");
define("FILE_IMAGE_EXTENSIONS", "jpg;gif;png");
/////////////////////////////////////////////////////////////////////////////
define("SMTE_CACHE_REDIS_PORT", 6379);

?>