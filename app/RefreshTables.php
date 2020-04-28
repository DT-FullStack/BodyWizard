<?php

namespace App;

use App\User;
use App\Patient;
use App\StaffMember;
use App\Practitioner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RefreshTables extends Model
{
    //
    public static function clearAuditTables($modelsArr){
        try{
            $tableNames = collect($modelsArr)->map(function($modelName){
                return snake(plural($modelName)).'_audit';
            })->toArray();
            foreach ($tableNames as $table){
                DB::table($table)->truncate();
            }
            return true;
        }catch(\Exception $e){
            reportError($e,'RefreshTables');
            return $e;
        }
    }
    public static function clearUserTables(){
    	try{
			Artisan::call("migrate:refresh --path database/migrations/2014_10_12_000000_create_users_table.php");
			Artisan::call("migrate:refresh --path database/migrations/2019_08_14_203443_create_practitioners_table.php");
			Artisan::call("migrate:refresh --path database/migrations/2019_08_14_132627_create_staff_members_table.php");
			Artisan::call("migrate:refresh --path database/migrations/2019_08_14_132458_create_patients_table.php");
            RefreshTables::clearAuditTables(['user','practitioner','staff member','patient']);
			return true;
    	}catch(\Exception $e){
    		reportError($e,'RefreshTables');
            return $e;
    	}
    }
    public static function clearApptTables(){
        try{
            Invoice::where('appointment_id','!=',null)->delete();
            Artisan::call("migrate:refresh --path database/migrations/2019_05_29_164721_create_appointments_table.php");
            Artisan::call("migrate:refresh --path database/migrations/2019_09_18_132210_create_appointmentables_table.php");
            DB::table('serviceables')->where('serviceable_type','App\Appointment')->delete();
            RefreshTables::clearAuditTables(['appointment','invoice']);
            return true;
        }catch(\Exception $e){
            reportError($e,'RefreshTables');
            return $e;
        }
    }
    public static function clearSubmissionTables(){
        try{
            Artisan::call("migrate:refresh --path database/migrations/2019_05_29_154608_create_submissions_table.php");
            Artisan::call("migrate:refresh --path database/migrations/2020_02_17_231953_create_submissionables_table.php");
            return true;
        }catch(\Exception $e){
            reportError($e,'RefreshTables');
            return $e;
        }        
    }
    public static function clearChartNoteTables(){
        try{
            Artisan::call("migrate:refresh --path database/migrations/2019_07_10_173634_create_chart_notes_table.php");
            // Artisan::call("migrate:refresh --path database/migrations/2020_01_02_183757_create_chart_notes_audit_table.php");
            RefreshTables::clearAuditTables(['chart note']);            
            return true;
        }catch(\Exception $e){
            reportError($e,'RefreshTables');
            return $e;
        }        
    }
    public static function clearInvoiceTables(){
        Artisan::call('migrate:refresh --path database/migrations/2019_07_26_130209_create_invoices_table.php');
            RefreshTables::clearAuditTables(['invoice']);            
    }
    public static function clearNotificationTables(){
        try{
            Artisan::call("migrate:refresh --path database/migrations/2019_12_13_151342_create_notifications_table.php");
            return true;
        }catch(\Exception $e){
            reportError($e,'RefreshTables');
            return $e;
        }        
    }
    public static function clearComplaintTables(){
        try{
            Artisan::call("migrate:refresh --path database/migrations/2020_04_01_202901_create_complaint_categories_table.php");
            Artisan::call("migrate:refresh --path database/migrations/2019_05_29_154552_create_complaints_table.php");
            return true;
        }catch(\Exception $e){
            reportError($e,'RefreshTables 86');
            return false;
        }        
    }
    public static function clearBugTables(){
        try{
            Artisan::call("migrate:refresh --path database/migrations/2019_12_13_122629_create_bugs_table.php");
            return true;
        }catch(\Exception $e){
            reportError($e,'RefreshTables');
            return $e;
        }        
    }
    public static function createDefaultUser(){
    	try{
		    $admin = new User;
		    $admin->first_name = "Bryan";
		    $admin->middle_name = "David";
		    $admin->last_name = "Taylor";
		    $admin->preferred_name = "David";
		    $admin->user_type = "practitioner";
            $admin->is_admin = 1;
            $admin->require_new_pw = 0;
            $admin->security_questions = null;
		    $admin->username = "david";
		    $admin->date_of_birth = '1985-10-14';
		    $admin->email = "david@bodywizardmedicine.com";
		    $admin->phone = "512-514-3706";
		    $admin->password = '$2y$10$chX/ZsWKiiEaLrI59N5nkuYYb.VZi9WXEf53DPmq.ko1iIGZVjyt2';
		    $admin->full_json = '{"Sections": [{"Name": "Personal Information", "Items": [{"type": "date", "question": "Date of Birth", "response": ["10/14/1985"]}, {"type": "text", "question": "First Name", "response": ["Bryan"]}, {"type": "text", "question": "Middle Name", "response": ["David"]}, {"type": "text", "question": "Last Name", "response": ["Taylor"]}, {"type": "text", "question": "Preferred Name", "response": ["David"]}]}, {"Name": "Contact Information", "Items": [{"type": "text", "question": "Phone Number", "response": ["5125143706"]}, {"type": "text", "question": "Email Address", "response": ["david@bodywizardmedicine.com"]}]}, {"Name": "Login Information", "Items": [{"type": "narrative", "question": "", "response": []}, {"type": "text", "question": "Username", "response": ["david"]}, {"type": "text", "question": "Password", "response": []}, {"type": "text", "question": "Confirm Password", "response": []}]}]}';
    		$admin->save();
	        $practitioner = new Practitioner;
	        $practitioner->user_id = $admin->id;
	        $practitioner->save();
			return true;
    	}catch(\Exception $e){
    		reportError($e,'RefreshTables');
            return $e;
    	}
    }
    public static function seedUserTables(){
        $patients = factory(User::class,10)->states('patient')->create()
                    ->each(function($user){
                        $user->patientInfo()->save(
                            factory(Patient::class)->create()
                        );
                    });
        $practitioners = factory(User::class,2)->states('practitioner')->create()
                            ->each(function($user){
                                $user->practitionerInfo()->save(
                                    factory(Practitioner::class)->create()
                                );
                            });
        $staffmembers = factory(User::class,2)->states('staff member')->create()
                        ->each(function($user){
                            $user->staffMemberInfo()->save(
                                factory(StaffMember::class)->create()
                            );
                        });
    }
    public static function seedApptTables($apptCount){
        $apptCount = (int)$apptCount;
        try{
            $appts = factory(Appointment::class, $apptCount)
                     ->create();
            return true;
        }catch(\Exception $e){
            reportError($e,'RefreshTables 145');
            return false;
        }
    }
}
