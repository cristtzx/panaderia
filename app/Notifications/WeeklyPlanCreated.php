<?php
namespace App\Notifications;

use App\Models\WeeklyPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class WeeklyPlanCreated extends Notification {
    use Queueable;

    public $plan;

    public function __construct(WeeklyPlan $plan) {
        $this->plan = $plan;
    }

    public function via($notifiable) {
        return ['mail'];
    }

    public function toMail($notifiable) {
        // Convertir explícitamente a Carbon
        $fecha = Carbon::parse($this->plan->day);
        
        return (new MailMessage)
            ->subject('✅ Nueva Planificación Semanal: ' . $this->plan->receta->nombre)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Se ha registrado una nueva planificación de producción:')
            ->line('📅 **Fecha:** ' . $fecha->format('d/m/Y (l)'))
            ->line('🍞 **Receta:** ' . $this->plan->receta->nombre)
            ->line('🔢 **Unidades planificadas:** ' . $this->plan->unidades_esperadas)
            ->line('📝 **Notas:** ' . ($this->plan->notas ?? 'Ninguna'))
            ->action('Ver Planificación Completa', url('/weekly-plans'))
            ->line('Gracias por usar nuestro sistema de planificación.')
            ->salutation('Saludos, Equipo de Producción');
    }
}