<?php

namespace Jamstackvietnam\Support\Traits;

use Illuminate\Support\Facades\Notification;
use Jamstackvietnam\Support\Notifications\CommonNotification;

trait HasNotification
{
    public static function bootHasNotification()
    {
        static::created(function ($model) {
            if (request()->route() === null) return;

            if ($emailTo = env('MAIL_NOTIFICATION_TO')) {

                $emails = explode(',', $emailTo);

                $data['mail_title'] = 'Bạn nhận được liên hệ mới';

                if (method_exists($model, 'transformEmail')) {
                    $data = array_merge($data, $model->transformEmail());
                }

                foreach($emails as $email)
                {
                    Notification::route('mail', $email)
                        ->notify(new CommonNotification($data));
                }
            }

            // send customer
            if (method_exists($model, 'transformEmailDetails')) {
                $data = $model->transformEmailDetails();
            } else {
                $data = $model->data;
            }

            $data['mail_title'] = 'Chúc mừng bạn gửi form thành công';
            if (isset($data['Email'])) {
                $emailTo = $data['Email'];

                Notification::route('mail', $emailTo)
                    ->notify(new CommonNotification($data));
            }
        });
    }
}
