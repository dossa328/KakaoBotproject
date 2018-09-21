<?php
function functions_afteraction($fcontent)
{
	//한번 검색하고나서 다시 돌아올때 검색했던 키워드가 날아감. 그래서 초기화되는 현상이 벌어지고 있음.
	$info_array = array("의정보","의 유해성과 위험성","의응급조치요령","의폭발및화재시대처방법","의누출사고시대처방법");
	
	for($len_i=0; $len_i<$len_info_array; $len_i++)
	{
		$info_array_output = $info_array_output.'#'.$content2.$info_array[$len_i].'&';
		
		//$chemlist = $chemlist.'*'.$x->chemNameKor.'&';
		//$searchChemlist = explode('&', $chemlist);
	}
	$info_array_output = $info_array_output.'처음으로';
	
	$info_array_output_fianl = explode('&', $info_array_output);
	
	
	/*
	$query_info = "http://msds.kosha.or.kr/openapi/service/msdschem/chemlist?searchCnd=0&searchWrd=$fcontent&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
	$myXMLData_info = file_get_contents($query_info);
	$xml_info = simplexml_load_string($myXMLData_info) or die("Error: Cannot create object");
	*/
	//'buttons' => array(
	//'화학제품에 대한 정보','유해성, 위험성','응급조치 요령','폭발, 화재시 대처방법','누출사고시 대처방법','처음으로'
	/*
	echo json_encode(
		array(
			'message' => array(
					'text' => 'function 진입 완료 '.$name.'입니다.'
			),
			'keyboard' => array(
				'type' => 'buttons',
				'buttons' => $finfo_array_output_fianl
			)
		)
	);
	*/
	//str_replace(' ', '&nbsp;', $stringVariable); //공백 보존?
	
	$fcontent2 = str_replace(' ', '&nbsp;',$fcontent);
	$fcontent2 = str_replace("#",'', $fcontent2);
	//$sss = $finfo_array_output_fianl;
	
	$query_info = "http://msds.kosha.or.kr/openapi/service/msdschem/chemlist?searchCnd=0&searchWrd=$fcontent2&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
	$myXMLData_info = file_get_contents($query_info);
	$xml_info = simplexml_load_string($myXMLData_info) or die("Error: Cannot create object");
	
	$k = $xml_info->body->items->item[0];
	
	$chem_chemId = $xml_info->body->items->item->chemId;
	
	$sum_info = $sum_info.'화학 제품에 대한 정보'."\n"."casNo : ".$k->casNo."\n"."chemId -> ".$k->chemId;
	
	$fcontent2 = str_replace("의","의 ", $fcontent2);
	
//	if(strpos($fcontent2,'의 정보') !== false){
	//if(strcmp($fcontent,'의 정보')==false){
		echo json_encode(
			array(
				'message' => array(
					//'text' => $sum_info
					'text' => $fcontent2
				),
				/*'keyboard' => array(
					'type' => 'buttons',
					'buttons' => array(
					'소화제는 없습니다','처음으로','소화제 있음'
					)
				)
				*/
				
				'keyboard' => array(
					'type' => 'buttons',
					'buttons' => $info_array_output_fianl
				)
					
			)
		);
//	}
	/*
	else if(strcmp($fcontent,'의 유해성과 위험성')==false){
		
	}
	else if(strcmp($fcontent,'의 응급조치 요령')==false){
		$query_action = "http://msds.kosha.or.kr/openapi/service/msdschem/chemdetail04?chemId=$fcontent&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
		$myXMLData_action = file_get_contents($query_action);
		$xml_action = simplexml_load_string($myXMLData_action) or die("Error : cannot create object");
	}
	else if(strcmp($fcontent,'폭발 및 화재시 대처방법')==false){
		
	}
	else if(strcmp($fcontent,'의 누출사고시 대처방법')==false){
		
	}
	else if (strcmp($fcontent, '적절한(부적절한)소화제') == false){
		echo json_encode(
			array(
				'message' => array(
					'text' => '적절한 소화제는 까스활명수'
				),
				'keyboard' => array(
					'type' => 'buttons',
					'buttons' => array(
					'소화제는 없습니다','처음으로','소화제 있음'
					)
				)
			)
		);
	}*/
}
?>