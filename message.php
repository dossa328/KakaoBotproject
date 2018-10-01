<?php
// 요청 저장함
include("functions/functions_afteraction.php");
$data = json_decode(file_get_contents('php://input'), true);
// 요청에서 content 항목 결정
$content = $data["content"];
$user_key = $data["user_key"];
//$info_array = array("화학제품에 대한 정보","유해성, 위험성","응급조치 요령","폭발, 화재시 대처방법","누출사고시 대처방법");
$info_array = array("의정보","의 유해성과 위험성","의응급조치요령","의폭발및화재시대처방법","의누출사고시대처방법");
//$type = $data['type'];
// $user_key = $data[
// $content = '대화시작';

$chemlist_chemid = Array();
$len_info_array = count($info_array);


if(strpos($content,'#')!== false){
	functions_afteraction($content);
}

if(strpos($content,'*') !== false){
	$content2 = str_replace("*",'',$content);
	
	
	for($len_i=0; $len_i<$len_info_array; $len_i++)
	{
		$info_array_output = $info_array_output.'#'.$content2.$info_array[$len_i].'&';
	}
	$info_array_output = $info_array_output.'처음으로';
	
	$info_array_output_fianl = explode('&', $info_array_output);
	
	
	echo json_encode(
		array(
			'message' => array(
				'text' => "조회한 화학물질명은 [".$content2."] 입니다."
			),
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
				'text' => '화학사고대응봇과 대화 시작'
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
	//기본 정보 쿼리
	$content_delplus = str_replace(" ",'+',$content);
	$query_info = "http://msds.kosha.or.kr/openapi/service/msdschem/chemlist?searchCnd=0&searchWrd=$content_delplus&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
	
	$myXMLData_info = file_get_contents($query_info);
	$xml_info = simplexml_load_string($myXMLData_info) or die("Error: Cannot create object");
	
	
	$num = count($xml_info->body->items->item);
	for ($i = 0; $i<$num ; $i++)
	{
		//검색된 화학물질명 읽어오고
		$x = $xml_info->body->items->item[$i];
		
		$chemlist = $chemlist.'*'.$x->chemNameKor.'&';
		$chemName_input = (string)($x->chemNameKor);
		$chemId_input = (string)($x->chemId);
		$chemlist_chemid[$chemName_input] = $chemId_input;
		
	}
	$chemlist = $chemlist.'처음으로';
	$searchChemlist = explode('&', $chemlist);
	
	echo json_encode(
		array(
			'message' => array(
				'text' => "검색한 화학물질은 ".$content."입니다."."\n"."검색된 화학물질명이 포함된 물질은 총 ".$num."개 입니다. 조회를 원하는 물질명을 선택해주세요."
			),
			'keyboard' => array(
				'type' => 'buttons',
				'buttons' => $searchChemlist
				
			)
		)
	);

}
?>