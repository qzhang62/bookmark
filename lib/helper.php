<?php
/**
 * Created by PhpStorm.
 * User: Qiang
 * helper functions
 */
function inBookmark(){  // check if this page is in app BOOKMARK
    if(!defined("BOOKMARK")){
        header("Location: http://theos.in/");
    }
}