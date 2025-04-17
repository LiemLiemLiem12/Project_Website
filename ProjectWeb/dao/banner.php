<?php
require_once 'pdo.php';
function getBanner()
{
    $sql = "SELECT * FROM slide where hide=1 ORDER BY `order` asc";
    return pdo_query($sql);
}
function showBanner($list)
{
    $html = '';
    foreach ($list as $v) {
        extract($v);
        $html .= '<div class="banner" style="background: url(upload/img/' . $img . ') bottom no-repeat; background-size:cover">';
        $html .= '<div class="banner-matter">
                        <label>Collection</label>
                        <h2>' . $name . '</h2>                       
                    </div>
                    <div class="you">
                        <span>40<label>%</label></span>
                        <small>off</small>
                    </div>
                    <p class="para-in">Some text regarding the featured product.</p>';
        $html .= '</div>';
    }

    return $html;
}