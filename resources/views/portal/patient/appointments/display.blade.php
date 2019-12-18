<?php
    Namespace App;
    Use Carbon\Carbon;
    Use App\Form;
    Use App\Practitioner;
    Use App\Patient;
    Use App\Appointment;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Request;

    // include_once app_path("/php/functions.php");

    $ctrl = new Form;

    if (isset($_POST['OptParams'])){
    	// echo "opt params passed";	
    }else{
    	// echo "no opt params passed";
    }
    $patient = Patient::find(Auth::user()->patientInfo->id);
    $patientInfo = [
        'id' => $patient->id,
        'isNewPatient' => ($patient->isNewPatient() == 'true'),
        'name' => getNameFromUid('Patient',$patient->id)
    ];
    $whichFirstOptions = ['Select Service','Select Practitioner','ID*WhichFirstBtn']
?>  

<h2 class="purple paddedSmall">Your Appointments</h2>
<div id="PatientCalendar" class='calendar patient' data-patient='{{json_encode($patientInfo)}}'>
    <div class='lds-ring dark'><div></div><div></div><div></div><div></div></div>
</div>
<div id='ScheduleFeedTarget'></div>
<div id="ApptInfo" class="modalForm prompt">
    <div class="message">
        <h1 class='purple'>Appointment Details</h1>
        <div class='split3366KeyValues'>
            <div>
                <span class="label">Practitioner:</span>
                <span class='value' id="PractitionerName"></span>
            </div>
            <div>
                <span class="label">Date + Time:</span>
                <span class='value' id="ApptDateTime"></span>
            </div>
            <div>
                <span class="label">Services:</span>
                <span class='value' id="ServiceInfo"></span>
            </div>
            <div>
                <span class="label">Required Forms:</span>
                <span class='value' id="FormInfo"></span>
            </div>
        </div>
    </div>
    <div class="options">
        <div class="button medium pink" id="EditApptBtn">change appointment</div>
        <div class="button medium pink70" id="DeleteApptBtn">cancel appointment</div>
        <div class="button medium cancel">dismiss</div>
    </div>
</div>

@include ('models.create-modal',["model" => "Appointment"])
@include ('models.edit-modal',["model" => "Appointment"])

<div id="WhichFirst" class='progressiveSelection'>
    {{$ctrl->answerDisp('radio',$whichFirstOptions)}}
</div>

@include ('schedules.services')
@include ('schedules.practitioners')
@include ('schedules.times')

<div id="Details">
    <h3 class="services">
        <span class='type purple'>Services:</span>
        <span class='info'>
            <span class='value pink'>none</span>
            <span class='edit yellow italic' data-target="#SelectServices">select</span>            
        </span>
    </h3>
    <h3 class="date">
        <span class='type purple'>Date:</span>
        <span class='info'>            
            <span class='value pink'>none</span>
            <span class='edit yellow italic' data-target="#SelectDate">select</span>
        </span>
    </h3>
    <h3 class="time">
        <span class='type purple'>Time:</span>
        <span class='info'>
            <span class='value pink'>none</span>
            <span class='edit yellow italic' data-target="#SelectTime"></span>
        </span>
    </h3>
    <h3 class="practitioner">
        <span class='type purple'>Practitioner:</span>
        <span class='info'>
            <span class='value pink'>none</span>
            <span class='edit yellow italic' data-target="#SelectPractitioner">select</span>       
        </span>
    </h3>
</div>

@include ('schedules.scripts')
<script type='text/javascript' src="{{ asset('/js/calendar-patient.js') }}"></script>


