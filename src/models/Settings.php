<?php

namespace liquidbcn\craftcmsreact\models;

use Craft;
use craft\base\Model;

/**
 * craftcms-react settings
 */
class Settings extends Model
{

    public $env = 'client_side';
    public $serverBundle = 'app/server-bundle.js';

    public function rules(): array {
        return [
            [['env', 'serverBundle'], 'required'],
        ];
    }

}
