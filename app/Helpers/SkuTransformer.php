<?php

namespace app\Helpers;

use Illuminate\Support\Facades\Log;

class SkuTransformer
{
    public static function transform($selectedOptions = [])
    {
        if ($selectedOptions) {
        // Check selected options and transform SKU accordingly
            foreach ($selectedOptions as $option) {
                
                $name = preg_replace('/[^A-Za-z0-9\s\/-]/', '', $option['name']);
                $name = trim($name);
                $name = preg_replace('/\s+/', ' ', $name);

                // $value = preg_replace('/[^A-Za-z0-9\s\/-]/', '', $option['value']);
                // $value = trim($value);
                // $value = preg_replace('/\s+/', ' ', $value);

                $value = preg_replace('/[^A-Za-z0-9.\s\/-]/', '', $option['value']);  // Allow decimal points
                $value = trim($value);
                $value = preg_replace('/\s+/', ' ', $value);


                // To see logs on server 
                // error_log("Option name: " . $name);
                // error_log("Option value: " . $value);

                Log::info("name: " . $name);
                Log::info("value : " . $value);

                // this array check if there name exist in selected name
                $all_Selected_Name = [
                                       'SIZES JIC',
                                       'Bulkheads JIC 832FS',
                                       'Hosetail x Thread',
                                       'Hose tail x Thread',
                                       'Hose Tail x Thread',
                                       'JIC x UNO Male/Male',
                                       'BSPP Plug',
                                       'BSPT x BSPT Male/Male 90',
                                       'SAE100R2AT',
                                       'SAE 100R2 04 length',
                                       'SAE 100R2AT-12 3/4 Bore LENGTH',
                                       'Bulkheads JIC 832FS',
                                       'ORFS Straight Female',
                                       'ORFS 90 SWEPT BEND',
                                       'ORFS SIZES FEMALE',
                                       'BPM-JIF-',
                                       'BSPP x BSPP Male/Male Nipples',
                                       'BSPP x BSPT Male/Male Nipples',
                                       'BSPT MALE K',
                                       'BSPP FEMALE K',
                                       'JIC Female Straight good for 1 and 2 wire hose',
                                       'BSPP x JIC Male/Male',
                                       'MALE JIC K',
                                       '90 JIC x UNO Male/Male Elbow',
                                       'ORFS Duro 90 Orings',
                                       'Sizes',
                                       '90',
                                       '950SA',
                                       'Female',
                                    ];

                if (in_array($name, $all_Selected_Name)) {
                        
                // if (($name == 'Hosetail x Thread') || ($name == 'Hose tail x Thread') || ($name == 'JIC x UNO Male/Male') || $name == 'BSPT x BSPT Male/Male 90°') {
                    switch ($value) {
                        // Hydraulic Caps & Plugs Jic Various Sizes
                        case '7/16 PLUG':
                            return 'JIM-07';
                        case '7/16 CAP':
                            return 'JIF-07';
                        case '1/2 CAP':
                            return 'JIF-08';
                        case '1/2 PLUG':
                            return 'JIM-08'; 
                        case '9/16 PLUG':
                            return 'JIM-09'; 
                        case '9/16 CAP':
                            return 'JIF-09';
                        case '3/4 PLUG':
                            return 'JIM-12'; 
                        case '3/4 CAP':
                            return 'JIF-12';
                        case '7/8 PLUG':
                            return 'JIM-14'; 
                        case '7/8 CAP':
                            return 'JIF-14';
                        case '1 1/16 PLUG':
                            return 'JIM-17'; 
                        case '1 1/16 CAP':
                            return 'JIF-17'; 
                        //JIC Male Field Fit Reusable Hydraulic Hose Fitting (Straight)
                        case '1/4 Reusable Hose Tail x 7/16 JIC Male':
                           return 'HTR-JIM-0407';
                        case '1/4 Reusable Hose Tail x 9/16 JIC Male':
                           return 'HTR-JIM-0409';    
                        case '3/8 Reusable Hose Tail x 9/16 JIC Male':
                           return 'HTR-JIM-0609';   
                        case '3/8 Reusable Hose Tail x 3/4 JIC Male':
                           return 'HTR-JIM-0612';     
                        case '1/2 Reusable Hose Tail x 3/4 JIC Male':
                           return 'HTR-JIM-0812';   
                        case '1/2 Reusable Hose Tail x 7/8 JIC Male':
                           return 'HTR-JIM-0814';     
                        case '3/4 Reusable Hose Tail x 1-1/16 JIC Male':
                           return 'HTR-JIM-1217';
                        case '1 Reusable Hose Tail x 1-5/16 JIC Male':
                           return 'HTR-JIM-1621';     
                        //  start JBM-JIC
                        case '7/16':
                            return 'JBM-JIM-0707';
                        case '9/16': 
                            return 'JBM-JIM-0909'; 
                        case '3/4': 
                            return 'JBM-JIM-0606';  
                        case '7/8':  
                            return 'JBM-JIM-1414';
                        case '1.1/16': 
                             return 'JBM-JIM-1717';
                        case '1.5/16': 
                             return 'JBM-JIM-2121';
                        // case '1.5/8'  
                        //      return 'jbm-jim-0707';
                        case '9/16':
                            return 'jBM-JIM-0909';
                        // JIC Female Field Fit Reusable Hydraulic Hose Fittings (Straight)
                        case '1/4 Reusable Hose Tail x 1/4 JIC Female':
                            return 'HTR-JIF-0404';
                        case '1/4 Reusable Hose Tail x 7/16 JIC Female':
                            return 'HTR-JIF-0407';
                        case '1/4 Reusable Hose Tail x 9/16 JIC Female':
                            return 'HTR-JIF-0409';
                        case '3/8 Reusable Hose Tail x 9/16 JIC Female':
                            return 'HTR-JIF-0609';
                        case '3/8 Reusable Hose Tail x 3/4 JIC Female':
                            return 'HTR-JIF-0612';
                        case '3/8 Reusable Hose Tail x 7/8 JIC Female':
                            return 'HTR-JIF-0614';
                        case '1/2 Reusable Hose Tail x 3/4 JIC Female':
                            return 'HTR-JIF-0812';
                        case '1/2 Reusable Hose Tail x 7/8 JIC Female':
                            return 'HTR-JIF-0814';
                        case '3/4 Reusable Hose Tail x 1-1/16 JIC Female':
                            return 'HTR-JIF-1217';
                        case '3/4 Reusable Hose Tail x 1-5/16 JIC Female':
                            return 'HTR-JIF-1221';
                        case '1 Reusable Hose Tail x 1-5/16 JIC Female':
                            return 'HTR-JIF-1621';
                        case '1/4 Tail x 9/16" JIC F 90 Swept Bend':
                            return 'HTR-JIF-90S-0409';
                        //Hydraulic Adapter Female Jic X Bspt
                        case 'BPM-JIF-0409 1/4 BSPPM x 9/16 JICFS':
                            return 'BPM-JIF-0409';
                        case 'BPM-JIF-0609 3/8 BSPPM x 9/16 JICFS':
                            return 'BPM-JIF-0609';
                        case 'BPM-JIF-0612 3/8 BSPPM x 3/4 JICFS':
                            return 'BPM-JIF-0612';
                        case 'BPM-JIF-0812 1/2 BSPPM x 3/4 JICFS':
                            return 'BPM-JIF-0812';
                        case 'BPM-JIF-0814 1/2 BSPPM x 7/8 JICFS':
                            return 'BPM-JIF-0814';
                        case 'BPM-JIF-1217 3/4 BSPPM x 1.1/16 JICFS':
                            return 'BPM-JIF-1217';
                        case 'BPM-JIF-0407 1/4 BSPPM x 7/16 JICFS':
                            return 'BPM-JIF-0407';
                        //JIC Female Field Fit Reusable Hydraulic Hose Fitting 90° Swept or Compact Bend
                        case '1/4 Tail x 7/16 JIC F 90 Swept Bend':
                            return 'HTR-JIF-90S-0407';
                        case '1/4 Tail x 7/16 JIC F 90 Compact Elbow':
                            return 'HTR-JIF-90C-0407';     
                        case '1/4 Tail x 9/16 JIC F 90 Swept Bend':
                            return 'HTR-JIF-90S-0409';        
                        case '1/4 Tail x 9/16 JIC F 90 Compact Elbow':
                            return 'HTR-JIF-90C-0409';    
                        case '3/8 Tail x 9/16 JIC F 90 Swept Bend':
                            return 'HTR-JIF-90S-0609';        
                        case '3/8 Tail x 9/16 JIC F 90 Compact Elbow':
                            return 'HTR-JIF-90-0609';     
                        case '3/8 Tail x 3/4 JIC F 90 Swept Bend':
                            return 'HTR-JIF-90S-0612';        
                        case '3/8 Tail x 3/4 JIC F 90 Compact Elbow':
                            return 'HTR-JIF-90C-0612';     
                        case '1/2 Tail x 3/4 JIC F 90 Swept Bend':
                            return 'HTR-JIF-90S-0812';        
                        case '1/2 Tail x 3/4 JIC F 90 Compact Elbow':
                            return 'HTR-JIF-90C-0812';      
                        case '1/2 Tail x 7/8 JIC F 90 Swept Bend':
                            return 'HTR-JIF-90S-0814';        
                        case '1/2 Tail x 7/8 JIC F 90 Compact Elbow':
                            return 'HTR-JIF-90C-0814';      
                        case '3/4 Tail x 1-1/16 JIC F 90 Swept Bend':
                            return 'HTR-JIF-90S-1217';      
                        case '3/4 Tail x 1-1/16 JIC F 90 Compact Elbow':
                            return 'HTR-JIF-90C-1217';  
                        case '3/4 Tail x 1-5/16 JIC F 90 Swept Bend':
                            return 'HTR-JIF-90S-1221';      
                        case '1 Tail x 1-5/16 JIC F 90 Swept Bend':
                            return 'HTR-JIF-90S-1621'; 
                        // BSPP Caps & Plugs Various Sizes
                        case '1/8 BSPP Plug':
                            return 'BPM-08'; 
                        case '1/4 BSPP Plug':
                            return 'BPM-04'; 
                        case '3/8 BSPP Plug':
                            return 'BPM-06'; 
                        case '1/2 BSPP Plug':
                            return 'BPM-08'; 
                        case '5/8 BSPP Plug':
                            return 'BPM-10'; 
                        case '3/4 BSPP Plug':
                            return 'BPM-12'; 
                        case '1 BSPP Plug':
                            return 'BPM-16'; 
                        case '1/8 BSPP Cap':
                            return 'BPF-02'; 
                        case '1/4 BSPP Cap':
                            return 'BPF-04'; 
                        case '3/8 BSPP Cap':
                            return 'BPF-06'; 
                        case '1/2 BSPP Cap':
                            return 'BPF-08'; 
                        case '5/8 BSPP Cap':
                            return 'BPF-10'; 
                        case '3/4 BSPP Cap':
                            return 'BPF-12'; 
                        case '1 BSPP Cap':
                            return 'BPF-16'; 
                        // Start HTR-BSP  
                        case '1/4 Reusable Hose Tail x 1/8 BSP Female':
                            return 'HTR-BSF-0402';
                        case '1/4 Reusable Hose Tail x 1/4 BSP Female':
                            return 'HTR-BSF-0404';
                        case '1/4 Reusable Hose Tail x 3/8 BSP Female':
                            return 'HTR-BSF-0406';
                        case '3/8 Reusable Hose Tail x 1/4 BSP Female':
                            return 'HTR-BSF-0604';
                        case '3/8 Reusable Hose Tail x 3/8 BSP Female':
                            return 'HTR-BSF-0606';
                        case '3/8 Reusable Hose Tail x 1/2 BSP Female':
                            return 'HTR-BSF-0608';
                        case '1/2 Reusable Hose Tail x 3/8 BSP Female':
                            return 'HTR-BSF-0806';
                        case '1/2 Reusable Hose Tail x 1/2 BSP Female':
                            return 'HTR-BSF-0808';
                        case '3/4 Reusable Hose Tail x 3/4 BSP Female':
                            return 'HTR-BSF-1212';
                        // BSPT Male Field Fit Reusable Hydraulic Hose Fittings (Straight)
                        case '1/4 Reusable Hose Tail x 1/8 BSP Male':
                            return 'HTR-BTM-0402'; 
                        case '1/4 Reusable Hose Tail x 1/4 BSP Male':
                            return 'HTR-BTM-0404'; 
                        case '1/4 Reusable Hose Tail x 3/8 BSP Male':
                            return 'HTR-BTM-0406'; 
                        case '3/8 Reusable Hose Tail x 1/4 BSP Male':
                            return 'HTR-BTM-0604'; 
                        case '3/8 Reusable Hose Tail x 3/8 BSP Male':
                            return 'HTR-BTM-0606'; 
                        case '3/8 Reusable Hose Tail x 1/2 BSP Male':
                            return 'HTR-BTM-0608'; 
                        case '1/2 Reusable Hose Tail x 3/8 BSP Male':
                            return 'HTR-BTM-0806'; 
                        case '1/2 Reusable Hose Tail x 1/2 BSP Male':
                            return 'HTR-BTM-0808'; 
                        case '3/4 Reusable Hose Tail x 3/4 BSP Male':
                            return 'HTR-BTM-1212'; 
                        case '1 Reusable Hose Tail x 1 BSP Male':
                            return 'HTR-BTM-1616';
                        // Nipples Bspt X Bspt
                        case '1/8 bspt x 1/8 BSPT':
                            return 'BTM-BTM-0808';
                        case '1/4 bspt x 1/4 BSPT':
                            return 'BTM-BTM-0404'; 
                        case '3/8 bspt x 3/8 BSPT':
                            return 'BTM-BTM-0606'; 
                        case '1/2 bspt x 1/2BSPT':
                            return 'BTM-BTM-0808'; 
                        case '3/4 bspt x 3/4 BSPT':
                            return 'BTM-BTM-1212'; 
                        case '1/8 bspt x 1/4 bspt':
                            return 'BTM-BTM-0204'; 
                        case '3/8bspt x 1/4 bspt':
                            return 'BTM-BTM-0604';  
                        case '1/2 bspt x 3/8 bspt':
                            return 'BTM-BTM-0806'; 
                        case '1/2 bspt x 1/4 bspt':
                            return 'BTM-BTM-0804'; 
                        case '3/4 bspt x 1/2 bspt':
                            return 'BTM-BTM-1208'; 
                        case '3/4 bspt x 3/8 bspt':
                            return 'BTM-BTM-1206'; 
                        case '1 Bspt x 1/2 bspt':
                            return 'BTM-BTM-1608'; 
                        case '1 Bspt x 3/4 bspt':
                            return 'BTM-BTM-1612'; 
                        case '1 Bspt x 1 Bspt':
                            return 'BTM-BTM-1616'; 

                        // BSP Tapered x BSP Tapered Male x Male 90° Adaptors
                        case '1/4 BSPT x 1/4 BSPT 90 Elbow 0404':
                            return 'BTM-BTM-90C-0404';
                        case '3/8 BSPT x 3/8 BSPT 90 Elbow 0606':
                            return 'BTM-BTM-90C-0606'; 
                        case '3/8 BSPT x 1/4 BSPT 90 Elbow 0604':
                            return 'BTM-BTM-90C-0604'; 
                        case '1/2 BSPT x 1/2 BSPT 90 Elbow 0808':
                            return 'BTM-BTM-90C-0808'; 
                        case '1/2 BSPT x 3/8 BSPT 90 Elbow 0806':
                            return 'BTM-BTM-90C-0806'; 
                        case '1/2 BSPT x 3/4 BSPT 90 Elbow 0812':
                            return 'BTM-BTM-90C-0812'; 
                        case '3/4 BSPT x 3/4 BSPT 90 Elbow 1212':
                            return 'BTM-BTM-90C-1212'; 
                        case '1 BSPT x 1 BSPT 90 Elbow 1616    ':
                            return 'BTM-BTM-90C-1616'; 
                        case '1 BSPT x 3/4 BSPT 90 Elbow 1612  ':
                            return 'BTM-BTM-90C-1612'; 
                        case '1-1/4 BSPT x 1-1/4 BSPT 90 Elbow 2020':
                            return 'BTM-BTM-90C-2020';
                        case '1-1/2 BSPT x 1-1/2 BSPT 90 Elbow 2424':
                            return 'BTM-BTM-90C-2424';

                        // BSP Female Field Fit Reusable Hydraulic Hose Fittings 90° Compact
                        case '1/4 Reusable Hose Tail x 1/4 BSP 90 C':
                            return 'HTR-BSF-90C-0404';
                        case '3/8 Reusable Hose Tail x 3/8 BSP 90 C':
                            return 'HTR-BSF-90C-0606';   
                        case '3/8 Reusable Hose Tail x 1/2 BSP 90 C':
                            return 'HTR-BSF-90C-0608';
                        case '1/2 Reusable Hose Tail x 1/2 BSP 90 C':
                            return 'HTR-BSF-90C-0808';
                        case '3/4 Reusable Hose Tail x 3/4 BSP 90 C':
                            return 'HTR-BSF-90C-1212'; 

                        // BSP Female Field Fit Reusable Hose Fittings 90° Swept
                        case '1/4 Reusable Hose Tail x 1/4 BSP 90 S':
                            return 'HTR-BSF-90S-0404';
                        case '3/8 Reusable Hose Tail x 3/8 BSP 90 S':
                            return 'HTR-BSF-90S-0606'; 
                        case '3/8 Reusable Hose Tail x 1/2 BSP 90 S':
                            return 'HTR-BSF-90S-0608'; 
                        case '1/2 Reusable Hose Tail x 1/2 BSP 90 S':
                            return 'HTR-BSF-90S-0808'; 
                        case '3/4 Reusable Hose Tail x 3/4 BSP 90 S':
                            return 'HTR-BSF-90S-1212'; 
                        case '1 Reusable Hose Tail x 1 BSP 90 S':
                            return 'HTR-BSF-90S-1616';

                        // BSP Parallel Male x Male Nipples
                        case 'BPM-BPM-0202 1/8 BSPPM x 1/8 BSPPM':
                            return 'BPM-BPM-0202';    
                        case 'BPM-BPM-0204 1/8 BSPPM x 1/4 BSPPM':
                            return 'BPM-BPM-0204';
                        case 'BPM-BPM-0404 1/4 BSPPM x 1/4 BSPPM':
                            return 'BPM-BPM-0404';
                        case 'BPM-BPM-0406 1/4 BSPPM x 3/8 BSPPM':
                            return 'BPM-BPM-0406';
                        case 'BPM-BPM-0408 1/4 BSPPM x 1/2 BSPPM':
                            return 'BPM-BPM-0408';
                        case 'BPM-BPM-0606 3/8 BSPPM x 3/8 BSPPM':
                            return 'BPM-BPM-0606';
                        case 'BPM-BPM-0608 3/8 BSPPM x 1/2 BSPPM':
                            return 'BPM-BPM-0608';
                        case 'BPM-BPM-0612 3/8 BSPPM x 3/4 BSPPM':
                            return 'BPM-BPM-0612';
                        case 'BPM-BPM-0808 1/2 BSPPM x 1/2 BSPPM':
                            return 'BPM-BPM-0808';
                        case 'BPM-BPM-0810 1/2 BSPPM x 5/8 BSPPM':
                            return 'BPM-BPM-0810';
                        case 'BPM-BPM-0812 1/2 BSPPM x 3/4 BSPPM':
                            return 'BPM-BPM-0812';
                        case 'BPM-BPM-0816 1/2 BSPPM x 1 BSPPM':
                            return 'BPM-BPM-0816';
                        case 'BPM-BPM-1212 3/4 BSPPM x 3/4 BSPPM':
                            return 'BPM-BPM-1212';
                        case 'BPM-BPM-1216 3/4 BSPPM x 1 BSPPM  ':
                            return 'BPM-BPM-1216';
                        // BSPP Parallel x BSPT Tapered Male x Male Nipples
                        case '1/8 BSPPM x 1/8 BSPT Nipple 0202':
                            return 'BPM-BTM-0202'; 
                        case '1/8 BSPPM x 1/4 BSPT Nipple 0204':
                            return 'BPM-BTM-0204'; 
                        case '1/4 BSPPM x 1/4 BSPT Nipple 0404':
                            return 'BPM-BTM-0404'; 
                        case '1/4 BSPPM x 1/8 BSPT Nipple 0402':
                            return 'BPM-BTM-0402'; 
                        case '1/4 BSPPM x 3/8 BSPT Nipple 0406':
                            return 'BPM-BTM-0406'; 
                        case '1/4 BSPPM x 1/2 BSPT Nipple 0408':
                            return 'BPM-BTM-0408'; 
                        case '3/8 BSPPM x 3/8 BSPT Nipple 0606':
                            return 'BPM-BTM-0606'; 
                        case '3/8 BSPPM x 1/4 BSPT Nipple 0604':
                            return 'BPM-BTM-0604'; 
                        case '3/8 BSPPM x 1/2 BSPT Nipple 0608':
                            return 'BPM-BTM-0608'; 
                        case '3/8 BSPPM x 3/4 BSPT Nipple 0612':
                            return 'BPM-BTM-0612'; 
                        case '1/2 BSPPM x 1/2 BSPT Nipple 0808':
                            return 'BPM-BTM-0808'; 
                        case '1/2 BSPPM x 1/4 BSPT Nipple 0804':
                            return 'BPM-BTM-0804'; 
                        case '1/2 BSPPM x 3/8 BSPT Nipple 0806':
                            return 'BPM-BTM-0806'; 
                        case '1/2 BSPPM x 3/4 BSPT Nipple 0812':
                            return 'BPM-BTM-0812'; 
                        case '1/2 BSPPM x 1 BSPT Nipple 0816':
                            return 'BPM-BTM-0816'; 
                        case '3/4 BSPPM x 3/4 BSPT Nipple 1212':
                            return 'BPM-BTM-1212'; 
                        case '3/4 BSPPM x 3/8 BSPT Nipple 1206':
                            return 'BPM-BTM-1206'; 
                        case '3/4 BSPPM x 1/2 BSPT Nipple 1208':
                            return 'BPM-BTM-1208'; 
                        case '3/4 BSPPM x 1 BSPT Nipple 1216':
                            return 'BPM-BTM-1216'; 
                        case '1 BSPPM x 1 BSPT Nipple 1616':
                            return 'BPM-BTM-1616'; 
                        case '1 BSPPM x 1/2 BSPT Nipple 1608':
                            return 'BPM-BTM-1608'; 
                        case '1 BSPPM x 3/4 BSPT Nipple 1612':
                            return 'BPM-BTM-1612'; 
                        case '1 BSPPM x 1-1/4 BSPT Nipple 1620':
                            return 'BPM-BTM-1620'; 
                        case '1-1/4 BSPPM x 1-1/4 BSPT Nipple 2020':
                            return 'BPM-BTM-2020';
                        case '3/4 BSPPM x 1-1/4 BSPT Nipple 1220':
                            return 'BPM-BTM-1220';
                        //BSPT Male Crimp on Hydraulic Hose Fitting
                        case 'HOSE 3/16 X BSPT M 1/4':
                            return'HTO-BTM-0304';
                        case 'HOSE 1/4 X BSPT M 1/8':
                            return'HTO-BTM-0402';
                        case 'HOSE 1/4 X BSPT M 1/4':
                            return'HTO-BTM-0404';
                        case 'HOSE 1/4 X BSPT M 3/8':
                            return'HTO-BTM-0406';
                        case 'HOSE 1/4 X BSPT M 1/2':
                            return'HTO-BTM-0408';
                        case 'HOSE 3/8 X BSPT M 1/4':
                            return'HTO-BTM-0604';
                        case 'HOSE 3/8 X BSPT M 3/8':
                            return'HTO-BTM-0606';
                        case 'HOSE 3/8 X BSPT M 1/2':
                            return'HTO-BTM-0608';
                        case 'HOSE 1/2 X BSPT M 3/8':
                            return'HTO-BTM-0806';
                        case 'HOSE 1/2 X BSPT M 1/2':
                            return'HTO-BTM-0808';
                        case 'HOSE 1/2 X BSPT M 3/4':
                            return'HTO-BTM-0812';
                        case 'HOSE 5/8 X BSPT M 1/2':
                            return'HTO-BTM-1008';
                        case 'HOSE 5/8 X BSPT M 3/4':
                            return'HTO-BTM-1012';
                        case 'HOSE 3/4 X BSPT M 3/4':
                            return'HTO-BTM-1212';
                        case 'HOSE 3/4 X BSPT M 1':
                            return'HTO-BTM-1216';
                        case 'HOSE 1 X BSPT M 1':
                            return'HTO-BTM-1616';
                        case 'HOSE 1 1/2 X BSPT M 1 1/2':
                            return'HTO-BTM-1717';
                        // Straight BSPP Female Crimp on Hydraulic Hose Fitting
                        case 'HOSE 1/4 X BSP F 1/8':
                            return 'HTO-BSF-0402';
                        case 'HOSE 1/4 X BSP F 1/4':
                            return 'HTO-BSF-0404';  
                        case 'HOSE 1/4 X BSP F 3/8':
                            return 'HTO-BSF-0406';  
                        case 'HOSE 3/8 X BSP F 1/4':
                            return 'HTO-BSF-0604';  
                        case 'HOSE 3/8 X BSP F 3/8':
                            return 'HTO-BSF-0606';  
                        case 'HOSE 3/8 X BSP F 1/2':
                            return 'HTO-BSF-0608';  
                        case 'HOSE 1/2 X BSP F 3/8':
                            return 'HTO-BSF-0806';  
                        case 'HOSE 1/2 X BSPF 1/2':
                            return 'HTO-BSF-0808';  
                        case 'HOSE 1/2 X BSP F 5/8':
                            return 'HTO-BSF-0810';  
                        case 'HOSE 1/2 X BSP F 3/4':
                            return 'HTO-BSF-0812';  
                        case 'HOSE 5/8 X BSP F 1/2':
                            return 'HTO-BSF-1008';  
                        case 'HOSE 5/8 X BSP F 3/4':
                            return 'HTO-BSF-1012';  
                        case 'HOSE 3/4 X BSP F 3/4':
                            return 'HTO-BSF-1212';  
                        case 'HOSE 3/4 X BSP F 1':
                            return 'HTO-BSF-1216';  
                        case 'HOSE 1 X BSP F 1':
                            return 'HTO-BSF-1616';
                        case 'HOSE 1 1/4 X BSP F 1 1/4':
                            return 'HTO-BSF-1717';
                        case 'HOSE 1 1/2 X BSP F 1 1/2':
                            return 'HTO-BSF-0909';
                        case 'HOSE 5/16 X BSP F 3/8':
                            return 'HTO-BSF-0506';
                        // Female Crimp-On BSP 90° Hydraulic Hose Fittings
                        case 'HOSE 1/4 X BSP F 1/4 90':
                            return 'HTO-BSF-90S-0404';
                        case 'HOSE 1/4 X BSP F 3/8 90':
                            return 'HTO-BSF-90S-0406'; 
                        case 'HOSE 3/8 X BSP F 3/8 90':
                            return 'HTO-BSF-90S-0606'; 
                        case 'HOSE 3/8 X BSP F 1/2 90':
                            return 'HTO-BSF-90S-0608'; 
                        case 'HOSE 1/2 X BSP F 1/2 90':
                            return 'HTO-BSF-90S-0808'; 
                        case 'HOSE 5/8 X BSP F 5/8 90':
                            return 'HTO-BSF-90S-1010'; 
                        case 'HOSE 5/8 X BSP F 3/4 90':
                            return 'HTO-BSF-90S-1012'; 
                        case 'HOSE 3/4 X BSP F 3/4 90':
                            return 'HTO-BSF-90S-1212'; 
                        case 'HOSE 1 X BSP F 1 90':
                            return 'HTO-BSF-90S-1616';

                        // Crimp-On Jic Male Hydraulic Hose Fitting
                        case 'HOSE 1/4 X JICM 7/16':
                            return 'HTO-JIM-0407'; 
                        case 'HOSE 1/4 X JICM 9/16':
                            return 'HTO-JIM-0409'; 
                        case 'HOSE 3/8 X JICM 9/16':
                            return 'HTO-JIM-0609'; 
                        case 'HOSE 3/8 X JICM 3/4':
                            return 'HTO-JIM-0612'; 
                        case 'HOSE 1/2 X JICM 3/4':
                            return 'HTO-JIM-0812'; 
                        case 'HOSE 1/2 X JICM 7/8':
                            return 'HTO-JIM-0814'; 
                        case 'HOSE 5/8 X JICM 7/8':
                            return 'HTO-JIM-1014'; 
                        case 'HOSE 3/4 X JICM 1-1/16':
                            return 'HTO-JIM-1217';

                        // JIC Female Hydraulic Hose Fitting for One and Two-wire hose

                        case 'HOSE 1/4 X JIC Female 7/16':
                            return 'HTO-JIF-0407';
                        case 'HOSE 1/4 X JIC Female 1/2':
                            return 'HTO-JIF-0408';
                        case 'HOSE 1/4 X JIC Female 9/16':
                            return 'HTO-JIF-0409';
                        case 'HOSE 3/8 X JIC Female 9/16':
                            return 'HTO-JIF-0609';
                        case 'HOSE 3/8 X JIC Female 3/4':
                            return 'HTO-JIF-0612';
                        case 'HOSE 3/8 X JIC Female 7/8':
                            return 'HTO-JIF-0614';
                        case 'HOSE 1/2 X JIC Female 3/4':
                            return 'HTO-JIF-0812';
                        case 'HOSE 1/2 X JIC Female 7/8':
                            return 'HTO-JIF-0814';
                        case 'HOSE 5/8 X JIC Female 7/8':
                            return 'HTO-JIF-1014';
                        case 'HOSE 5/8 X JIC Female 1-1/16':
                            return 'HTO-JIF-1017';
                        case 'HOSE 3/4 X JIC Female 1-1/16':
                            return 'HTO-JIF-1217';
                        case 'HOSE 3/4 X JIC Female 1-5/16':
                            return 'HTO-JIF-1221';
                        case 'HOSE 1 X JIC Female 1-5/16':
                            return 'HTO-JIF-1621';

                        // JIC CRIMP ONS 90º
                        case 'HOSE 1/4 X JIC F 7/16 90':
                            return 'HTO-JIF-90-0407'; 
                        case 'HOSE 1/4 X JIC F1/2 90 ':
                            return 'HTO-JIF-90-0408';
                        case 'HOSE 1/4 X JIC F 9/16 90':
                            return 'HTO-JIF-90-0409'; 
                        case 'HOSE 3/8 X JIC F 9/16 90':
                            return 'HTO-JIF-90-0609'; 
                        case 'HOSE 3/8 X JIC F 3/4 90':
                            return 'HTO-JIF-90-0612'; 
                        case 'HOSE 3/8 X JIC F 7/8 90':
                            return 'HTO-JIF-90-0614'; 
                        case 'HOSE 1/2 X JIC F 3/4 90':
                            return 'HTO-JIF-90-0812'; 
                        case 'HOSE 1/2 X JIC F 7/8 90':
                            return 'HTO-JIF-90-0814'; 
                        case 'HOSE 1/2 X JIC F 1.1/16 90':
                            return 'HTO-JIF-90-0817'; 
                        case 'HOSE 5/8 X JIC F 7/8 90':
                            return 'HTO-JIF-90-1014'; 
                        case 'HOSE 5/8 X JIC F 1.1/16 90':
                            return 'HTO-JIF-90-1017'; 
                        case 'HOSE 3/4 X JIC F 7/8 90':
                            return 'HTO-JIF-90-1214'; 
                        case 'HOSE 3/4 X JIC F 1.1/16 90':
                            return 'HTO-JIF-90-1217'; 
                        case 'HOSE 1 X JIC F 1.5/16 90':
                            return 'HTO-JIF-90-1621'; 
                        case 'HOSE 1.1/4 X JIC F 1.5/8 90':
                            return 'HTO-JIF-90-2026';
                        case 'HOSE 1.1/2 X JIC F 1.7/8 90':
                            return 'HTO-JIF-90-2430';
                        case 'HOSE 2 X JIC F 2.1/2 90':
                            return 'HTO-JIF-90-3240';



                         //BSPPM x JIC Straight Adapters Male x Male
                        case '1/8 BSPPM x 7/16 JICM NIPPLE 903FSP0402':
                            return 'BPM-JIM-0207'; 
                        case '1/8 BSPPM x 9/16 JICM NIPPLE 903FSP0404':
                            return 'BPM-JIM-0209';                     
                        case '1/4 BSPPM x 7/16 JICM NIPPLE 903FSP0602':
                            return 'BPM-JIM-0407'; 
                        case '1/4 BSPPM x 1/2 JICM NIPPLE':
                            return 'BPM-JIM-0408'; 
                        case '1/4 BSPPM x 9/16 JICM NIPPLE 903FSP0604':
                            return 'BPM-JIM-0409'; 
                        case '1/4 BSPPM x 3/4 JICM NIPPLE 903FSP0804':
                            return 'BPM-JIM-0412'; 
                        case '3/8 BSPPM x 7/16 JICM NIPPLE 903FSP0406':
                            return 'BPM-JIM-0607'; 
                        case '3/8 BSPPM x 9/16 JICM NIPPLE 903FSP0606':
                            return 'BPM-JIM-0609'; 
                        case '3/8 BSPPM x 3/4 JICM NIPPLE 903FSP0806':
                            return 'BPM-JIM-0612'; 
                        case '3/8 BSPPM x 7/8 JICM NIPPLE 903FSP1006':
                            return 'BPM-JIM-0614'; 
                        case '3/8 BSPPM x 1-1/16 JICM NIPPLE 903FSP1206':
                            return 'BPM-JIM-0617'; 
                        case '1/2 BSPPM x 7/16 JICM NIPPLE 903FSP0408':
                            return 'BPM-JIM-0807'; 
                        case '1/2 BSPPM x 9/16 JICM NIPPLE 903FSP0608':
                            return 'BPM-JIM-0809'; 
                        case '1/2 BSPPM x 3/4 JICM NIPPLE 903FSP0808':
                            return 'BPM-JIM-0812'; 
                        case '1/2 BSPPM x 7/8 JICM NIPPLE 903FSP1008':
                            return 'BPM-JIM-0814'; 
                        case '1/2 BSPPM x 1-1/16 JICM NIPPLE 903FSP1208':
                            return 'BPM-JIM-0817'; 
                        case '1/2 BSPPM x 1-5/16 JICM NIPPLE 903FSP1608':
                            return 'BPM-JIM-0821'; 
                        case '3/4 BSPPM x 3/4 JICM NIPPLE 903FSP0812':
                            return 'BPM-JIM-1212'; 
                        case '3/4 BSPPM x 9/16 JICM NIPPLE':
                            return 'BPM-JIM-1209';
                        case '3/4 BSPPM x 7/8 JICM NIPPLE':
                            return 'BPM-JIM-1214';
                        case '3/4 BSPPM x 1-1/16 JICM NIPPLE':
                            return 'BPM-JIM-1217';
                        case '3/4 BSPPM x 1-5/16 JICM NIPPLE':
                            return 'BPM-JIM-1221'; 
                        case '1 BSPPM x 3/4 JICM NIPPLE':
                            return 'BPM-JIM-1612'; 
                        case '1 BSPPM x 7/8 JICM NIPPLE':
                            return 'BPM-JIM-1614'; 
                        case '1 BSPPM x 1-1/16 JICM NIPPLE':
                            return 'BPM-JIM-1617'; 
                        case '1 BSPPM x 1-5/16 JICM NIPPLE':
                            return 'BPM-JIM-1621'; 
                        case '3/4 BSPPM x 3/4 JICM NIPPLE':
                            return 'BPM-JIM-1212';   
                        //  Strat JIM & UNM Adaptors
                        case '7/16 JIC M x 7/16 UNO M -0707':
                            return 'JIM-UNM-0707';
                        case '9/16 JIC M x 9/16 UNO M -0909':
                            return 'JIM-UNM-0909';
                        case '7/16 JIC M x 3/4 UNO M -0712':
                            return 'JIM-UNM-0712';
                        case '9/16 JIC M x 7/16 UNO M -0907':
                            return 'JIM-UNM-0907';
                        case '9/16 JIC M x 3/4 UNO M -0912':
                            return 'JIM-UNM-0921';    
                        case '9/16 JIC M x 7/8 UNO M -0914':    
                            return 'JIM-UNM-0921';   
                        case '9/16 JIC M x 1-1/16 UNO M -0917':
                            return 'JIM-UNM-0917';  
                        case '3/4 JIC M x 3/4 UNO M -1212':   
                            return 'JIM-UNM-1212';  
                        case '3/4 JIC M x 7/16 UNO M -1207':  
                            return 'JIM-UNM-1207';    
                        case '3/4 JIC M x 9/16 UNO M -1209':
                            return 'JIM-UNM-1209';       
                        case '3/4 JIC M x 7/8 UNO M -1214':
                            return 'JIM-UNM-1214';        
                        case '3/4 JIC M x 1-1/16 UNO M -1217':
                            return 'JIM-UNM-1217';    
                        case '3/4 JIC M x 1-5/16 UNO M -1221':
                            return 'JIM-UNM-1221';     
                        case '7/8 JIC M x 7/8 UNO M -1414':
                            return 'JIM-UNM-1414';     
                        case '7/8 JIC M x 9/16 UNO M -1409':
                            return 'JIM-UNM-1409';     
                        case '7/8 JIC M x 3/4 UNO M -1412':
                            return 'JIM-UNM-1412';     
                        case '7/8 JIC M x 1-1/16 UNO M -1417':
                            return 'JIM-UNM-1417';     
                        case '7/8 JIC M x 1-5/16 UNO M -1421':
                            return 'JIM-UNM-1421';     
                        case '1-1/16 JIC M x 1-1/16 UNO M -1717':
                            return 'JIM-UNM-1717';  
                        case '1-1/16 JIC M x 3/4 UNO M -1712':
                            return 'JIM-UNM-1712';  
                        case '1-1/16 JIC M x 7/8 UNO M -1714':
                            return 'JIM-UNM-1714';  
                        case '1-1/16 JIC M x 1-5/16 UNO M -1721':
                            return 'JIM-UNM-1721';  
                        case '1-1/16 JIC M x 1-5/8 UNO M -1726':
                            return 'JIM-UNM-1726';  
                        case '1-5/16 JIC M x 1-5/16 UNO M -2121':
                            return 'JIM-UNM-2121';   
                        case '1-5/16 JIC M x 3/4 UNO M -2112':
                            return 'JIM-UNM-2112';  
                        case '1-5/16 JIC M x 7/8 UNO M -2114':
                            return 'JIM-UNM-2114';   
                        case '1-5/16 JIC M x 1-1/16 UNO M -2117':
                            return 'JIM-UNM-2117';   
                        case '1-5/16 JIC M x 1-5/8 UNO M -2126':
                            return 'JIM-UNM-2126';  
                        case '1-5/8 JIC M x 1-5/8 UNO M -2626':
                            return 'JIM-UNM-2626';  
                        // for Hose
                        case '1/2 H-R2-08-10-EN':
                            return 'H-R2-08-EN';
                        case '20 metre coil':
                            return 'H-R2-04-EN';
                        case '1/4 H-R2-04-10-EN':
                            return 'H-R2-04-EN';
                        case '10 Metre coil H-R2-1210':
                            return 'H-R2-12-EN';
                        case '3/8 H-R2-06-10-EN':
                            return 'H-R2-06-EN';
                        //JIC x UNO 90° Male x Male Adapters
                        case '7/16 JIC M x 9/16 UNO M 90 ELBOW -0406':
                            return 'JIM-UNM-90C-0709';
                        case '7/16 JIC M x 3/4 UNO M 90 ELBOW -0408':
                           return 'JIM-UNM-90C-0712';
                        case '9/16 JIC M x 9/16 UNO M 90 ELBOW -0606':
                           return 'JIM-UNM-90C-0909';
                        case '9/16 JIC M x 7/16 UNO M 90 ELBOW -0604':
                           return 'JIM-UNM-90C-0907';
                        case '9/16 JIC M x 3/4 UNO M 90 ELBOW -0608':
                           return 'JIM-UNM-90C-0912';
                        case '9/16 JIC M x 7/8 UNO M 90 ELBOW -0610':
                           return 'JIM-UNM-90C-0914';
                        case '9/16 JIC M x 1-1/16 UNO M 90 ELBOW -0612':
                           return 'JIM-UNM-90C-0917';
                        case '3/4 JIC M x 3/4 UNO M 90 ELBOW -0808':
                            return 'JIM-UNM-90C-1212';
                        case '3/4 JIC M x 7/16 UNO M 90 ELBOW -0804':
                           return 'JIM-UNM-90C-1207';
                        case '3/4 JIC M x 9/16 UNO M 90 ELBOW -0806':
                           return 'JIM-UNM-90C-1209';
                        case '3/4 JIC M x 7/8 UNO M 90 ELBOW -0810':
                           return 'JIM-UNM-90C-1214';
                        case '3/4 JIC M x 1-1/16 UNO M 90 ELBOW -0812':
                           return 'JIM-UNM-90C-1217';
                        case '3/4 JIC M x 1-5/16 UNO M 90 ELBOW -0816':
                           return 'JIM-UNM-90C-1221';
                        case '7/8 JIC M x 7/8 UNO M 90 ELBOW -1010':
                           return 'JIM-UNM-90C-1414';
                        case '7/8 JIC M x 9/16 UNO M 90 ELBOW -1006':
                           return 'JIM-UNM-90C-1409';
                        case '7/8 JIC M x 3/4 UNO M 90 ELBOW -1008':
                           return 'JIM-UNM-90C-1412';
                        case '7/8 JIC M x 1-1/16 UNO M 90 ELBOW -1012':
                           return 'JIM-UNM-90C-1417';
                        case '7/8 JIC M x 1-5/16 UNO M 90 ELBOW -1016':
                           return 'JIM-UNM-90C-1421';
                        case '1-1/16 JIC M x 1-1/16 UNO M 90 ELBOW -1212':
                           return 'JIM-UNM-90C-1717';
                        case '1-1/16 JIC M x 3/4 UNO M 90 ELBOW -1208':
                           return 'JIM-UNM-90C-1712';
                        case '1-1/16 JIC M x 7/8 UNO M 90 ELBOW -1210':
                           return 'JIM-UNM-90C-1714';
                        case '1-1/16 JIC M x 1-5/16 UNO M 90 ELBOW -1216':
                           return 'JIM-UNM-90C-1721';
                        // case '1-1/16 JIC M x 1-5/8 UNO M 90 ELBOW -1220':
                        //     return 'JIM-UNM-90C-179';
                        case '1-5/16 JIC M x 1-5/16 UNO M 90 ELBOW -1616':
                            return 'JIM-UNM-90C-2121';
                        case '1-5/16 JIC M x 7/8 UNO M 90 ELBOW -1610':
                            return 'JIM-UNM-90C-2114';
                        case '1-5/16 JIC M x 1-1/16 UNO M 90 ELBOW -1612':
                             return 'JIM-UNM-90C-2117';
                         // case '1-5/16 JIC M x 1-5/8 UNO M 90 ELBOW -1620':
                         //     return 'JIM-UNM-90C-21';
                         // case '1-5/8 JIC M x 1-5/8 UNO M 90 ELBOW -2020':
                         //     return 'JIM-UNM-90C-009';

                        //ORFS Female Field Fit Reusable Hydraulic Hose Fitting
                        case '1/4 Re-Usable Tail x 9/16 ORFSF':
                            return ' HTR-ORF-0409';
                        case '3/8 Tail x 11/16 ORFSF':                  
                            return 'HTR-ORF-0611';
                        case '1/2Tail x 13/16 ORFSF': 
                            return 'HTR-ORF-0813';

                        // ORF Rings
                        case 'ORFS ORING 9/16':
                            return 'OR-ORF-09';  
                        case 'ORFS ORING 11/16':
                            return 'OR-ORF-11';  
                        case 'ORFS ORING 13/16':
                            return 'OR-ORF-13';  
                        case 'ORFS ORING 1':
                            return 'OR-ORF-16';  
                        case 'ORFS ORING 1 3/16':
                            return 'OR-ORF-19';  

                        // Hydraulic Hose Fittings ORFS Female CRIMP ON 1/4" to 1"
                        case 'HTB-ORF-0404 - 1/4 Hose x ORFS Female 1/4':
                            return 'HTB-ORF-0404';
                        case 'HTB-ORF-0409 - 1/4 Hose x ORFS Female 3/8':
                            return 'HTB-ORF-0406';
                        case 'HTB-ORF-0409 - 1/4 Hose x ORFS Female 9/16':
                            return 'HTB-ORF-0409';
                        case 'HTB-ORF-0613 - 3/8 Hose x ORFS Female 13/16':
                            return 'HTB-ORF-0613';
                        case 'HTB-ORF-0813 - 1/2 Hose x ORFS Female 13/16':
                            return 'HTB-ORF-0813';
                        case 'HTB-ORF-1219 - 3/4 Hose x ORFS Female 1-3/16':
                            return 'HTB-ORF-1219';
                        case 'HTB-ORF-0613 - 3/8 Hose x ORFS Female 13/16':
                             return 'HTB-ORF-0613';

                        // ORFS HOSE FITTINGS FEMALE 90° SWEPT SWAGE ON

                        case 'E04FO0990':
                            return 'HTB-ORF-90S-0409';
                        case 'E04FO1190':
                            return 'HTB-ORF-90S-0611'; 
                        case 'E06FO1190':
                            return 'HTB-ORF-90S-0613'; 
                        case 'E06FO1390':
                            return 'HTB-ORF-90S-0613'; 
                        case 'E08FO1390':
                            return 'HTB-ORF-90S-0813'; 
                        case 'E08FO1690':
                            return 'HTB-ORF-90S-'; 
                        case 'E10FO1690':
                            return 'HTB-ORF-90S-'; 
                        case 'E12JFO1990':
                            return 'HTB-ORF-90S-';
                        case 'E16FO2390':
                            return 'HTB-ORF-90S-'; 

                        // For Clamps
                        case '6mm':
                            return 'TC-6-S';
                        case '8mm':
                          return 'TC-8-S';
                        case '9.5mm':
                          return 'TC-9.5-S';
                        case '10mm':
                          return 'TC-10-S';
                        case '12mm':
                          return 'TC-12-S';  
                        case '12.7mm':
                          return 'TC-12.7-S'; 
                        case '14mm ';
                          return 'TC-14-S';  
                        case '15mm':
                          return 'TC-15-S';   
                        case '16mm':
                          return 'TC-16-S';  
                        case '18mm':
                          return 'TC-18-S';   
                        case '19mm':
                          return 'TC-19-S';   
                        case '20mm':
                          return 'TC-20-S';   
                        case '22mm':
                          return 'TC-22-S';   
                        case '25mm':
                          return 'TC-22-S';   
                        case '25.4';
                          return 'TC-25.4-S';   
                        case '26.9mm':
                          return 'TC-26.9-S'; 
                        case '28mm':
                          return 'TC-28-S';   
                        case '30mm':
                          return 'TC-30-S';   
                        case '32mm':
                          return 'TC-32-S';   
                        case '35mm':
                          return 'TC-35-S';   
                        case '38mm':
                          return 'TC-38-S';   
                        case '40mm':
                          return 'TC-40-S';   
                        case '42mm':
                          return 'TC-42-S';   
                        case '44.5mm':
                          return 'TC-44.5-S'; 
                        case '48.3mm':
                          return 'TC-48.3-S'; 
                        case '50.8mm':
                          return 'TC-50.8-S';    
                    }
                }
                //  elseif ($option['name'] == 'SAE 100R2 04  length') {
                //     return 'H-R2-04-EN';
                // }  elseif ($option['name'] == 'SAE 100R2AT12 3/4 Bore LENGTH') {
                //     return 'H-R2-12-EN';
                // } 
            }
        }

        // Default return if no condition is met
        return 'DEFAULT-SKU';
    }
}


