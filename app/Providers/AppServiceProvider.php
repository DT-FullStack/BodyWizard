<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Message;
use App\Patient;
use App\Practitioner;
use App\StaffMember;
use App\Practice;
use App\User;
use App\Invoice;
use App\ChartNote;
use App\Form;
use App\Appointment;
use App\Events\BugReported;
use App\Events\AppointmentSaved;
use App\Notifications\NewRequiredForm;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
  public function register()
  {
    include_once app_path("/php/functions.php");
  }

  public function boot()
  {
    \Stripe\Stripe::setApiKey('sk_test_VO8cEI3MbKfcxeHOLlpjBOfa009mq5Zrze');
        // ELOQUENT MODEL EVENTS
    // Message::creating(function($model){$model->status = $model->defaultStatus();});
    // Patient::created(function($model){
    //   try{
    //     $forms = Form::where('settings->require_at_registration',true)->get();
    //     if ($model->user){
    //       foreach($forms as $form){
    //         Notification::send($model->user, new NewRequiredForm($form));
    //       }
    //     }
    //   }catch(\Exception $e){
    //     reportError($e,'AppServiceProvider 50');
    //   }
    // });
    Appointment::observe(\App\Observers\AppointmentObserver::class);
    User::observe(\App\Observers\UserObserver::class);
    Patient::observe(\App\Observers\PatientObserver::class);
    Practitioner::observe(\App\Observers\PractitionerObserver::class);
    StaffMember::observe(\App\Observers\StaffMemberObserver::class);
    ChartNote::observe(\App\Observers\ChartNoteObserver::class);
    Invoice::observe(\App\Observers\InvoiceObserver::class);
    Form::observe(\App\Observers\FormObserver::class);
  }
}
