<?php
namespace App\Traits;    
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;

trait CountryTrait{
    private $link = "https://test.simplifile.com/sf/rest/api/erecord";
    private $key = "4DZB2NYSXMIIPZVDHVGLQI1QM<*>f345beNBZ8Pyzg2CZpwfKUhut6jwMBlIF/ZvaUaq4XA=";
    public function countryList($request){
        $endkey = '/recipients/all';
        $jdata = Http::get($this -> link.$endkey.'?key='.$this -> key);
        $data = json_decode($jdata);
        return $data -> recipientList -> recipients;
    }

    public function getuniquecountry($countryCode, $jurisdiction){
        $endkey = '/recipients/'.$countryCode.'/requirements';  
        $jdata = Http::get($this -> link.$endkey.'?key='.$this -> key);
        $data = json_decode($jdata);    
        foreach($data -> recipientRequirements -> instruments as $val){         
            if($val -> instrument == $jurisdiction){
                return [$val, $data -> recipientRequirements -> enumerations];
            }
        }
    }


    public function getjusisdiction($countryCode){
        $endkey = '/recipients/'.$countryCode.'/requirements';
        $jdata = Http::get($this -> link.$endkey.'?key='.$this -> key);
        $data = json_decode($jdata);
        return $data-> recipientRequirements -> instruments;
    }

    public function createPackage($dataArray){
        $endkey = '/packages/create';  
        $userData = 
        [
            "documents"=> [
                [
                    "submitterDocumentID"=> "123409564",
                    "name"=> "Mortgage Document Name",
                    "kindOfInstrument"=> [
                        "MORTGAGE"
                    ],
                    "indexingData"=> [
                        "mortgageConsideration"=> "280000.00",
                        "grantors"=> [
                            [
                                "firstName"=> "John",
                                "middleName"=> "Kyle",
                                "lastName"=> "Doe",
                                "type"=> "PERSON"
                            ],
                            [
                                "firstName"=> "Jane",
                                "lastName"=> "Doe",
                                "type"=> "PERSON"
                            ]
                        ],
                        "grantees"=> [
                            [
                                "nameUnparsed"=> "Sample Lender, LLC",
                                "type"=> "ORGANIZATION"
                            ]
                        ]
                    ],
                    "fileBytes"=> [
                        "TU0AKgAAAAgADAD+AAQAAAABAAAAAAEAAAMAAAABCfYAAAEBAAMAAAABDOQAAAEDAAMAAAABAAQAAAEGAAMAAAABAAAAAAERAAQAAAAhAAAAngEVAAMAAAABAAEAAAEWAAMAAAABAGQAAAEXAAQAAAAhAAABIgEaAAUAAAABAAABpgEbAAUAAAABAAABrgEoAAMAAAABASwAAAAAAAAAAAG2AAABxgAAAdYAAAHmAAAB9gAAAgYAAAIWAAACJgAAAjYAAAJGAAACVgAAAmYAAAJ2AAAChgAAApYAAAKmAAACtgAAAsYAAALWAAAC5gAAAvYAAAMGAAADFgAAAyYAAAM2AAADRgAAA1YAAANmAAADdgAAA4YAAAOWAAADpgAAA7YAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAEsAAAAAQAAASwAAAAB////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ"
                    ]
                ],
            ],
                "recipient"=> "COCNHW",
                "submitterPackageID"=> "15648974",
                "name"=> "Sample Package Name",
                "operations"=> [
                    "draftOnErrors"=> true,
                    "submitImmediately"=> false,
                    "verifyPageMargins"=> true
                ]
            ];

        $jsondata = json_encode($userData);      
          
        // $jdata = Http::withHeaders() -> post($this -> link.$endkey, array(
        //     'body'      => $jsondata,
        //     'headers'   => array('api_token' => $this -> key)
        // ));

        $jdata = Http::withHeaders([
            'api_token' => $this -> key
        ])->post($this -> link.$endkey, $userData);
        
        $data = json_decode($jdata);
        echo "<pre/>";
        print_r($data);
    }

  


