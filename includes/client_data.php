<?php

//add_action('init', "isClientHasData");

function isClientHasData()
{
    $totalLastOrder = TrendeeCoupons::$totalLastOrder;

    if ($totalLastOrder !== 0):
        echo "Tiene datos";
    else:
        echo "No tiene datos";
    endif;
}
