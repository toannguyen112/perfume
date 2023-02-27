<?php

namespace Jamstackvietnam\Support\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $email = (new MailMessage)
            ->subject($this->data['mail_title'])
            ->greeting($this->data['mail_title']);
        unset($this->data['mail_title']);
        foreach ($this->data as $key => $value) {
            if (is_array($this->data[$key])) {
                $email->line(new HtmlString($this->group($key)));
                foreach ($this->data[$key] as $item => $res) {
                    $email->line(Str::title(str_replace('_', ' ', $item)) . ': ' . $res);
                }
            } else {
                if($key == 'url') {
                    $email->line(new HtmlString($this->bottom('Xem chi tiết liên hệ', $value)));
                }
                else if($key == 'job_url') {
                    $email->line(new HtmlString($this->bottom('Chi tiết tuyển dụng', $value)));
                }
                else if($key == 'order_url') {
                    $email->line(new HtmlString($this->bottom('Chi tiết đơn hàng', $value)));
                }
                else {
                    $email->line(Str::title(str_replace('_', ' ', $key)) . ': ' . $value);
                }
            }
        }

        return $email;
    }

    public function bottom($title, $url)
    {
        return '<div style="display: flex; justify-content: center; margin-top: 12px; margin-bottom: 12px">
        <a href="'.$url.'" style="box-sizing:border-box; border-radius:4px; display:inline-flex; justify-content: center; margin: 0 auto; padding-top: 8px; padding-bottom: 8px; padding-left: 18px; padding-right: 18px; background-color: #2d3748; text-decoration: none; color: white">'.$title.'</a>
        </div>';
    }

    public function group($group)
    {
        return '<span style="color:blue;font-weight:bold">'.$group.'</span>';
    }
}
