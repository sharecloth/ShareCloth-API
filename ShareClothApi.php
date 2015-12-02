<?php
/**
 * @author ShareCloth.com
 * @date November 2015
 * @contact team@sharecloth.com
 * @desc API v1 ShareCloth
 */

/**
 * Class ShareClothApi
 */
class ShareClothApi {

    /** @var string */
    private $_error;

    /** @var string */
    protected $_apiPath = 'http://api.sharecloth.com/v1/';

    /** @var string Client ID */
    protected $_clientId;

    /** @var string Secret Key */
    protected $_secret;

    /** @var string Path to pattern file */
    protected $_patternFile;

    /** @var array Array of sketches */
    protected $_sketchFiles;

    /**
     * @param int $clientId
     */
    public function setClient($clientId) {
        $this->_clientId = $clientId;
    }

    /**
     * @param string $secret
     */
    public function setSecret($secret) {
        $this->_secret = $secret;
    }

    /**
     * @param $timeStamp
     * @return string
     */
    public function generateSign($timeStamp) {
        return md5($this->_clientId . $this->_secret . $timeStamp);
    }

    /**
     * @param $path
     */
    public function setApiPath($path) {
        $this->_apiPath = 'http://' . $path . '/v1/';
    }

    /**
     * @param $filePath
     * @throws Exception
     */
    public function addSketchFile($filePath) {
        if (!file_exists($filePath)) {
            throw new \Exception('Sketch file not exists: ' . $filePath);
        }

        $this->_sketchFiles[] = $filePath;
    }

    /**
     * @param $filePath
     * @throws Exception
     */
    public function addPatternFile($filePath) {
        if (!file_exists($filePath)) {
            throw new \Exception('Pattern file not exists: ' . $filePath);
        }

        $this->_patternFile = $filePath;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function _createParams() {
        if (empty($this->_clientId)) {
            throw new \Exception('Client is empty');
        }

        if (!$this->_secret) {
            throw new \Exception('Api secret is empty');
        }

        $time = time();
        $sign = $this->generateSign($time);

        return array(
            'sign' => $sign,
            'time' => $time,
            'client' => $this->_clientId,
        );
    }

    /**
     * @param $url
     * @param $params
     * @param null $files
     * @return mixed
     */
    protected function _createRequest($url, $params, $files = null) {
        $this->_error = false;

        if (!empty($files)) {
            foreach ($files as $fileName => $file) {
                if (is_array($file)) {
                    foreach ($file as $key =>$fItem) {
                        $params[$fileName . '[' . $key . ']'] = '@' . $fItem;
                    }
                } else {
                    $params[$fileName] = '@' . realpath($file);
                }
            }
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!$result = curl_exec($ch)) {
            $this->_error = curl_error($ch);
            return false;
        }

        curl_close($ch);

        return $this->_parseResult($result);
    }

    /**
     * @return string
     */
    public function getError() {
        return $this->_error;
    }

    /**
     * @param $result
     * @return array|bool
     */
    protected function _parseResult($result) {
        if (empty($result)) {
            $this->_error = 'Result is empty';
            return false;
        }

        $result = @json_decode($result, true);
        if (empty($result)) {
            $this->_error = 'Result is empty';
            return false;
        }

        if ($result['status'] == 'error') {
            $this->_error = $result['data'];
            return false;
        }

        return $result['data'];
    }

    /**
     * @param $settings
     * @return mixed
     * @throws Exception
     */
    public function productUpload($settings) {
        $params = $this->_createParams();

        if (!empty($settings['collection_id'])) {
            $params['collection_id'] = $settings['collection_id'];
        }

        if (empty($settings['name'])) {
            throw new \Exception('Name is empty');
        }

        $params['model_name'] = $settings['name'];

        if (empty($settings['description'])) {
            throw new \Exception('Model_name is empty');
        }

        $params['description'] = $settings['description'];

        if (empty($this->_patternFile)) {
            throw new \Exception('Pattern file is required');
        }

        if (empty($this->_sketchFiles)) {
            throw new \Exception('Sketch file is required');
        }

        $files = array('pattern_file' => $this->_patternFile);

        $files['sketch_files'] = $this->_sketchFiles;

        $params['size_type_id'] = isset($settings['size_type_id']) ? $settings['size_type_id'] : '';
        $params['size_id'] = isset($settings['size_id']) ? $settings['size_id'] : '';
        $params['collection_id'] = isset($settings['collection_id']) ? $settings['collection_id'] : '';
        $params['collection_size_id'] = isset($settings['collection_size_id']) ? $settings['collection_size_id'] : '';

        return $this->_createRequest($this->_apiPath . 'product/upload', $params, $files);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getCollections() {
        $params = $this->_createParams();
        return $this->_createRequest($this->_apiPath . 'options/collections', $params);
    }

    /**
     * @param $collectionId
     * @return mixed
     * @throws Exception
     */
    public function getCollectionSizes($collectionId) {
        $params = $this->_createParams();
        $params['collection_id'] = $collectionId;
        return $this->_createRequest($this->_apiPath . 'options/collection_sizes', $params);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getTypes() {
        $params = $this->_createParams();
        return $this->_createRequest($this->_apiPath . 'options/types', $params);
    }

    /**
     * @param $typeId
     * @return mixed
     * @throws Exception
     */
    public function getSizes($typeId) {
        $params = $this->_createParams();
        $params['type_id'] = $typeId;
        return $this->_createRequest($this->_apiPath . 'options/sizes', $params);
    }

}