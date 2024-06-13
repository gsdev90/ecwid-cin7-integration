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


                $value = preg_replace('/[^A-Za-z0-9\s\/-]/', '', $option['value']);
                $value = trim($value);
                $value = preg_replace('/\s+/', ' ', $value);

                // To see logs on server 
                // error_log("Option name: " . $name);
                // error_log("Option value: " . $value);

                Log::info("name: " . $name);
                Log::info("value : " . $value);

                //  delete it after testing 

                // this array check if there name exist in selected name
                $all_Selected_Name = [
                                       'SIZES JIC',
                                       'Bulkheads JIC 832FS',
                                       'Hosetail x Thread',
                                       'Hose tail x Thread',
                                       'Hose Tail x Thread',
                                       'JIC x UNO Male/Male',
                                       'BSPT x BSPT Male/Male 90',
                                       'SAE100R2AT',
                                       'SAE 100R2 04 length',
                                       'SAE 100R2AT12 3/4 Bore LENGTH',
                                       'Bulkheads JIC 832FS',
                                       'ORFS Straight Female',
                                       'ORFS SIZES FEMALE',
                                       'BPM-JIF-',
                                       'BSPP x BSPP Male/Male Nipples',
                                       'BSPP x BSPT Male/Male Nipples',
                                       '90 JIC x UNO Male/Male Elbow'
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
                            return 'jbm-jim-0707';
                        case '9/16': 
                            return 'jbm-jim-0909'; 
                        case '3/4': 
                            return 'jbm-jim-0606';  
                        case '7/8':  
                            return 'jbm-jim-1414';
                        case '1.1/16': 
                             return 'jbm-jim-1717';
                        case '1.5/16': 
                             return 'jbm-jim-2121';
                        // case '1.5/8'  
                        //      return 'jbm-jim-0707';
                        case '9/16':
                            return 'jbm-jim-09-09';
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
                        // BSPP Caps & Plugs Various Sizes
                        
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
                        case '3/8 BSPT x 3/8" BSPT 90 Elbow 0606':
                            return 'BTM-BTM-90-0606';
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
                         //  Strat JIM & UNM
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
                        case '1/2 H-R2-08-10-EN':
                            return 'H-R2-08-EN';
                        case '20 metre coil':
                            return 'H-R2-04-EN';
                        case '10 Metre coil HR21210':
                            return 'H-R2-12-EN';
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
                        // for ORF - HTB
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


