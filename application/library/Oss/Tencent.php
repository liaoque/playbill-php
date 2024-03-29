<?php

namespace Oss;

use AppUtils\Config;
use Jcupitt\Vips\Image;
use Qcloud\Cos\Client;

class Tencent extends \Upload\Storage\Base implements OssInterface
{

    /**
     * @var Client
     */
    private Client $client;
    private $bucket;
    private $config;
    private $filePath;


    public function __construct($config)
    {
        $secretId = $config->get('secretId');
        $secretKey = $config->get('secretKey');
        $region = $config->get('region');
        $this->bucket = $config->get('bucket');
        $this->config = $config;

        $this->client = new Client([
            'region' => $region,
            'schema' => 'http',
            'credentials' => ['secretId' => $secretId, 'secretKey' => $secretKey]
        ]);
    }

    public function put(Image $image, \stdClass $params): OssResult
    {
        $fileName = date('Ymd') . '/' . $params->data->filename .$params->data->mime_type;
        $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $fileName,
            'Body' => $image->writeToBuffer($params->data->mime_type)
        ]);
        $this->filePath = $this->bucket . '/' . $fileName;
        return new OssResult([
            'file' => $fileName,
            'src' => $fileName
        ]);
    }


    public function upload(\Upload\File $file, $newName = null)
    {
        if (is_string($newName)) {
            $fileName = strpos($newName, '.') ? $newName : $newName . '.' . $file->getExtension();
        } else {
            $fileName = $file->getNameWithExtension();
        }
        $newFile = date('Ymd') . '/' . $fileName;
        $this->filePath = $this->bucket . '/' . $fileName;
        // TODO: Implement upload() method.
        $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $newFile,
            'Body' => fopen($file->getPathname(), 'rb')
        ]);
        return true;
    }

    public function getFilePath(): string
    {
        // TODO: Implement getFilePath() method.
        return $this->filePath;
    }
}
