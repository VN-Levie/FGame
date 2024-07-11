<?php

namespace Models;

use PDO;

class Traffic extends Model
{
    protected static $table = 'traffics';

    public $id;
    public $ip;
    public $user_agent;
    public $user_id;
    public $count_up;
    public $updated_at;
    public $created_at;



    public static function checkAndCountUpOrInsert($ip, $user_agent, $user_id)
    {

        $traffic = Traffic::findOne(['ip' => $ip, 'user_id' => $user_id, 'user_agent' => $user_agent]);
        if ($traffic) {

            $traffic->count_up++;
            return $traffic->save();
        } else {
            // Nếu không tồn tại, hoặc tồn tại nhưng user_id khác, chèn mới
            $data = [
                'ip' => $ip,
                'user_agent' => $user_agent,
                'user_id' => $user_id,
                'count_up' => 1
            ];
            return self::create($data);
        }
    }

    






}
