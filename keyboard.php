<?php
/*
echo <<< EOD
{
    "type" : "text"
}
EOD;
*/

echo json_encode(
	array(
        'type' => 'buttons',
        'buttons' => array('대화 시작')
    )
);

?>
