<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 29.03.18
 * Time: 13:20
 */

namespace core\callTracking;


use core\fileCache\fileCache;
use core\registry\registry;


/**
 * Class call
 * @package core\component\callTracking
 */
class call
{
    /**
     * @var int
     */
    private $phone;

    /**
     * @var int
     */
    private $phone_id;
    /**
     * @var int
     */
    private $durability;

    /**
     * @var string
     */
    private $record;

    /**
     * @var int
     */
    private $visit_id;

    /**
     * @var int
     */
    private $external_id;

    /**
     * call constructor.
     * @param array $array
     */
    public function __construct(array $array = [])
    {
        $this->external_id      =   $array['external_id']       ??  0;
        $this->phone            =   $array['phone']             ??  0;
        $this->phone_id         =   $array['phone_id']          ??  0;
        $this->visit_id         =   $array['visit_id']          ??  0;
        $this->durability       =   $array['durability']        ??  0;
        $this->record           =   $array['record']            ??  '';

    }

    /**
     * Сохранение
     */
    public function save(): void
    {
        /** @var \core\PDO\PDO $db */
        $db     =   registry::get('db');
        $where  = [
            'external_id'   =>  $this->external_id
        ];
        if ($db->selectCount('callTracking_call', 'id', $where) > 0) {
            $value  =   [
                'durability'        =>  $this->durability,
                'record'            =>  $this->record,
            ];
            $db->update('callTracking_call', $value, $where);
        } else {
            $value  =   [
                'external_id'       =>  $this->external_id,
                'phone'             =>  $this->phone,
                'phone_id'          =>  $this->phone_id,
                'visit_id'          =>  $this->visit_id
            ];
            $db->inset('callTracking_call', $value);
        }
    }

    /**
     * скачивание записей
     */
    public static function downloadRecord() : void
    {
        /** @var \core\PDO\PDO $db */
        $db     =   registry::get('db');
        $where  =   [
            'record_is_downloaded' => 0
        ];
        $row    =   $db->selectRow('callTracking_call', '*', $where, 'date_update ASC', '0,1');
        if (isset($row['record'])) {
            $file = file_get_contents($row['record']);
            if ($file) {
                $name = uniqid($row['id'] . '_' . date('Y-m-d') . '_', true) . '.mp3';
                $dirAbsolute = fileCache::getDir('record', false);
                $dir = fileCache::getDir('record');
                file_put_contents("{$dirAbsolute}/{$name}", $file);
                $where = [
                    'id' => $row['id']
                ];
                $value = [
                    'record_is_downloaded' => 1,
                    'record' => "{$dirAbsolute}/{$name}"
                ];
                $db->update('callTracking_call', $value, $where);
            }
        }
    }
}