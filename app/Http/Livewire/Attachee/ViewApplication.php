<?php

namespace App\Http\Livewire\Attachee;

use App\Models\Application;
use App\Models\ApplicationAccompaniment;
use Closure;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class ViewApplication extends Component
{
    use WithFileUploads;

    public $feedback;
    public $alert_class;
    public $alert_type;
    public $feedback_header;
    public $confirmed_action;
    public $application_letter;
    public $introduction_letter;
    public $insurance_cover;
    public $identification_document_front;
    public $identification_document_back;
    public $advert;
    public $application;
    public $user;
    public $application_accompaniments;
    public $attachment_start_date;
    public $minimum_attachment_weeks;
    public $attachment_end_date;

    protected $listeners = ['cancelApplication' => 'cancelApplication',];

    protected $rules = [
        'application_letter' => 'file|mimes:pdf,docx,odt',
        'introduction_letter' => 'file|mimes:pdf,jpg,jpeg,png',
        'insurance_cover' => 'file|mimes:pdf,jpg,jpeg,png',
        'identification_document_front' => 'file|mimes:pdf,jpg,jpeg,png,',
        'identification_document_back' => 'file|mimes:pdf,jpg,jpeg,png,',
    ];

    public function mount($id)
    {
        $this->application = Application::find($id);
        $this->application_accompaniments = $this->application->applicationAccompaniments;
        $this->attachment_start_date = $this->application->attachment_start_date;
        $this->minimum_attachment_weeks = $this->application->minimum_attachment_weeks;
        $this->attachment_end_date = $this->application->attachment_end_date;
    }
    public function render()
    {
        return view('livewire.attachee.view-application', ['application' => $this->application,], );
    }

    public function warn($action)
    {
        switch ($action) {
            case 'cancel':
                $this->feedback_header = 'Confirm Cancelation';
                $this->feedback = "Are you sure you want to cancel this application? By doing this you won't be considered for shortlisting.";
                $this->alert_class = 'alert-danger';
                $this->confirmed_action = 'cancelApplication';
                $this->alert_type = 'confirmation_prompt';
                $this->dispatchBrowserEvent('action_confirm');
                break;

            //more actions will be added here

        }
    }

    public function cancelApplication()
    {
        try {
            $this->application->central_services_approval_status = 'canceled';
            $this->application->save();

            $this->feedback_header = 'Application Cancelation Successful';
            $this->feedback = "Your application has been successfully canceled. You won't be considered for short listing in the applied post.";
            $this->alert_class = 'alert-success';
            $this->dispatchBrowserEvent('action_feedback');
        } catch (\Exception $e) {
            $this->feedback_header = 'cancelation failed';
            $this->feedback = "Sorry, something went wrong. Please try again and if the error persists contact support team for assistance.";
            $this->alert_class = 'alert-danger';
            $this->dispatchBrowserEvent('action_feedback');
        }
    }
    public function updateUploads($field)
    {

        switch ($field) {
            case 'application_letter':
                $to_be_updated = $this->application_letter;
                break;
            case 'introduction_letter':
                $to_be_updated = $this->introduction_letter;
                break;
            case 'insurance_cover':
                $to_be_updated = $this->insurance_cover;
                break;
            case 'identification_document_front':
                $to_be_updated = $this->identification_document_front;
                break;
            case 'identification_document_back':
                $to_be_updated = $this->identification_document_back;
                break;
        }
        $this->validateOnly($field);

        try {
            $to_be_updated->storePubliclyAs(
                preg_replace('/\s+/', '_', $this->application->advert->department->name) . '/' . 'application_docs/' .
                preg_replace('/\//', '_', $this->application->advert->year) . '/' . preg_replace('/[\W\s\/]+/', '_', $this->application->advert->studyArea->title) . '/' .
                auth()->user()->applicant->national_id,
                $field,
                'public'
            );
            $target = $this->application_accompaniments->where('name', $field)->first();
            $target->status = 'pending_review';
            $target->save();
        } catch (\Exception $e) {
            //Log::info($e);
            $this->feedback_header = 'Error Updating Document!!';
            $this->feedback = 'Something went wrong while updating the document. Please try again and if the error persists contact support team to resolve the issue';
            $this->alert_class = 'alert-danger';
            $this->dispatchBrowserEvent('action_feedback');
            return;
        }

        $this->feedback_header = 'success!!';
        $this->feedback = "Document has been updated successfully";
        $this->alert_class = 'alert-success';
        $this->dispatchBrowserEvent('action_feedback');
    }

    public function updateAttachmentPeriod()
    {
        $this->validate([
        'attachment_start_date'  => ['required', 'date'],
        'minimum_attachment_weeks' => ['required', 'numeric', 'integer', 'min:1', 'max:12'],
        'attachment_end_date'  => ['required', 'date', 'after:attachment_start_date', 'after:today',
            function (string $attribute, mixed $value, Closure $fail) {
                $attachment_start_date = new DateTime($this->attachment_start_date);
                $attachment_end_date = new DateTime($this->attachment_end_date);
                $expected_date = date_add($attachment_start_date, date_interval_create_from_date_string($this->minimum_attachment_weeks." weeks"));
                if ($expected_date > $attachment_end_date) {
                    $fail("Given the minimum number of weeks you have indicated, the attachment end date  has to be either on {$expected_date->format('d/m/Y')} or on a later date.");
                }
            },
        ]
        ]);

        try {
            $this->application->update([
                'attachment_start_date'  => $this->attachment_start_date,
                'minimum_attachment_weeks' =>$this->minimum_attachment_weeks,
                'attachment_end_date'  => $this->attachment_end_date,
            ]);
        } catch (\Exception $e) {
            //Log::info($e);
            $this->feedback_header = 'Error Updating Attachment Period!!';
            $this->feedback = 'Something went wrong. Please try again and if the error persists contact support team to resolve the issue';
            $this->alert_class = 'alert-danger';
            $this->dispatchBrowserEvent('action_feedback');
            return;
        }

        $this->feedback_header = 'success!!';
        $this->feedback = "Attachment period has been updated successfully";
        $this->alert_class = 'alert-success';
        $this->dispatchBrowserEvent('action_feedback');
    }
}