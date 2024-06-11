<?php

namespace app\Helpers;

class SkuTransformer
{
    public static function transform($productName,$selectedOptions = [])
    {
        // Define your custom SKU transformation logic here
        if (strpos($productName, '1/4" ENGMATTEC Two Wire Hydraulic Hose') !== false) {
            return 'H-R2-04-EN';
        } elseif (strpos($productName, '3/4" inch ENGMATTEC Two Wire Hydraulic Hose') !== false) {
            return 'H-R2-12-EN';
        } elseif (strpos($productName, '3/8" 2 wire Hydraulic Hose') !== false) {
            return 'H-R2-12-EN';
        }

        if ($selectedOptions) {
        // Check selected options and transform SKU accordingly
            foreach ($selectedOptions as $option) {
                if ($option['name'] == 'Hosetail x Thread') {
                    switch ($option['value']) {
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
                        case '1/4" Reusable Hose Tail x 1/8" BSP Female':
                            return 'HTR-BSF-0402';
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
                    }
                }
            }
        }

        // Default return if no condition is met
        return 'DEFAULT-SKU';
    }
}

