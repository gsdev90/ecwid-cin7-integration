<?php

namespace app\Helpers;

class SkuTransformer
{
    public static function transform($selectedOptions = [])
    {
        if ($selectedOptions) {
        // Check selected options and transform SKU accordingly
            foreach ($selectedOptions as $option) {
                $name = trim($option['name']);
                $value = preg_replace('/\s+/', ' ', trim($option['value']));

                // Replace \u00b0 with the actual degree symbol
                // $value = str_replace('\u00b0', '°', $value);
                // $value = str_replace('\u00b0', '', $value);

                // Debugging: Log the actual values
                error_log("Option name: " . $name);
                error_log("Option value: " . $value);
                if (($name == 'Hosetail x Thread') || ($name == 'Hose tail x Thread') || ($name == 'JIC x UNO Male/Male')) {
                    switch ($value) {
                        //  start JIC
                        case '1/4" Reusable Hose Tail x 7/16" JIC Female':
                            return 'HTR-JIF-0407';
                        case '1/4" Reusable Hose Tail x 9/16" JIC Female':
                            return 'HTR-JIF-0409';
                        case '3/8" Reusable Hose Tail x 9/16" JIC Female':
                            return 'HTR-JIF-0609';
                        case '3/8" Reusable Hose Tail x 3/4" JIC Female':
                            return 'HTR-JIF-0612';
                        case '1/2" Reusable Hose Tail x 3/4" JIC Female':
                            return 'HTR-JIF-0812';
                        case '1/2" Reusable Hose Tail x 7/8" JIC Female':
                            return 'HTR-JIF-0814';
                        case '3/4" Reusable Hose Tail x 1-1/16" JIC Female':
                            return 'HTR-JIF-1217';
                        case '3/4" Reusable Hose Tail x 1-5/16" JIC Female':
                            return 'HTR-JIF-1221';
                        case '1/4" Tail x 9/16" JIC F 90° Swept Bend':
                            return 'HTR-JIF-90S-0409';
                        case '1/4" Reusable Hose Tail x 1/8" BSP Female':
                            return 'HTR-BSF-0402';
                        // Start BSP  
                        case '1/4" Reusable Hose Tail x 1/4" BSP Female':
                            return 'HTR-BSF-0404';
                        case '1/4" Reusable Hose Tail x 3/8" BSP Female':
                            return 'HTR-BSF-0406';
                        case '3/8" Reusable Hose Tail x 1/4" BSP Female':
                            return 'HTR-BSF-0604';
                        case '3/8" Reusable Hose Tail x 3/8" BSP Female':
                            return 'HTR-BSF-0606';
                        case '3/8" Reusable Hose Tail x 1/2" BSP Female':
                            return 'HTR-BSF-0608';
                        case '1/2" Reusable Hose Tail x 3/8" BSP Female':
                            return 'HTR-BSF-0806';
                        case '1/2" Reusable Hose Tail x 1/2" BSP Female':
                            return 'HTR-BSF-0808';
                        case '3/4" Reusable Hose Tail x 3/4" BSP Female':
                            return 'HTR-BSF-1212';
                        //  Strat JIM & UNM
                        case '7/16" JIC M x 7/16" UNO M -0707':
                            return 'JIM-UNM-0707';
                        case '9/16" JIC M x 9/16" UNO M -0909':
                            return 'JIM-UNM-0909';
                        case '7/16" JIC M x 3/4" UNO M -0712':
                            return 'JIM-UNM-0712';
                        case '9/16" JIC M x 7/16" UNO M -0907':
                            return 'JIM-UNM-0907';
                        case '9/16" JIC M x 3/4" UNO M -0912':
                            return 'JIM-UNM-0921';    
                        case '9/16" JIC M x 7/8" UNO M -0914':    
                            return 'JIM-UNM-0921';   
                        case '9/16" JIC M x 1-1/16" UNO M -0917':
                            return 'JIM-UNM-0917';  
                        case '3/4" JIC M x 3/4" UNO M -1212':   
                            return 'JIM-UNM-1212';  
                        case '3/4" JIC M x 7/16" UNO M -1207':  
                            return 'JIM-UNM-1207';    
                        case '3/4" JIC M x 9/16" UNO M -1209':
                            return 'JIM-UNM-1209';       
                        case '3/4" JIC M x 7/8" UNO M -1214':
                            return 'JIM-UNM-1214';        
                        case '3/4" JIC M x 1-1/16" UNO M -1217':
                            return 'JIM-UNM-1217';    
                        case '3/4" JIC M x 1-5/16" UNO M -1221':
                            return 'JIM-UNM-1221';     
                        case '7/8" JIC M x 7/8" UNO M -1414':
                            return 'JIM-UNM-1414';     
                        case '7/8" JIC M x 9/16" UNO M -1409':
                            return 'JIM-UNM-1409';     
                        case '7/8" JIC M x 3/4" UNO M -1412':
                            return 'JIM-UNM-1412';     
                        case '7/8" JIC M x 1-1/16" UNO M -1417':
                            return 'JIM-UNM-1417';     
                        case '7/8" JIC M x 1-5/16" UNO M -1421':
                            return 'JIM-UNM-1421';     
                        case '1-1/16" JIC M x 1-1/16" UNO M -1717':
                            return 'JIM-UNM-1717';  
                        case '1-1/16" JIC M x 3/4" UNO M -1712':
                            return 'JIM-UNM-1712';  
                        case '1-1/16" JIC M x 7/8" UNO M -1714':
                            return 'JIM-UNM-1714';  
                        case '1-1/16" JIC M x 1-5/16" UNO M -1721':
                            return 'JIM-UNM-1721';  
                        case '1-1/16" JIC M x 1-5/8" UNO M -1726':
                            return 'JIM-UNM-1726';  
                        case '1-5/16" JIC M x 1-5/16" UNO M -2121':
                            return 'JIM-UNM-2121';   
                        case '1-5/16" JIC M x 3/4" UNO M -2112':
                            return 'JIM-UNM-2112';  
                        case '1-5/16" JIC M x 7/8" UNO M -2114':
                            return 'JIM-UNM-2114';   
                        case '1-5/16" JIC M x 1-1/16" UNO M -2117':
                            return 'JIM-UNM-2117';   
                        case '1-5/16" JIC M x 1-5/8" UNO M -2126':
                            return 'JIM-UNM-2126';  
                        case '1-5/8" JIC M x 1-5/8" UNO M -2626':
                            return 'JIM-UNM-2626';  
                    }
                } elseif ($option['name'] == "SAE 100R2 04  length") {
                    return 'H-R2-04-EN';
                }  elseif ($option['name'] == "SAE 100R2AT-12 (3/4\" Bore) LENGTH" ) {
                    return 'H-R2-12-EN';
                } 
            }
        }

        // Default return if no condition is met
        return 'DEFAULT-SKU';
    }
}

