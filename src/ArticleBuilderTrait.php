<?php 
namespace FaizAhmed\ArticleBuilder;

use FaizAhmed\ArticleBuilder\ArticleBuilderService;

trait ArticleBuilderTrait
{
    public function buildArticle($username, $passsword, $dataArray = null)
    {
        if ( empty($username) || empty($passsword) ) 
            return false;

        $output = ArticleBuilderService::buildArticle($username, $passsword, $dataArray);
        echo "<pre>";
        print_r($output);
        echo "</pre>";
        die;
        return $output;
    }
}