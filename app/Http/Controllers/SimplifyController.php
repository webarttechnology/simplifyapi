<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use App\Traits\CountryTrait;
class SimplifyController extends Controller
{   

    use CountryTrait;

    public function orderinfo(Request $request){
        if($request -> method()=='POST'){            
            $request -> session() -> put('county', $request -> input('county'));
            $request -> session() -> put('jurisdiction', $request -> input('jurisdiction'));   
            $condition = $this -> getuniquecountry($request -> input('county'), $request -> input('jurisdiction')); 
            
            $formData = '';
       
     
            foreach($condition[0] -> requirements as $inputd){ 
                if($inputd -> type == 'ENUMERATED'){
                   foreach($condition[1] as $optionval){
                       if($optionval -> path == $inputd -> path){
                          $optionvalue = $optionval -> options;
                       }
                   }
                   $formData.=$this -> getInput('radio', $inputd -> required, $inputd -> label, $inputd -> path, $optionvalue);
                }else if($inputd -> type == 'STRING'){
                    $formData.=$this -> getInput('STRING', $inputd -> required, $inputd -> label, $inputd -> path);
                }else if($inputd -> type == 'INTEGER'){                  
                    $formData.=$this -> getInput('INTEGER', $inputd -> required, $inputd -> label, $inputd -> path);
                }else if($inputd -> type == 'BOOLEAN'){                  
                    $formData.=$this -> getInput('BOOLEAN', $inputd -> required, $inputd -> label, $inputd -> path);
                }     
                   
            }        

            return view('case_participants', ['inputfields' => $formData]);
           
        }else{
            $country = $this -> countryList($request); 
            return view('neworder', ['country' => $country]);
        }
       
    }

    public function case_participants(Request $request, $packageId = ''){
        if($request -> method() == 'POST'){
            $this -> createPackage([]);
            die;
            echo "<pre/>";
            print_r($_POST);
            die;
        }else{
            return $this -> retrievepackage($packageId);
        }
    }

    public function add_document(Request $request){
        if($request -> method() == "POST"){
            return $this -> addDocument();
        }else{
            return $this -> addDocument();
        }
    }


    public function get_jusisdiction(Request $request){
        if($request -> ajax()){
            $countryCode = $request -> get('countryCode');
            $optionvalue = $this -> getjusisdiction($countryCode);           
            $option = "<option value=''>Select</option>";
            foreach($optionvalue as $val){
                $option=$option.'<option value="'.$val -> instrument.'">'.$val -> instrument.'</option>';
            }
            echo $option;
        }
    }

}
