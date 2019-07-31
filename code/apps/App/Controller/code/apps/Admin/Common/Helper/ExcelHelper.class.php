<?php
namespace Common\Helper;
class ExcelHelper{

	public function exportExcel($expCellName,$expTableData,$expTitle = ''){
		if(!$expTitle) $expTitle = date('YmdHis');
		$xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
		$fileName = $expTitle;//or $xlsTitle 文件名称可根据自己情况设定
		$cellNum = count($expCellName);
		$dataNum = count($expTableData);
		vendor("PHPExcel.PHPExcel");
		$objPHPExcel = new \PHPExcel();
		$cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

		for($i=0;$i<$cellNum;$i++){
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'1', $expCellName[$i]); 
		} 
		$i = 0;
		foreach ($expTableData as $key => $data) {
			//宽度自适应
			$j = 0;
			foreach ($data as $k => $v) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($cellName[$j])->setAutoSize(true);  
		    	$objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+2), $v,\PHPExcel_Cell_DataType::TYPE_STRING);
		    	$j++;
			}
			$i++;
		}



		ob_end_clean();//清除缓冲区,避免乱码
		header('pragma:public');
		header('Cache-Control: max-age=0');
		header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
		header("Content-Disposition:attachment;filename=$fileName.xls");
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
		$objWriter->save('php://output'); 
		exit;   
	}
}