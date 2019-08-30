<?php
namespace Aliyun;

require_once './Core/Config.php';

class DetectQRCodes extends \RpcAcsRequest
{
    public $regionId;
    public $accessId;
    public $accessKey;
    public $bucket;

    protected $clientProfile;

    public function setClientProfile()
    {
        $this->clientProfile = DefaultProfile::getProfile(
            $this->regionId,                   # 您的 Region ID 
            $this->accessId,               # 您的 AccessKey ID
            $this->accessKey                 # 您的 AccessKey Secret
        );
    }

    /**
     * 检测图片里面是否有二维码
     * @param [type] $srcUris
     * @return void
     */
    public function detect($url)
    {
        $this->setSrcUris($this->getBucketPath($url));
        $client = new DefaultAcsClient($this->clientProfile);
        return $client->getAcsResponse($this);
    }

    /**
     * @var string
     */
    protected $method = 'POST';

    /**
     * Class constructor.
     */
    public function __construct($bucket, $projectName, $regionId, $accessId, $accessKey)
    {
        $this->regionId  = $regionId;
        $this->accessId  = $accessId;
        $this->accessKey = $accessKey;
        $this->bucket    = $bucket;

        $this->setProject($projectName);
        $this->setClientProfile();


        parent::__construct(
            'imm',
            '2017-09-06',
            'DetectQRCodes',
            'imm'
        );
    }

    /**
     * 获取oss地址
     *
     * @param [type] $url
     * @return void
     */
    public function getBucketPath($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        return "oss://".$this->bucket.$path;
    }

    /**
     * @param string $srcUris
     *
     * @return $this
     */
    public function setSrcUris($srcUris)
    {
        $this->requestParameters['SrcUris'] = $srcUris;
        $this->queryParameters['SrcUris'] = $srcUris;
        return $this;
    }

    /**
     * @param string $project
     *
     * @return $this
     */
    public function setProject($project)
    {
        $this->requestParameters['Project'] = $project;
        $this->queryParameters['Project'] = $project;
        return $this;
    }
}
