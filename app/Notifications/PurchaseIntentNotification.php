<?php

namespace App\Notifications;

use App\Models\PurchaseIntent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseIntentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected PurchaseIntent $intent;

    /**
     * Create a new notification instance.
     */
    public function __construct(PurchaseIntent $intent)
    {
        $this->intent = $intent;
    }

    /**
     * Delivery channels
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Mail notification
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Purchase Intent Raised')
            ->greeting('Hello Purchase Team,')

            ->line('A new Purchase Intent has been automatically generated.')

            ->line('BOM Reference: ' .
                $this->intent->batch?->bomHeader?->bom_reference)

            ->line('Item Code: ' .
                $this->intent->item_code)

            ->line('Required Quantity: ' .
                $this->intent->required_qty)

            ->line('Available Quantity: ' .
                $this->intent->available_qty)

            ->line('Shortfall Quantity: ' .
                $this->intent->shortfall_qty)

            ->line('Priority: ' .
                $this->intent->priority)

            ->action(
                'View Purchase Intents',
                url('/purchase-intents')
            )

            ->line('Please review and raise PO accordingly.');
    }

    /**
     * Database notification
     */
    public function toArray(object $notifiable): array
    {
        return [
            'purchase_intent_id' => $this->intent->id,

            'item_code' => $this->intent->item_code,

            'shortfall_qty' => $this->intent->shortfall_qty,

            'priority' => $this->intent->priority,

            'message' => 'New Purchase Intent created for item: '
                . $this->intent->item_code,
        ];
    }
}