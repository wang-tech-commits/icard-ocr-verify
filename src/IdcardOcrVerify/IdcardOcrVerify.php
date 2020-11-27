<?php

namespace MrwangTc\IdcardOcrVerify;

use MrwangTc\IdcardOcrVerify\Exceptions\ApiException;
use MrwangTc\IdcardOcrVerify\Exceptions\VerifyException;
use Illuminate\Support\Facades\Storage;
use MrwangTc\IdcardOcrVerify\Models\OcrStorage;
use Godruoyi\OCR\Application;
use Illuminate\Support\Str;

class IdcardOcrVerify
{

    const FRONT = 'front';
    const BACK  = 'back';

    const TYPES = [
        self::BACK  => '身份证正面',
        self::FRONT => '身份证反面',
    ];

    public function verify($image, $type)
    {
        if (!$this->checkType($type)) {
            throw new VerifyException('身份证正反面参数错误');
        }
        $storage = OcrStorage::where('name', $image)->first();
        if ($storage) {
            return $storage;
        } else {
            $configType = config('idcardocrverify.default');
            switch ($configType) {
                case 'aliyun':
                    $config      = config('idcardocrverify.drivers.aliyun');
                    $application = new Application($config);
                    $response    = $application->aliyun->idcard($image, [
                        'side' => $type,
                    ]);
                    break;
                case 'baidu':
                    $config      = config('idcardocrverify.drivers.baidu');
                    $application = new Application($config);
                    $response    = $application->baidu->idcard($image, [
                        'id_card_side'     => $type,
                        'detect_direction' => false,
                    ]);
                    break;
                case 'tencent':
                    $config      = config('idcardocrverify.drivers.tencent');
                    $application = new Application($config);
                    $response    = $application->tencent->idcard($image, [
                        'Region'   => 'ap-shanghai',
                        'CardSide' => Str::upper($type),
                        'Config'   => json_encode(['CropIdCard' => false]),
                    ]);
                    break;
                default:
                    throw new VerifyException('未知驱动');
                    break;
            }
            $body = $response->toArray();
            if (isset($body['Response']['Error']) && !empty($body['Response']['Error'])) {
                throw new ApiException($body['Response']['Error']['Message']);
            } else {
                $idCard = $body['Response']['IdNum'];
            }
            $size = Storage::size($image);
        }

    }

    protected function checkType($type)
    {
        return in_array($type, self::TYPES);
    }

}