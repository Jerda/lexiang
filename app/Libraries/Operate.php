<?php
namespace App\Libraries;

use App\Model\Operate as Operator;
use Illuminate\Support\Facades\Validator;

class Operate
{

    public static function record($data)
    {
        self::validator($data);

        Operator::create([
            'type' => $data['type'],
            'content' => $data['content'],
            'model' => isset($data['model']) ? $data['model'] : null,
            'model_id' => isset($data['model_id']) ? $data['model_id'] : null,
        ]);
    }


    public static function listByOne($data)
    {
        $operators = Operator::where([
            ['model', $data['model']],
            ['model_id', $data['model_id']],
        ])->get();

        return $operators;
    }


    /**
     * 字段验证
     * @param $data
     * @throws \Exception
     */
    protected static function validator($data)
    {
        $validator = Validator::make($data, [
            'type' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }
}
