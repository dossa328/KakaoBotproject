<?php
// 요청 저장함
$data = json_decode(file_get_contents('php://input'), true);
// 요청에서 content 항목 결정
$content = $data["content"];
$user_key = $data["user_key"];
//$info_array = array("화학제품에 대한 정보","유해성, 위험성","응급조치 요령","폭발, 화재시 대처방법","누출사고시 대처방법");
$info_array = array("의 정보","의 유해성과 위험성","의 응급조치 요령","의 폭발 및 화재시 대처방법","의 누출사고시 대처방법");
//$type = $data['type'];
// $user_key = $data[
// $content = '대화시작';
$len_info_array = count($info_array);

if(strpos($content,'*') !== false){
	//$content2 = (string)strstr($content,'*');
	//$content2_divide = explode('*', $content2);
	$content2 = str_replace('*','',$content);
	
	
	
	for($len_i=0; $len_i<$len_info_array; $len_i++)
	{
		$info_array_output = $info_array_output.$content2.$info_array[$len_i].'&';
		
		//$chemlist = $chemlist.'*'.$x->chemNameKor.'&';
		//$searchChemlist = explode('&', $chemlist);
	}
	$info_array_output = $info_array_output.'처음으로';
	
	$info_array_output_fianl = explode('&', $info_array_output);
	
	
	echo json_encode(
		array(
			'message' => array(
				'text' => "조회한 화학물질명은 [".$info_array_output."] 입니다."
//				'text' => $info_array_output
			),
			//'buttons' => array(
			//'화학제품에 대한 정보','유해성, 위험성','응급조치 요령','폭발, 화재시 대처방법','누출사고시 대처방법','처음으로'
			'keyboard' => array(
				'type' => 'buttons',
				'buttons' => $info_array_output_fianl
			)
		)
	);
}

if(strcmp($content,'대화 시작') == false || strcmp($content,'처음으로')==false) {
	echo json_encode(
		array(
			'message' => array(
				'text' => '봇 시작'
			),
            'keyboard' => array(
                'type' => 'buttons',
				'buttons' => array(
					'화학 물질명 검색','처음으로'
				)
            )
        )
    );
}
else if(strcmp($content,'화학 물질명 검색') == false){
	echo json_encode(
		array(
			'message' => array(
				'text' => '화학물질 이름을 입력해주세요'
			),
			'keyboard' => array(
				'type' => 'text'
			)
		)
	);
}

else{
//	$data = json_decode(file_get_contents('php://input'), true);
	// 요청에서 content 항목 결정
//	$content = $data["content"];
	
//	$content = "염산";

	//대응 쿼리
	//이 친구 순서 바꿔야함 
//	$query_action = "http://msds.kosha.or.kr/openapi/service/msdschem/chemdetail04?chemId=001097&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
	//$query = "http://msds.kosha.or.kr/openapi/service/msdschem/chemlist?searchCnd=0&searchWrd=$content&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
	//sgxoTq3GeTy8Q9PzzEn9zG%2FnNmENGob0tYPeVglRFTmHRKYao7vVBdyt%2Bvml1ZYOS5YQcFpXdFu2Hr0jXdhsHg%3D%3D

	//기본 정보 쿼리
	$query_info = "http://msds.kosha.or.kr/openapi/service/msdschem/chemlist?searchCnd=0&searchWrd=$content&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
	$myXMLData_info = file_get_contents($query_info);
	$xml_info = simplexml_load_string($myXMLData_info) or die("Error: Cannot create object");

//	$myXMLData_action = file_get_contents($query_action);
//	$xml_action = simplexml_load_string($myXMLData_action) or die("Error : cannot create object");
	
	$num = count($xml_info->body->items->item);
	for ($i = 0; $i<$num ; $i++)
	{
		//검색된 화학물질명 읽어오고
		$x = $xml_info->body->items->item[$i];		
		
		$chemlist = $chemlist.'*'.$x->chemNameKor.'&';
		/*
		$x = $xml_info->body->items->item[$i];
		$x_n = $xml_info->body->items->item->chemId;
	
		$query_action = "http://msds.kosha.or.kr/openapi/service/msdschem/chemdetail04?chemId=$x_n&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
		$myXMLData_action = file_get_contents($query_action);
			$xml_action = simplexml_load_string($myXMLData_action) or die("Error : cannot create object");

		$k = $xml_action->body->items->item[$i];

		//$sum_info = $sum_info.(string)"casNo -> ".$x->casNo."chemId -> ".$x->chemId."---------".$k->itemDetail.'\n';
		$sum_info = $sum_info."casNo -> ".$x->casNo."chemId -> ".$x->chemId."---------".$k->itemDetail.'\n';
		$sum_act = (string)"how to action after accident -> ".$x->itemDetail.'<br>';
		*/
	}
	$chemlist = $chemlist.'처음으로';
	$searchChemlist = explode('&', $chemlist);


	//$bu = array("1","2","3");
	echo json_encode(
		array(
			'message' => array(
				//'text' => $sun_info
				'text' => "검색한 화학물질은 ".$content."입니다."."\n"."검색된 화학물질명이 포함된 물질은 총 ".$num."개 입니다. 조회를 원하는 물질명을 선택해주세요."
			),
			'keyboard' => array(
				'type' => 'buttons',
//				'buttons' => array(
//				'염산 오라민','염산 구아니딘','염산 디에틸아민','염산 에탄올아민','염산 L-시스틴','염산 프로티오카브','데메클로사이클린 염산염','L-글루타민 산','아미노비페닐 염산염','나트륨 하이포아염산염 오수화물',$tt,$ttt,'처음으로'
				'buttons' => $searchChemlist
				
			)
		)
	);
	/*if(strpos($searchChemlist,$content)){
		echo json_encode(
			array(
				'message' => array(
					'text' => "조회할 물질 : ".$content
				)
			)
		);
	}*/
	
	//'화학제품에 대한 정보','유해성, 위험성','응급조치 요령','폭발, 화재시 대처방법','누출사고시 대처방법','처음으로'

	if(strcmp($content,'화학제품에 대한 정보')==false){
		functions_afteraction();
	}
	else if(strcmp($content,'유해성, 위험성')==false){
		functions_afteraction();
	}
	else if(strcmp($content,'응급조치 요령')==false){
		functions_afteraction();
	}
	else if(strcmp($content,'폭발, 화재시 대처방법')==false){
		functions_afteraction();
	}
	else if(strcmp($content,'누출사고시 대처방법')==false){
		functions_afteraction();
	}
	else if (strcmp($content, '적절한(부적절한)소화제') == false){
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
	}
	
	/*
	else if(strpos($content,'조회') !== false){

		$cut_stringTok = explode(' ',$content);
		$content2 = (string)$cut_stringTok[0];
		echo json_encode(
					array(
							'message' => array(
									'text' => "조회를 시도한 화학물질명은 [".$content2."] 입니다."
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
	*/
	else{
		echo json_encode(
					array(
							'message' => array(
									'text' => '오류 발생임.'
							),
							'keyboard' => array(
									'type' => 'buttons',
									'buttons' => array(
											'처음으로'
									)
							)
					)
			);
	}	
}
?>