// further need to work
/* 
case '9/16" JIC M x 3/4" UNO M -0912     
case '9/16" JIC M x 7/8" UNO M -0914     
case '9/16" JIC M x 1-1/16" UNO M -0917 
case '3/4" JIC M x 3/4" UNO M -1212      
case '3/4" JIC M x 7/16" UNO M -1207    
case '3/4" JIC M x 9/16" UNO M -1209     
case '3/4" JIC M x 7/8" UNO M -1214      
case '3/4" JIC M x 1-1/16" UNO M -1217   
case '3/4" JIC M x 1-5/16" UNO M -1221   
case '7/8" JIC M x 7/8" UNO M -1414   
case '7/8" JIC M x 9/16" UNO M -1409   
case '7/8" JIC M x 3/4" UNO M -1412   
case '7/8" JIC M x 1-1/16" UNO M -1417   
case '7/8" JIC M x 1-5/16" UNO M -1421   
case '1-1/16" JIC M x 1-1/16" UNO M -1717
case '1-1/16" JIC M x 3/4" UNO M -1712 
case '1-1/16" JIC M x 7/8" UNO M -1714 
case '1-1/16" JIC M x 1-5/16" UNO M -1721 
case '1-1/16" JIC M x 1-5/8" UNO M -1726  
case '1-5/16" JIC M x 1-5/16" UNO M -2121 
case '1-5/16" JIC M x 3/4" UNO M -2112  
case '1-5/16" JIC M x 7/8" UNO M -2114  
case '1-5/16" JIC M x 1-1/16" UNO M -2117 
case '1-5/16" JIC M x 1-5/8" UNO M -2126  
case '1-5/8" JIC M x 1-5/8" UNO M -2626  
*/

