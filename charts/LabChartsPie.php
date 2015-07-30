<?php

namespace app\charts;

include_once(dirname(__FILE__) . '/LabCharts.php');

class LabChartsPie extends LabCharts {
    
    public function __construct () {
        $this->_type = 'p';
        $this->_colors = '0000FF';
        $this->_size = '300x150';
    }
    protected function dataToString ($data) {
        $str = 't:';
        $sum = array_sum($data);
        foreach ($data as $value) {
            $newValue = round($value / $sum * 100, 3);
            $str .= $newValue . ',';
        }
        return substr($str, 0, -1);
    }
    public function setType ($type) {
		$this->_type = $type;
	}
    public function setLabels ($labels) {
        $this->_labelsPie = $labels;
    }
    
}

?>
