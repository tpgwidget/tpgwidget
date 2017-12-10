<?php

/**
 * arrayMap function. Customized array_map function which preserves keys/associate array indexes. Note that this costs a descent amount more memory (eg. 1.5k per call)
 *
 * @access public
 * @param callback $callback Callback function to run for each element in each array.
 * @param mixed $arr1 An array to run through the callback function.
 * @param array $array Variable list of array arugments to run through the callback function.
 * @return array Array containing all the elements of $arr1 after applying the callback function to each one, recursively, maintain keys.
 */
function arrayMap($callback, $arr1)
{
    $results = array();
    $args = array();
    if (func_num_args() > 2) $args = (array)array_shift(array_slice(func_get_args() , 2));
    foreach($arr1 as $key => $value) {
        $temp = $args;
        array_unshift($temp, $value);
        if (is_array($value)) {
            array_unshift($temp, $callback);
            $results[$key] = call_user_func_array(array(
                'self',
                'arrayMap'
            ) , $temp);
        }
        else {
            $results[$key] = call_user_func_array($callback, $temp);
        }
    }

    return $results;
}

class Plans {

    /* Types de plans */
    protected static $types = [
        'urbain' => [
            'name' => 'Plan urbain',
            'desc' => 'Lignes de tram, de trolleybus et de bus urbain'
        ],
        'regional' => [
            'name' => 'Plan régional',
            'desc' => 'Lignes régionales et transfrontalières'
        ],
        'noctambus' => [
            'name' => 'Plan Noctambus régional',
            'desc' => 'Lignes Noctambus régionales (vendredi et samedi soir)'
        ],
        'noctambusurbain' => [
            'name' => 'Plan Noctambus urbain',
            'desc' => 'Lignes Noctambus urbaines (vendredi et samedi soir)'
        ],
        'zones' => [
            'name' => 'Plan des zones tarifaires',
            'desc' => 'Toutes les zones tarifaires unireso'
        ]
    ];

    /* Années et leurs plans */
    protected static $years = [
        '2018' => [
            'name' => 'Dès le 10 décembre 2017',
            'plans' => ['urbain', 'regional', 'noctambus', 'noctambusurbain'],
        ],
    ];

    public static function all()
    {
        // Pour chaque plan, prend le nom du plan et le remplace
        // par l'objet définissant le type de ce plan
        foreach(self::$years as $yearNumber => $year) {

            $plans = &self::$years[$yearNumber]["plans"];

            foreach($plans as $index => $plan){
                $plans[$plan] = self::$types[$plan];

                unset($plans[$index]);
            }
        }

        return self::$years;
    }
}