    public function getInput($type, $isRequire, $lable, $fieldName, $option = []){
      
        $fields = '';
        $optionfileds = '';
        if($type == 'radio'){
            if($option != ''){
            
            foreach($option as $val){
                $optionfileds .= '<option value='.strtolower($val).'>'.strtolower($val).'</option>';
            }

                       
            $isRequireSign = $isRequire == 'ALWAYS'?'<span style="color:red;">*</span>':'';
    
            $fields.='<div class="col-md-12 mb-3">
                <lable>'.$lable.' :'.$isRequireSign.'</lable>
                <select class="form-control" name="'.$fieldName.'" id="'.$fieldName.'">
                '.$optionfileds.'
                </select>
            </div>';
            }else{
                return "Require option values";
            }
        }else if($type == 'STRING'){           
            $isRequireSign = $isRequire == 'ALWAYS'?'<span style="color:red;">*</span>':'';
            $fields .='<div class="col-md-12 mb-3">
            <lable>'.$lable.' :'.$isRequireSign.'</lable>
                <input type="text" class="form-control"  name="'.$fieldName.'" id="'.$fieldName.'" placeholder="'.$lable.'">
                <span id="organization_name_error" class="text-danger"></span>
            </div>';
        }else if($type == 'INTEGER'){            
            $isRequireSign = $isRequire == 'ALWAYS'?'<span style="color:red;">*</span>':'';
            $fields .='<div class="col-md-12 mb-3">
            <lable>'.$lable.' :'.$isRequireSign.'</lable>
                <input type="number" class="form-control" name="'.$fieldName.'" id="'.$fieldName.'" placeholder="'.$lable.'">
                <span id="organization_name_error" class="text-danger"></span>
            </div>';
        } else if($type == 'BOOLEAN'){
            $isRequireSign = $isRequire == 'ALWAYS'?'<span style="color:red;">*</span>':'';
            $fields .='<div class="col-md-12 mb-3">
                <lable>'.$lable.' :'.$isRequireSign.'</lable>
                <input type="radio" name="'.$fieldName.'" id="'.$fieldName.'" placeholder="'.$lable.'" value="Yes"> Yes
                <input type="radio" name="'.$fieldName.'" id="'.$fieldName.'" placeholder="'.$lable.'" value="No"> No
                <span id="organization_name_error" class="text-danger"></span>
            </div>';
        }

        return $fields;
    }

    public function createpayment($request){   
        print_r($request -> input());
        die;
        $endkey = '/packages/create';       
        $jdata = Http::post($this -> link.$endkey.'?key='.$this -> key, [

        ]);

        print_r($jdata);
        die;

    }

    public function retrievepackage($packageId){
        $endkey = '/packages/'.$packageId.'/retrieve';
        $jdata = Http::get($this -> link.$endkey.'?key='.$this -> key);
        $data = json_decode($jdata);
        echo "<pre/>";
        print_r($data);
        die;
    }

    public function addDocument($packageId="15648974"){
        $endkey = '/packages/'.$packageId.'/document';

        $userData = [
            "fileBytes"=> [
                "TU0AKgAAAAgADAD+AAQAAAABAAAAAAEAAAMAAAABCfYAAAEBAAMAAAABDOQAAAEDAAMAAAABAAQAAAEGAAMAAAABAAAAAAERAAQAAAAhAAAAngEVAAMAAAABAAEAAAEWAAMAAAABAGQAAAEXAAQAAAAhAAABIgEaAAUAAAABAAABpgEbAAUAAAABAAABrgEoAAMAAAABASwAAAAAAAAAAAG2AAABxgAAAdYAAAHmAAAB9gAAAgYAAAIWAAACJgAAAjYAAAJGAAACVgAAAmYAAAJ2AAAChgAAApYAAAKmAAACtgAAAsYAAALWAAAC5gAAAvYAAAMGAAADFgAAAyYAAAM2AAADRgAAA1YAAANmAAADdgAAA4YAAAOWAAADpgAAA7YAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAAQAAAAEAAAABAAAAEsAAAAAQAAASwAAAAB////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ////////////////8AEAEP////////////////ABABD////////////////wAQAQ"
            ],
            "indexingData"=> [        
              "consideration"=> "1000.00",
              "executionDate"=> "2008-06-11T06:00:00Z",
              "grantees"=> [[        
                "nameUnparsed"=> "Quay Bank, N.A.",
                "type"=> "ORGANIZATION"        
              ]],        
              "grantors"=> [[
                "firstName"=> "Gary",
                "lastName"=> "Grantor",
                "nameUnparsed"=> "GARY G GRANTOR",
                "type"=> "PERSON"        
              ]],
        
              "legalDescriptions"=> [[
                "addressCity"=> "Pittsburgh",
                "addressState"=> "PA",
                "addressStreet"=> "1001 Fifth Avenue",
                "addressZip"=> "15219"
              ]],        
              "mortgageConsideration"=> "100.00",
              "partyCount"=> 5,        
              "referenceInformation"=> [[
                "book"=> "98066",
                "date"=> "2022-09-20T06:00:00Z",
                "documentType"=> "MORTGAGE RELEASE",
                "entry"=> "96367",
                "page"=> "4444"
        
              ]],
              "taxablePercent"=> "10",
              ],
        
            "isElectronicallyOriginated"=> false,
            "kindOfInstrument"=> ["DEED"],        
            "name"=> "alternateDocumentName 1",        
            "submitterDocumentID"=> "submitterDocumentID 1"
        ];

        

        $jdata = Http::withHeaders([
            'api_token' => $this -> key
        ])->post($this -> link.$endkey, $userData);

        
        echo "<pre/>";
        print_r(json_decode($jdata));
        die;
    }
}

?>