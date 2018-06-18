<?php


class Lms_Football
{

    static public $countryMap = [
        'Россия' => ['name_ru' => 'Россия', 'name_en' => 'Russia', 'img' => '/images/flags/russia.svg', 'code' => 'russia'],
        'Бразилия' => ['name_ru' => 'Бразилия', 'name_en' => 'Brazil', 'img' => '/images/flags/brazil.svg', 'code' => 'brazil'],
        'Швейцария' => ['name_ru' => 'Швейцария', 'name_en' => 'switzerland', 'img' => '/images/flags/switzerland.svg', 'code' => 'switzerland'],
        'Швеция' => ['name_ru' => 'Швеция', 'name_en' => 'Sweden', 'img' => '/images/flags/sweden.svg', 'code' => 'sweden'],
        'Южная Корея' => ['name_ru' => 'Южная корея', 'name_en' => 'South Korea', 'img' => '/images/flags/south-korea.svg', 'code' => 'southkorea'],
        'Бельгия' => ['name_ru' => 'Бельгия', 'name_en' => 'Belgium', 'img' => '/images/flags/belgium.svg', 'code' => 'belgium'],
        'Панама' => ['name_ru' => 'Панама', 'name_en' => 'Panama', 'img' => '/images/flags/panama.svg', 'code' => 'panama'],
        'Тунис' => ['name_ru' => 'Тунис', 'name_en' => 'Tunisia', 'img' => '/images/flags/tunisia.svg', 'code' => 'tunisia'],
        'Англия' => ['name_ru' => 'Англия', 'name_en' => 'United Kingdom', 'img' => '/images/flags/united-kingdom.svg', 'code' => 'unitedkingdom'],
        'Колумбия' => ['name_ru' => 'Колумбия', 'name_en' => 'Colombia', 'img' => '/images/flags/colombia.svg', 'code' => 'colombia'],
        'Япония' => ['name_ru' => 'Япония', 'name_en' => 'Japan', 'img' => '/images/flags/japan.svg', 'code' => 'japan'],
        'Польша' => ['name_ru' => 'Польша', 'name_en' => 'Poland', 'img' => '/images/flags/poland.svg', 'code' => 'poland'],
        'Синегал' => ['name_ru' => 'Синегал', 'name_en' => 'Senegal', 'img' => '/images/flags/senegal.svg', 'code' => 'senegal'],
        'Египет' => ['name_ru' => 'Египет', 'name_en' => 'Egypt', 'img' => '/images/flags/egypt.svg', 'code' => 'egypt'],
        'Португалия' => ['name_ru' => 'Португалия', 'name_en' => 'Portugal', 'img' => '/images/flags/portugal.svg', 'code' => 'portugal'],
        'Марокко' => ['name_ru' => 'Марокко', 'name_en' => 'Morocco', 'img' => '/images/flags/morocco.svg', 'code' => 'morocco'],
        'Уругвай' => ['name_ru' => 'Уругвай', 'name_en' => 'Uruguay', 'img' => '/images/flags/uruguay.svg', 'code' => 'uruguay'],
        'Саудовская Аравия' => ['name_ru' => 'Саудовская аравия', 'name_en' => 'Saudi Arabia', 'img' => '/images/flags/saudi-arabia.svg', 'code' => 'saudiarabia'],
        'Иран' => ['name_ru' => 'Иран', 'name_en' => 'Iran', 'img' => '/images/flags/iran.svg', 'code' => 'iran'],
        'Испания' => ['name_ru' => 'Испания', 'name_en' => 'Spain', 'img' => '/images/flags/spain.svg', 'code' => 'spain'],
        'Дания' => ['name_ru' => 'Дания', 'name_en' => 'Denmark', 'img' => '/images/flags/denmark.svg', 'code' => 'denmark'],
        'Австралия' => ['name_ru' => 'Австралия', 'name_en' => 'Australia', 'img' => '/images/flags/australia.svg', 'code' => 'australia'],
        'Франция' => ['name_ru' => 'Франция', 'name_en' => 'France', 'img' => '/images/flags/france.svg', 'code' => 'france'],
        'Перу' => ['name_ru' => 'Перу', 'name_en' => 'Peru', 'img' => '/images/flags/peru.svg', 'code' => 'peru'],
        'Аргентина' => ['name_ru' => 'Аргентина', 'name_en' => 'Argentina', 'img' => '/images/flags/argentina.svg', 'code' => 'argentina'],
        'Хорватия' => ['name_ru' => 'Хорватия', 'name_en' => 'Croatia', 'img' => '/images/flags/croatia.svg', 'code' => 'croatia'],
        'Коста-Рика' => ['name_ru' => 'Коста-рика', 'name_en' => 'Costa-Rica', 'img' => '/images/flags/costa-rica.svg', 'code' => 'costarica'],
        'Нигерия' => ['name_ru' => 'Нигерия', 'name_en' => 'Nigeria', 'img' => '/images/flags/nigeria.svg', 'code' => 'nigeria'],
        'Исландия' => ['name_ru' => 'Исландия', 'name_en' => 'Iceland', 'img' => '/images/flags/iceland.svg', 'code' => 'iceland'],
        'Сербия' => ['name_ru' => 'Сербия', 'name_en' => 'Serbia', 'img' => '/images/flags/serbia.svg', 'code' => 'serbia'],
        'Мексика' => ['name_ru' => 'Мексика', 'name_en' => 'Mexico', 'img' => '/images/flags/mexico.svg', 'code' => 'mexico'],
        'Германия' => ['name_ru' => 'Германия', 'name_en' => 'Germany', 'img' => '/images/flags/germany.svg', 'code' => 'germany'],
    ];
    static public $countryMapCode = [];


