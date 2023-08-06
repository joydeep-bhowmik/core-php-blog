<?php
class Pagination{
  public $results_per_page="x";
  public $query="x";
  public $bind=null;
  public $page="";
  function  __construct($arr){
    $this->results_per_page=$arr['results_per_page'];
    $this->query=$arr['query'];
    if(isset($arr['bind'])){
      $this->bind=$arr['bind'];
    }
    $this->page=$arr['page'];
    $total=[];
    $total=db::select($this->query,$this->bind);
    $this->number_of_result =0;
    if($total){
      $this->number_of_result =count($total);
    }
  }
  public function get(){
    if($this->number_of_result==0) return;
    $page_first_result = (int)($this->page-1) * $this->results_per_page;  
    //find the total number of results stored in the database  
    $rows=db::select($this->query.' LIMIT ' . $page_first_result. ',' . $this->results_per_page,$this->bind);
    // echo $this->query.' LIMIT ' . (int)($this->page-1) . ',' . $this->results_per_page;  
    return $rows;
  }
  public function number_of_pages()
  {
    if($this->number_of_result){
      $number_of_page = ceil ($this->number_of_result / $this->results_per_page);  
      return $number_of_page;
    }
  }
}
?>