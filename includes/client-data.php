<?php


add_action('init', "isClientHasData");


function isClientHasData()
{
    $TrendeeCoupons = new TrendeeCoupons();

    if ($TrendeeCoupons->totalLastOrder !== 0):
        // echo "Tiene datos";
    else:
        // echo "No tiene datos";
    endif;
}
