<?php
function functions_afteraction($name)
{
	/*
	$query_info = "http://msds.kosha.or.kr/openapi/service/msdschem/chemlist?searchCnd=0&searchWrd=$content&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
	$myXMLData_info = file_get_contents($query_info);
	$xml_info = simplexml_load_string($myXMLData_info) or die("Error: Cannot create object");
	*/
	echo json_encode(
		array(
			'message' => array(
					'text' => 'function 진입 완료 '.$name.'입니다.'
			),
			'keyboard' => array(
				'type' => 'buttons',
				'buttons' => array(
						'화학제품에 대한 정보','유해성, 위험성','응급조치 요령','폭발, 화재시 대처방법','누출사고시 대처방법','처음으로'
				)
			)
		)
	);
}
?>