<?php

namespace App\Http\Livewire;

use App\Events\ApplicationReplied;
use Livewire\Component;
use App\Models\Application;
use App\Models\ApplicationAccompaniment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseApplications extends Component
{
    public $feedback;
    public $alert_class;
    public $feedback_header;
    public $confirmed_action;
    public $confirmed_action_parameter;
    public $intended_status;
    public $intended_action;
    public $review_remarks;


    // public function setIntendedStatus($status, $action)
    // {
    //     $this->intended_status = $status;
    //     $this->intended_action = $action;
    // }

    public function acceptAll()
    {
        $docs = ['application_letter', 'introduction_letter', 'insurance_cover', 'identification_document_front'];
        if (ApplicationAccompaniment::where('application_id', $this->application->id)
                ->where('name', 'identification_document_back')->exists()){
                array_push($docs, 'identification_document_back');
        }
        $this->intended_status = 'accepted';
        $this->review_remarks = '';
        foreach ($docs as $key => $doc) {
            $this->act($doc);
        }
    }

    public function act($field)
    {
        try {

            switch ($field) {
                case 'application_letter':
                    ApplicationAccompaniment::where('application_id', $this->application->id)
                        ->where('name', 'application_letter')->update([
                            'status' => $this->intended_status,
                            'review_remarks' => $this->review_remarks == '' ? 'no remarks' : $this->review_remarks,

                        ]);

                    break;
                case 'introduction_letter':
                    ApplicationAccompaniment::where('application_id', $this->application->id)
                        ->where('name', 'introduction_letter')->update([
                            'status' => $this->intended_status,
                            'review_remarks' => $this->review_remarks == '' ? 'no remarks' : $this->review_remarks,

                        ]);
                    break;
                case 'insurance_cover':
                    ApplicationAccompaniment::where('application_id', $this->application->id)
                        ->where('name', 'insurance_cover')->update([
                            'status' => $this->intended_status,
                            'review_remarks' => $this->review_remarks == '' ? 'no remarks' : $this->review_remarks,

                        ]);
                    break;
                case 'identification_document_front':
                    ApplicationAccompaniment::where('application_id', $this->application->id)
                        ->where('name', 'identification_document_front')->update([
                            'status' => $this->intended_status,
                            'review_remarks' => $this->review_remarks == '' ? 'no remarks' : $this->review_remarks,

                        ]);
                    break;
                case 'identification_document_back':
                    ApplicationAccompaniment::where('application_id', $this->application->id)
                        ->where('name', 'identification_document_back')->update([
                            'status' => $this->intended_status,
                            'review_remarks' => $this->review_remarks == '' ? 'no remarks' : $this->review_remarks,

                        ]);
                    break;
            }

        } catch (\Exception $e) {
            //Log::info($e);
            $this->feedback_header = 'Error Performing action!!';
            $this->feedback = 'Something went wrong while performing the intended action. Please try again and if the error persists contact support team to resolve the issue';
            $this->alert_class = 'alert-danger';
            $this->dispatchBrowserEvent('action_feedback');
            return;
        }

        $this->feedback_header = 'success!!';
        $this->feedback = "Action done successfully";
        $this->alert_class = 'alert-success';
        $this->dispatchBrowserEvent('action_feedback');
    }

    public function warn($action, $id)
    {
        switch ($action) {
            case 'delete':
                $this->feedback_header = 'Confirm Deletion';
                $this->feedback = 'Are you sure you want to delete this application? This action is irrevasible.';
                $this->alert_class = 'alert-danger';
                $this->confirmed_action = 'deleteApplication';
                $this->confirmed_action_parameter = $id;
                $this->dispatchBrowserEvent('action_confirm');
                break;
            case 'reject':
                $this->feedback_header = 'Confirm Rejection';
                $this->feedback = 'Are you sure you want to reject this application? This action is irrevasible.';
                $this->alert_class = 'alert-warning';
                $this->confirmed_action = 'rejectApplication';
                $this->confirmed_action_parameter = $id;
                $this->dispatchBrowserEvent('action_confirm');
                break;
            case 'accept':
                $application = Application::find($id);
                if( $application->applicationAccompaniments->contains('status', '!==', 'accepted')){
                    $this->feedback_header = 'Action Denied!!';
                    $this->feedback = "You can't accept this application before you approve all the application accompaniments.";
                    $this->alert_class = 'alert-danger';
                    $this->dispatchBrowserEvent('action_feedback');
                } else {
                    $this->feedback_header = 'Confirm Acceptance';
                    $this->feedback = 'Are you sure you want to accept this Application? The applicant will receive a letter of offer immediately.';
                    $this->alert_class = 'alert-warning';
                    $this->confirmed_action = 'acceptApplication';
                    $this->confirmed_action_parameter = $id;
                    $this->dispatchBrowserEvent('action_confirm');
                }
                break;

        }
    }

    public function rejectApplication($id)
    {
        try {
            $application = Application::find($id);

            Application::where('id', $id)->update(
                [
                    'central_services_approval_status' => 'rejected',
                    'date_replied' => \Carbon\Carbon::now(),
                ]
            );
            $applicant = $application->applicant;
            if ($applicant->engagement_level < 3) {
                $applicant->engagement_level = 3;
                $applicant->save();
            }
            $application->refresh();
            $message = 'Dear ' . $applicant->first_name . ', your application has been successfully Processed. Please follow the link below to get your response letter.';
            ApplicationReplied::dispatch($application, $message);
        } catch (\Exception $e) {
            $this->feedback_header = 'Error performing requested action!!';
            $this->feedback = 'Something went wrong. Please try again and if the error persists contact support team to resolve the issue';
            $this->alert_class = 'alert-danger';
            $this->dispatchBrowserEvent('action_feedback');
        }

        $this->feedback_header = 'Success!!';
        $this->feedback = 'Application rejected successfully';
        $this->alert_class = 'alert-success';
        $this->dispatchBrowserEvent('action_feedback');

    }

    public function acceptApplication($id)
    {
        try {
            $application = Application::find($id);

            Application::where('id', $id)->update(
                [
                    'central_services_approval_status' => 'accepted',
                    'date_replied' => \Carbon\Carbon::now(),
                ]
            );
            $applicant = $application->applicant;
            if ($applicant->engagement_level < 3) {
                $applicant->engagement_level = 3;
                $applicant->save();
            }
            $application->refresh();
            $message = 'Congratulations ' . $applicant->first_name . ', your application has been successfully Processed. Click on the response letter link below to access your response letter.';
            ApplicationReplied::dispatch($application, $message);
        } catch (\Exception $e) {
            $this->feedback_header = 'Error performing requested action!!';
            $this->feedback = 'Something went wrong. Please try again and if the error persists contact support team to resolve the issue';
            $this->alert_class = 'alert-danger';
            $this->dispatchBrowserEvent('action_feedback');
        }

        $this->feedback_header = 'Success!!';
        $this->feedback = 'Application accepted successfully';
        $this->alert_class = 'alert-success';
        $this->dispatchBrowserEvent('action_feedback');

    }

    public function revokeApplicationAcceptance($id)
    {
        $this->validate();
        try {
            $application = Application::find($id);

            Application::where('id', $id)->update(
                [
                    'central_services_approval_status' => 'revoked',
                    'date_replied' => \Carbon\Carbon::now(),
                ]
            );
            $application->refresh();
            if ($application->applicant->engagement_level < 4) {
                $application->applicant->engagement_level = 4;
                $application->applicant->save();
            }
            $message = 'Dear ' . $application->applicant->first_name . ', Due to reasons stated below, your application acceptance for the post (' . $application->advert->studyArea->title . ') has been revoked.
            You may contact us for more information.';
            ApplicationReplied::dispatch($application, $message, $this->revocation_reasons);
        } catch (\Exception $e) {
            $this->feedback_header = 'Error performing requested action!!';
            $this->feedback = 'Something went wrong. Please try again and if the error persists contact support team to resolve the issue';
            $this->alert_class = 'alert-danger';
            $this->dispatchBrowserEvent('action_feedback');
        }

        $this->feedback_header = 'Success!!';
        $this->feedback = 'Application acceptance revoked successfully';
        $this->alert_class = 'alert-success';
        $this->dispatchBrowserEvent('action_feedback');

    }

    public function deleteApplication($id)
    {
        try {
            Application::where('id', $id)->destroy();
        } catch (\Exception $e) {
            $this->feedback_header = 'Error performing requested action!!';
            $this->feedback = 'Something went wrong. Please try again and if the error persists contact support team to resolve the issue';
            $this->alert_class = 'alert-danger';
            $this->dispatchBrowserEvent('action_feedback');
        }

        $this->feedback_header = 'Success!!';
        $this->feedback = 'Application deleted successfully';
        $this->alert_class = 'alert-success';
        $this->dispatchBrowserEvent('action_feedback');

    } 
}