    static public function getTvshowsFuture()
    {
        $db = Lms_Db::get('main');

        $items = $db->select("SELECT * FROM tvshows
                    WHERE channel_id = '10254' AND start > ? AND title LIKE '%Чемпионат мира-2018%'
                    ORDER BY date", date('Y-m-d H:i:s')
        );

        foreach ($items as $k => $item)
            $items[$k] = self::titleParse($item);

        return $items;
    }

    static public function getTvshowsArchive()
    {
        $db = Lms_Db::get('main');

        $items = $db->select("SELECT * FROM tvshows
                    WHERE channel_id = '10254' AND start < ? AND title LIKE '%Чемпионат мира-2018%'
                    ORDER BY date desc", date('Y-m-d H:i:s')
        );

        foreach ($items as $k => $item)
            $items[$k] = self::titleParse($item);

        return $items;
    }


    static public function getTvshowsOnline()
    {
        $db = Lms_Db::get('main');

        $item = $db->selectRow(
            "SELECT * FROM tvshows
            WHERE channel_id = '10254' AND tvshow_id < (
                SELECT tvshow_id FROM tvshows WHERE channel_id = 10254 AND start > ? ORDER BY start ASC LIMIT 1
            )
            ORDER BY tvshow_id DESC 
            LIMIT 1",
            date('Y-m-d H:i:s')
        );
        $item = self::titleParse($item);


        return $item;
    }

    static public function getTvshowsToday()
    {
        $db = Lms_Db::get('main');

        $items = $db->select(
            "SELECT * FROM tvshows
            WHERE channel_id = 10254 AND date = ?  AND title LIKE '%Чемпионат мира-2018%'
            ORDER BY start ASC",
            date('Y-m-d')
        );
        foreach ($items as $k => $item)
            $items[$k] = self::titleParse($item);


        return $items;
    }

    static public function getTvshowInfo($tvshowId) {
        $db = Lms_Db::get('main');
        return $db->selectRow('select * from tvshows where tvshow_id = ?', $tvshowId);
    }


    static public function getTvshowStream($tvshowId)
    {


    }

    static public function getTvshowVod($tvshowId)
    {


    }

    /*
     * парсинг строки телепрограммы
     * добавление нужных значений (названия стран, иконки, генерация чпу-урл)
     */
    static function titleParse($item)
    {
        preg_match('/.+\.\s(.+-.+)\..*/', $item['title'], $m);
        if ($m[1]) {
            $item['cmd12'] = $m[1];
            list($cmd1, $cmd2) = explode('-', $m['1']);
            if ($country = self::countryGet($cmd1))
                $item['cmd1'] = $country;
            if ($country = self::countryGet($cmd2))
                $item['cmd2'] = $country;
            if (isset($item['cmd1']) and isset($item['cmd2']))
                $item['url'] = sprintf('/match/%s/%s/%s/', $item['cmd1']['code'], $item['cmd2']['code'], $item['tvshow_id']);
            $parts = explode('.', $item['title']);
            $item['city'] = trim(array_pop($parts));

            setlocale(LC_ALL, 'ru_RU', 'ru_RU.UTF-8', 'ru', 'russian');
            $ts = strtotime($item['start']);
            $item['date_ru'] =  strftime('%d %B', $ts);
            $item['datetime_ru'] =  strftime('%d %B %Y, в %H:%M', $ts);
        }
        return $item;
    }

    static function countryGet($nameRu)
    {
        if (isset(self::$countryMap[$nameRu]))
            return self::$countryMap[$nameRu];

        return false;
    }

    static function countryGetCode($code) {
        if (empty(self::$countryMapCode)) {
            foreach (self::$countryMap as $country)
                self::$countryMapCode[$country['code']] = $country;
        }

        if (isset(self::$countryMapCode[$code]))
            return self::$countryMapCode[$code];

        return false;
    }

}