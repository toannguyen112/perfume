<?php

namespace App\Models\Contact;

use App\Rules\Blackwords;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use Jamstackvietnam\Support\Models\BaseModel;
use Jamstackvietnam\Support\Traits\Searchable;
use Jamstackvietnam\Support\Traits\HasNotification;
use App\Models\Blog\Job;

class Contact extends BaseModel
{
    use HasFactory;
    use Searchable;
    use HasNotification;

    const TYPE_CONTACT_FORM = 'CONTACT_FORM';
    const TYPE_JOB_FORM = 'JOB_FORM';

    public const TYPE_LIST_FORM = [
        self::TYPE_CONTACT_FORM => 'Liên hệ',
        self::TYPE_JOB_FORM => 'Ứng tuyển',
    ];

    const STATUS_NEW = 'NEW';
    const STATUS_RESPONSE = 'RESPONSE';
    const STATUS_PROCESSED = 'PROCESSED';
    const STATUS_CLOSE = 'CLOSE';
    const STATUS_IS_SPAM = 'IS_SPAM';

    public const STATUS_LIST = [
        self::STATUS_NEW => 'Mới',
        self::STATUS_RESPONSE => 'Đã phản hồi',
        self::STATUS_PROCESSED => 'Đã xử lý',
        self::STATUS_CLOSE => 'Đóng',
        self::STATUS_IS_SPAM => 'Spam',
    ];

    public $fillable = [
        'data',
        'type',
        'status',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected $appends = [
        'formatted_created_at',
        'formatted_updated_at',
        'data_contact'
    ];

    protected $searchable = [
        'columns' => [
            'contacts.data' => 10,
            'contacts.id' => 5,
            'contacts.status' => 5,
        ],
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->ip_address = $model->setIpAddress();
            $model->user_agent = $model->setUserAgent();
            $model->request_url = $model->setRequestUrl();
            $model->status = $model->setStatus();
        });
    }

    public function modelRules()
    {
        return [
            'store' => [
                'data' => 'exclude_unless:id,null|array|max:20',
                'type' => 'exclude_unless:id,null|string|in:' . implode(',', array_keys(self::getTypeList())),
            ],
        ];
    }

    public static function getTypeList()
    {
        return [
            self::TYPE_CONTACT_FORM => 'Contact Form',
            self::TYPE_JOB_FORM => 'Job form',
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_NEW => 'Mới',
            self::STATUS_RESPONSE => 'Đã phản hồi',
            self::STATUS_PROCESSED => 'Đã xử lý',
            self::STATUS_CLOSE => 'Đóng',
            self::STATUS_IS_SPAM => 'Spam',
        ];
    }

    private function setIpAddress()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip();
    }

    private function setUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    private function setRequestUrl()
    {
        return parse_url(request()->headers->get('referer'), PHP_URL_HOST);
    }

    private function setStatus()
    {
        return $this->isSpamContent() ? self::STATUS_IS_SPAM : self::STATUS_NEW;
    }

    private function isSpamContent()
    {
        $rules = ['*' => ['max:1000', new Blackwords]];
        return Validator::make($this->data, $rules)->passes();
    }

    public function getDataContactAttribute()
    {
        return json_decode($this->attributes['data']);
    }

    public function transformEmailDetails()
    {

        $data = [
            'Loại form' => self::TYPE_LIST_FORM[$this->type] ?? ''
        ];
        if($this->type == self::TYPE_CONTACT_FORM)
        {
            return array_merge($data, $this->data);
        }
        if($this->type == self::TYPE_JOB_FORM) {
            $dataContact = $this->data;
            $job = Job::findOrFail($dataContact['Job']['id']);
            $dataContact['job_url'] = route("job.show", ['slug' => $job->custom_slug ?? $job->slug]);
            unset($dataContact['Job']);
            return array_merge($data, $dataContact);
        }
    }

    public function transformEmail()
    {
        $data = [
            'Loại form' => self::TYPE_LIST_FORM[$this->type] ?? ''
        ];
        if($this->type == self::TYPE_CONTACT_FORM)
        {
            return array_merge($data, $this->data, ['url' => self::getUrl()]);
        }
        if($this->type == self::TYPE_JOB_FORM)
        {
            $dataContact = [
                'Họ và tên' => $this->data['Họ và tên'],
                'Email' => $this->data['Email'],
                'Số điện thoại' => $this->data['Số điện thoại'],
                'url' => self::getUrl()
            ];
            return array_merge($data, $dataContact);
        }
    }

    public function getUrl()
    {
        if($this->type == self::TYPE_CONTACT_FORM) {
            return route('sidebar.contacts.form', [ 'id' => $this->id ]);
        }
        if($this->type == self::TYPE_JOB_FORM) {
            return route('sidebar.applies.form', [ 'id' => $this->id ]);
        }
        return null;
    }
}
