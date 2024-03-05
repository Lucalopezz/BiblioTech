<?php 
function xssProtect ($value){
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}
