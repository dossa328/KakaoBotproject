<?php
/* 
--------------------작업 프로세스 현황----------------------
//////////////////////////////////////////////////////
* (MSDS 정보가 없어서 교체) 구성성분의 명칭 및 함유량 -> 100%
* 유해성과 위험성 -> 50% (사진이 안뜸...)
* 응급조치 요령 -> 100%
* 폭발 및 화재시 대처방법 -> 100%
* 누출사고시 대처 방법 -> 100%
* 적절한(부적절한)소화제 -> 100%
//////////////////////////////////////////////////////
*/
function functions_afteraction($fcontent)
{	
	if(strpos($fcontent,'#')!==false)
	{
		//사용법임. $result = strstr("가나다라마바사", "다라"); 결과값 $result = "다라마바사"
		//strstr()-> 찾고 싶은 문자를 찾은 후 그 이후 값까지 반환해주는 함수.
		$fcontent_del = strstr($fcontent,"의");
		$fcontent2 = str_replace($fcontent_del,'',$fcontent);
		//$fcontent2 = str_replace(' ', '&nbsp;',$fcontent2);
		$fcontent2 = str_replace("#",'', $fcontent2);
		$fcontent2_addplus = str_replace(" ",'+',$fcontent2);
		//$sss = $finfo_array_output_fianl;
		
		$query_info = "http://msds.kosha.or.kr/openapi/service/msdschem/chemlist?searchCnd=0&searchWrd=$fcontent2_addplus&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
		$myXMLData_info = file_get_contents($query_info);
		$xml_info = simplexml_load_string($myXMLData_info) or die("Error: Cannot create object");
		
		$num = count($xml_info->body->items->item);
		for ($i = 0; $i<$num ; $i++)
		{
			//검색된 화학물질명 읽어오고
			$x = $xml_info->body->items->item[$i];
			
			//$chemlist = $chemlist.'*'.$x->chemNameKor.'&';
			//print_r($x);
			$chemName_input = (string)($x->chemNameKor);
			if($chemName_input == (string)$fcontent2){
				$chemId_input = (string)($x->chemId);
				$chemlist_chemid[$chemName_input] = $chemId_input;
			}
		}
		$fcontent2 = str_replace("의","의 ", $fcontent2);
		
		//detail 03 (3. 구성성분의 명칭 및 함유량)
		if(strpos($fcontent,'의 구성성분의 명칭 및 함유량') !== false){
			$query_detail03 = "http://msds.kosha.or.kr/openapi/service/msdschem/chemdetail03?chemId=$chemId_input&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
			$myXMLData_detail03 = file_get_contents($query_detail03);
			$xml_detail03 = simplexml_load_string($myXMLData_detail03) or die("Error : cannot create object");
			$num = count($xml_detail03->body->items->item);
			
			for ($i = 0; $i<$num ; $i++)
			{
				//검색된 화학물질명 읽어오고
				$x = $xml_detail03->body->items->item[$i];
				$detail03_itemDetail = $detail03_itemDetail." # ".(string)($x->msdsItemNameKor)."\n"." * ".(string)($x->itemDetail)."\n"."-------------------------------------------------"."\n";
				//$detail03_itemDetail = $detail03_itemDetail." # ".(string)($x->msdsItemNameKor)."\n"." * ".(string)($x->itemDetail)."\n";
			}
			$detail03_itemDetail = str_replace("|","\n * ", $detail03_itemDetail);
			echo json_encode(
				array(
					'message' => array(
						'text' => $detail03_itemDetail
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
		//detail 02 (02. 유해성과 위험성)
		else if(strpos($fcontent,'의 유해성과 위험성')!==false){
			$query_detail02 = "http://msds.kosha.or.kr/openapi/service/msdschem/chemdetail02?chemId=$chemId_input&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
			$myXMLData_detail02 = file_get_contents($query_detail02);
			$xml_detail02 = simplexml_load_string($myXMLData_detail02) or die("Error : cannot create object");
			$num = count($xml_detail02->body->items->item);
			
			for ($i = 0; $i<$num ; $i++)
			{
				//검색된 화학물질명 읽어오고
				$x = $xml_detail02->body->items->item[$i];
				$detail02_itemDetail = $detail02_itemDetail." # ".(string)($x->msdsItemNameKor)."\n"." * ".(string)($x->itemDetail)."\n"."----------------------------------------------------------------------"."\n\n";
			}
			$detail02_itemDetail = str_replace("|","\n * ", $detail02_itemDetail);
			echo json_encode(
				array(
					'message' => array(
						'text' => $detail02_itemDetail
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
		//detail 04 (04. 응급조치 요령)
		else if(strpos($fcontent,'의 응급조치 요령')!==false){
			$query_detail04 = "http://msds.kosha.or.kr/openapi/service/msdschem/chemdetail04?chemId=$chemId_input&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
			$myXMLData_detail04 = file_get_contents($query_detail04);
			$xml_detail04 = simplexml_load_string($myXMLData_detail04) or die("Error : cannot create object");			
			$num = count($xml_detail04->body->items->item);
			for ($i = 0; $i<$num ; $i++)
			{
				//검색된 화학물질명 읽어오고
				$x = $xml_detail04->body->items->item[$i];
				$detail04_itemDetail = $detail04_itemDetail." # ".(string)($x->msdsItemNameKor)."\n"." * ".(string)($x->itemDetail)."\n"."-------------------------------------------------"."\n";
				//$detail03_itemDetail = $detail04_itemDetail." # ".(string)($x->msdsItemNameKor)."\n"." * ".(string)($x->itemDetail)."\n";
			}
			$detail04_itemDetail = str_replace("|","\n * ", $detail04_itemDetail);
			echo json_encode(
				array(
					'message' => array(
						'text' => $detail04_itemDetail
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
		//detail 05 (05. 폭발 및 화재시 대처방법)
		else if(strpos($fcontent,'폭발 및 화재시 대처방법')!==false){
			$query_detail05 = "http://msds.kosha.or.kr/openapi/service/msdschem/chemdetail05?chemId=$chemId_input&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
			$myXMLData_detail05 = file_get_contents($query_detail05);
			$xml_detail05 = simplexml_load_string($myXMLData_detail05) or die("Error : cannot create object");			
			$num = count($xml_detail05->body->items->item);
			for ($i = 0; $i<$num ; $i++)
			{
				//검색된 화학물질명 읽어오고
				$x = $xml_detail05->body->items->item[$i];
				$detail05_itemDetail = $detail05_itemDetail." # ".(string)($x->msdsItemNameKor)."\n"." * ".(string)($x->itemDetail)."\n"."-------------------------------------------------"."\n";
			}
			$detail05_itemDetail = str_replace("|","\n * ", $detail05_itemDetail);
			echo json_encode(
				array(
					'message' => array(
						'text' => $detail05_itemDetail
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
		//detail 06 (06. 누출사고시 대처 방법)
		else if(strpos($fcontent,'의 누출사고시 대처 방법')!==false)
		{
			$query_detail06 = "http://msds.kosha.or.kr/openapi/service/msdschem/chemdetail06?chemId=$chemId_input&ServiceKey=QgcZ7AnmeeqX394vEJHPd7sO%2BdK6XCTAWBgvaoI7RLQXODtOpMYjr7lrDYgfRt863BqDPPpfQ4rL2C%2BROWMsUA%3D%3D";
			$myXMLData_detail06 = file_get_contents($query_detail06);
			$xml_detail06 = simplexml_load_string($myXMLData_detail06) or die("Error : cannot create object");
			
			$num = count($xml_detail06->body->items->item);
			for ($i = 0; $i<$num ; $i++)
			{
				$x = $xml_detail06->body->items->item[$i];
				$detail06_itemDetail = $detail06_itemDetail." # ".(string)($x->msdsItemNameKor)."\n\n"." * ".(string)($x->itemDetail)."\n"."----------------------------------------------------------------------"."\n";
				
			}
			$detail06_itemDetail = str_replace("|","\n * ", $detail06_itemDetail);
			echo json_encode(
				array(
					'message' => array(
						'text' => $detail06_itemDetail
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
}
?>