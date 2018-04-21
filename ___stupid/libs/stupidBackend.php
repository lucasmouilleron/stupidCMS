<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__ . "/helpers.php";
require_once __DIR__ . "/stupidEngine.php";

///////////////////////////////////////////////////////////////////////////////
class StupidBackend
{

    ///////////////////////////////////////////////////////////////////////////////
    public $stupid;

    ///////////////////////////////////////////////////////////////////////////////
    function __construct()
    {
        $this->stupid = new Stupid();
    }

    ///////////////////////////////////////////////////////////////////////////////
    function scanFiles()
    {

        $regexStd = "/\{\{" . FILE_TAG . "([^\(\)]*)\}\}/U";
        $regexMultipleStd = "/\{\{" . FILE_TAG . "(.*?)\((.*?)\)(.*?)\}\}/U";
        $regexDynamic = "/__file\(\"(.*)\"\)/U";

        function enrichiFoundFiles($content, $files, $page, $regex)
        {
            preg_match_all($regex, $content, $matches);
            $results = $matches[1];
            foreach($results as $result)
            {
                if(!array_key_exists($result, $files))
                {
                    $files[$result] = array();
                }
                if(!in_array($page, $files[$result]))
                {
                    array_push($files[$result], $page);
                }
            }
            return $files;
        }

        function enrichiFoundFilesMultiple($content, $files, $page, $regex)
        {
            preg_match_all($regex, $content, $matches);
            list($alls, $pres, $lists, $posts) = $matches;
            for($i = 0; $i < count($alls); $i++)
            {
                $list = $lists[$i];
                $listBits = explode(",", $list);
                foreach($listBits as $lb)
                {
                    $result = $pres[$i] . $lb . $posts[$i];
                    if(!array_key_exists($result, $files))
                    {
                        $files[$result] = array();
                    }
                    if(!in_array($page, $files[$result]))
                    {
                        array_push($files[$result], $page);
                    }
                }
            }
            return $files;
        }

        @mkdir(FILES_PATH);
        $files = array();
        $pages = $this->listPagesFullPath();
        foreach($pages as $page)
        {
            $content = file_get_contents($page);
            $page = str_replace(PAGES_PATH, "", $page);
            $files = enrichiFoundFiles($content, $files, $page, $regexStd);
            $files = enrichiFoundFilesMultiple($content, $files, $page, $regexMultipleStd);
            $files = enrichiFoundFiles($content, $files, $page, $regexDynamic);
        }
        $contents = json_decode(file_get_contents(CONTENTS_FILE));
        foreach($contents as $contentName => $contentPages)
        {
            $contentFile = $this->stupid->getContentFilePath($this->stupid->cleanContentName($contentName));
            if(file_exists($contentFile))
            {
                $content = file_get_contents($this->stupid->getContentFilePath($this->stupid->cleanContentName($contentName)));
//                $page = MULTIPLE_PAGE;
//                if(count($contentPages) == 1)
//                {
//                    $page = $contentPages[0];
//                }
//                $files = enrichiFoundFiles($content, $files, $page, $regexStd);
//                $files = enrichiFoundFiles($content, $files, $page, $regexDynamic);
                foreach($contentPages as $contentPage)
                {
                    $files = enrichiFoundFiles($content, $files, $contentPage, $regexStd);
                    $files = enrichiFoundFiles($content, $files, $contentPage, $regexDynamic);
                    $files = enrichiFoundFilesMultiple($content, $files, $contentPage, $regexMultipleStd);
                }
            }
        }

        file_put_contents(FILES_FILE, json_encode($files));
        return $files;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function scanContents()
    {

        $regexStd = "/\{\{" . CONTENT_TAG . "([^\(\)]*)\}\}/U";
        $regexMultipleStd = "/\{\{" . CONTENT_TAG . "(.*?)\((.*?)\)(.*?)\}\}/U";
        $regexDynamic = "/__cnt\(\"(.*)\"\)/U";

        function enrichiFoundContents($content, $contents, $page, $regex)
        {
            preg_match_all($regex, $content, $matches);
            $results = $matches[1];
            foreach($results as $result)
            {
                if(!array_key_exists($result, $contents))
                {
                    $contents[$result] = array();
                }
                if(!in_array($page, $contents[$result]))
                {
                    array_push($contents[$result], $page);
                }
            }
            return $contents;
        }

        function enrichiFoundContentsMultiple($content, $contents, $page, $regex)
        {
            preg_match_all($regex, $content, $matches);
            list($alls, $pres, $lists, $posts) = $matches;
            for($i = 0; $i < count($alls); $i++)
            {
                $list = $lists[$i];
                $listBits = explode(",", $list);
                foreach($listBits as $lb)
                {
                    $result = $pres[$i] . $lb . $posts[$i];
                    if(!array_key_exists($result, $contents))
                    {
                        $contents[$result] = array();
                    }
                    if(!in_array($page, $contents[$result]))
                    {
                        array_push($contents[$result], $page);
                    }
                }
            }
            return $contents;
        }

        @mkdir(CONTENTS_PATH);
        $contents = array();
        $pages = $this->listPagesFullPath();
        foreach($pages as $page)
        {
            $content = file_get_contents($page);
            $page = str_replace(PAGES_PATH, "", $page);
            $contents = enrichiFoundContents($content, $contents, $page, $regexStd);
            $contents = enrichiFoundContentsMultiple($content, $contents, $page, $regexMultipleStd);
            $contents = enrichiFoundContents($content, $contents, $page, $regexDynamic);
        }
        foreach($contents as $contentName => $contentPages)
        {
            $contentFile = $this->stupid->getContentFilePath($this->stupid->cleanContentName($contentName));
            if(file_exists($contentFile))
            {
                $content = file_get_contents($this->stupid->getContentFilePath($this->stupid->cleanContentName($contentName)));
//                $page = MULTIPLE_PAGE;
//                if(count($contentPages) == 1)
//                {
//                    $page = $contentPages[0];
//                }
//                $contents = enrichiFoundContents($content, $contents, $page, $regexStd);
//                $contents = enrichiFoundContents($content, $contents, $page, $regexDynamic);
                foreach($contentPages as $contentPage)
                {
                    $contents = enrichiFoundContents($content, $contents, $contentPage, $regexStd);
                    $contents = enrichiFoundContents($content, $contents, $contentPage, $regexDynamic);
                    $contents = enrichiFoundContentsMultiple($content, $contents, $contentPage, $regexMultipleStd);
                }

            }
        }

        file_put_contents(CONTENTS_FILE, json_encode($contents));
        return $contents;
    }

    /////////////////////////////////////////////////////////////////////////////
    function listPagesFullPath()
    {
        return $this->stupid->listPagesFullPath();
    }

    /////////////////////////////////////////////////////////////////////////////
    function listPagesWithExtensions()
    {
        return $this->stupid->listPagesFullPath();
    }

    /////////////////////////////////////////////////////////////////////////////
    function savePageFullPath($pagePath, $content)
    {
        $folder = dirname($pagePath);
        @mkdir($folder, 0777, true);
        file_put_contents($pagePath, $content);
        return $pagePath;
    }

    /////////////////////////////////////////////////////////////////////////////
    function savePage($pageName, $content)
    {
        $pagePath = PAGES_PATH . "/" . $this->stupid->cleanPageNameFile($pageName);
        $folder = dirname($pagePath);
        @mkdir($folder, 0777, true);
        file_put_contents($pagePath, $content);
        return $pagePath;
    }

    /////////////////////////////////////////////////////////////////////////////
    function saveContent($contentName, $content)
    {
        $contentPath = $this->stupid->getContentFilePath($contentName);
        @mkdir(dirname($contentPath), 0777, true);
        file_put_contents($contentPath, $content);
        return $contentPath;
    }

    /////////////////////////////////////////////////////////////////////////////
    function saveFile($fileName, $file)
    {
        $filePath = $this->stupid->getFilePath($fileName);
        @mkdir(dirname($filePath), 0777, true);
        move_uploaded_file($file, $filePath);
        return $filePath;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function deleteFile($fileName)
    {
        $filePath = $this->stupid->getFilePath($fileName);
        if(file_exists($filePath))
        {
            unlink($filePath);
        }
        return $fileName;
    }

    /////////////////////////////////////////////////////////////////////////////
    function listTemplates()
    {
        $files = getDirContents(PAGE_TEMPLATES_PATH);
        $templates = array();
        foreach($files as $file)
        {
            array_push($templates, array("file" => $file, "content" => file_get_contents($file)));
        }
        return $templates;
    }

    /////////////////////////////////////////////////////////////////////////////
    function listContents()
    {
        $contents = @json_decode(file_get_contents(CONTENTS_FILE), true);
        if($contents === null)
        {
            return array();
        }
        else
        {
            return $contents;
        }
    }

    /////////////////////////////////////////////////////////////////////////////
    function listContentsByPages($grouped = false)
    {
        $contents = $this->listContents();
        $contentsByPage = array();
        foreach($contents as $contentName => $contentPages)
        {
            if($grouped)
            {
                $nbs = count($contentPages);
                if($nbs > 1)
                {
                    $contentPage = MULTIPLE_PAGE;
                }
                else
                {
                    $contentPage = $contentPages[0];
                }
                if(!array_key_exists($contentPage, $contentsByPage))
                {
                    $contentsByPage[$contentPage] = array();
                }
                array_push($contentsByPage[$contentPage], ["name" => $contentName, "count" => $nbs]);
            }
            else
            {
                $nbs = count($contentPages);
                foreach($contentPages as $contentPage)
                {
                    if(!array_key_exists($contentPage, $contentsByPage))
                    {
                        $contentsByPage[$contentPage] = array();
                    }
                    array_push($contentsByPage[$contentPage], ["name" => $contentName, "count" => $nbs]);
                }
            }

        }
        return $contentsByPage;
    }

    /////////////////////////////////////////////////////////////////////////////
    function listFiles()
    {
        $files = @json_decode(file_get_contents(FILES_FILE), true);
        if($files === null)
        {
            return array();
        }
        else
        {
            return $files;
        }
    }

    /////////////////////////////////////////////////////////////////////////////
    function listFilesByPages($grouped = false)
    {
        $files = $this->listFiles();
        $filesByPage = array();
        foreach($files as $fileName => $filePages)
        {
            if($grouped)
            {
                $nbs = count($filePages);
                if($nbs > 1)
                {
                    $filePage = MULTIPLE_PAGE;
                }
                else
                {
                    $filePage = $filePages[0];
                }
                if(!array_key_exists($filePage, $filesByPage))
                {
                    $filesByPage[$filePage] = array();
                }
                array_push($filesByPage[$filePage], ["name" => $fileName, "count" => $nbs]);
            }
            else
            {
                $nbs = count($filePages);
                foreach($filePages as $filePage)
                {
                    if(!array_key_exists($filePage, $filesByPage))
                    {
                        $filesByPage[$filePage] = array();
                    }
                    array_push($filesByPage[$filePage], ["name" => $fileName, "count" => $nbs]);
                }
            }
        }
        return $filesByPage;
    }

    /////////////////////////////////////////////////////////////////////////////
    function fileExists($file)
    {
        return $this->stupid->fileExists($file);
    }

    ///////////////////////////////////////////////////////////////////////////////
    function cleanContents()
    {
        $deletedContents = array();
        $this->scanContents();
        $contents = $this->stupid->listContents(true);
        array_push($contents, CONTENTS_FILE);
        $contentFiles = getDirContents(CONTENTS_PATH);
        foreach($contentFiles as $contentFile)
        {
            if(is_dir($contentFile))
            {
                if(isDirectoryEmpty($contentFile))
                {
                    array_push($deletedFiles, $contentFile);
                    deleteDirectory($deletedContents);
                }
            }
            else if(!in_array($contentFile, $contents))
            {
                array_push($deletedContents, $contentFile);
                unlink($contentFile);
            }
        }
        return $deletedContents;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function cleanFiles()
    {
        $deletedFiles = array();
        $this->scanFiles();
        $files = $this->stupid->listFiles(true);
        array_push($files, FILES_FILE);
        $filesFiles = getDirContents(FILES_PATH);
        foreach($filesFiles as $fileFile)
        {
            if(is_dir($fileFile))
            {
                if(isDirectoryEmpty($fileFile))
                {
                    array_push($deletedFiles, $fileFile);
                    deleteDirectory($fileFile);
                }
            }
            else if(!in_array($fileFile, $files))
            {
                array_push($deletedFiles, $fileFile);
                unlink($fileFile);
            }
        }
        return $deletedFiles;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function isFileAnImage($fileName)
    {
        $imageExtensions = explode(";", FILE_IMAGE_EXTENSIONS);
        $fileExtension = @array_pop(explode(".", $fileName));
        return in_array($fileExtension, $imageExtensions);
    }

    ///////////////////////////////////////////////////////////////////////////////
    function isAuthentified()
    {
        if(!isset($_SESSION["authentified"]))
        {
            return false;
        }
        else
        {
            return $_SESSION["authentified"];
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    function lockPage($redirect = true)
    {
        if(!$this->isAuthentified())
        {
            if($redirect)
            {
                header("Location: login");
            }
            else
            {
                header("HTTP/1.0 403 Forbidden");
                echo 'You are forbidden!';
                exit;
            }
        }
    }


    ///////////////////////////////////////////////////////////////////////////////
    function login($password)
    {
        if($password == ADMIN_PASSWORD)
        {
            $_SESSION["authentified"] = true;
            header("Location: .");
        }
    }

}


?>